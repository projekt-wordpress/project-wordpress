<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// REST API endpoint
add_action('rest_api_init', function () {
    register_rest_route('custom-registration', '/register', array(
        'methods' => 'POST',
        'callback' => 'br_rest_registration_handler',
        'permission_callback' => '__return_true', // only for not logged in users
    ));
});

function br_rest_registration_handler($request) {
    global $wpdb;
    $params = $request->get_json_params();

    $login = sanitize_user($params['u_login'] ?? '');
    $email = sanitize_email($params['u_email'] ?? '');
    $pass  = $params['u_pass'] ?? '';
    $role  = sanitize_text_field($params['user_role'] ?? 'rodzic');
    $token_str = sanitize_text_field($params['guardian_token'] ?? '');

    // Validation
    if (empty($login) || empty($email) || empty($pass)) {
        return new WP_REST_Response(['success' => false, 'error' => 'empty_fields'], 400);
    }

    $guardian_data = null;
    if ($role === 'dziecko') {
        if (empty($token_str)) {
            return new WP_REST_Response(['success' => false, 'error' => 'missing_token'], 400);
        }
        $tokens_table = $wpdb->prefix . 'guardian_tokens';
        $guardian_data = $wpdb->get_row($wpdb->prepare("SELECT id, guardian_id FROM $tokens_table WHERE token = %s", $token_str));
        
        if (!$guardian_data) {
            return new WP_REST_Response(['success' => false, 'error' => 'invalid_token'], 400);
        }
    }

    // Register user
    $user_id = wp_create_user($login, $pass, $email);

    if (is_wp_error($user_id)) {
        return new WP_REST_Response(['success' => false, 'error' => 'user_exists'], 400);
    }

    $user = new WP_User($user_id);
    $user->set_role($role);

    // Save relations and generate token
    $response_data = ['success' => true, 'role' => $role];

    if ($role === 'dziecko' && $guardian_data) {
        $wpdb->insert(
            $wpdb->prefix . 'user_guardian_child',
            ['guardian_id' => $guardian_data->guardian_id, 'child_id' => $user_id, 'token_id' => $guardian_data->id],
            ['%d', '%d', '%d']
        );
    } elseif ($role === 'rodzic') {
        $new_token = strtoupper(wp_generate_password(8, false));
        $wpdb->insert(
            $wpdb->prefix . 'guardian_tokens',
            ['guardian_id' => $user_id, 'token' => $new_token],
            ['%d', '%s']
        );
        $response_data['token'] = $new_token;
    }

    return new WP_REST_Response($response_data, 200);
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'custom-registration', '/my-token', array(
        'methods'             => 'GET',
        'callback'            => 'br_get_my_guardian_token',
        'permission_callback' => '__return_true'
    ) );
} );

function br_get_my_guardian_token( $request ) {
    $nonce = $request->get_header( 'X-WP-Nonce' );
    if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) && ! is_user_logged_in() ) {
        return new WP_Error( 'unauthorized', 'Brak autoryzacji.', array( 'status' => 401 ) );
    }

    $user = wp_get_current_user();
    
    if ( ! in_array( 'rodzic', (array) $user->roles ) ) {
        return new WP_Error( 'not_a_parent', 'Tylko rodzic posiada token.', array( 'status' => 403 ) );
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'guardian_tokens';
    
    $token = $wpdb->get_var( $wpdb->prepare(
        "SELECT token FROM $table_name WHERE guardian_id = %d LIMIT 1",
        $user->ID
    ) );

    if ( $token ) {
        return rest_ensure_response( array( 'success' => true, 'token' => $token ) );
    } else {
        return new WP_Error( 'no_token', 'Nie wygenerowano tokenu.', array( 'status' => 404 ) );
    }
}