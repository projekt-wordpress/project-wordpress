<?php

if ( !defined( 'ABSPATH' ) ) exit;

add_action( 'rest_api_init', function () {
    register_rest_route( 'wapuugo/v1', '/map-progress', [
        'methods'             => 'GET',
        'callback'            => 'wg_get_map_progress',
        'permission_callback' => 'wg_is_logged_in',
        'args' => [
            'course_id' => [
                'required'          => true,
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
            ],
        ],
    ]);
});

function wg_is_logged_in(): bool {
    return is_user_logged_in();
}

function wg_get_map_progress( WP_REST_Request $request ): WP_REST_Response {
    $user_id   = get_current_user_id();
    $course_id = (int) $request->get_param( 'course_id' );

    $course = learn_press_get_course( $course_id );
    $user   = learn_press_get_user( $user_id );

    if ( !$course || !$user ) {
        return new WP_Error( 'not_found', 'Kurs nie istnieje', [ 'status' => 404 ] );
    }

    $item_ids        = $course->get_item_ids();
    $lessons         = [];
    $previous_passed = true;

    foreach ( $item_ids as $lesson_id ) {
        $lp_status = $user->get_item_status( $lesson_id, $course_id );

        if ( $lp_status === 'completed' ) {
            $status = 'completed';
        } elseif ( $lp_status === 'in-progress' ) {
            $status = 'in_progress';
        } elseif ( $previous_passed ) {
            $status = 'available';
        } else {
            $status = 'locked';
        }

        $lessons[] = [
            'id'     => $lesson_id,
            'status' => $status,
        ];

        $previous_passed = ( $lp_status === 'completed' );
    }

    return rest_ensure_response( [ 'lessons' => $lessons ] );
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'wapuugo/v1', '/complete-lesson', [
        'methods'             => 'POST',
        'callback'            => 'wg_complete_lesson',
        'permission_callback' => 'wg_is_logged_in',
        'args' => [
            'lesson_id' => [
                'required'          => true,
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
            ],
            'course_id' => [
                'required'          => true,
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
            ],
            'quiz_score' => [
                'required'          => true,
                'type'              => 'integer',
                'sanitize_callback' => 'absint',
            ],
        ],
    ]);
});

function wg_complete_lesson( WP_REST_Request $request ): WP_REST_Response {
    $user_id    = get_current_user_id();
    $lesson_id  = (int) $request->get_param( 'lesson_id' );
    $course_id  = (int) $request->get_param( 'course_id' );
    $quiz_score = (int) $request->get_param( 'quiz_score' );

    $min_score = 50;

    $course = learn_press_get_course( $course_id );
    $user   = learn_press_get_user( $user_id );

    if ( !$course || !$user ) {
        return new WP_Error( 'not_found', 'Kurs nie istnieje', [ 'status' => 404 ] );
    }

    $item_ids = $course->get_item_ids();
    if ( !in_array( (string) $lesson_id, $item_ids ) ) {
        return new WP_Error( 'invalid_lesson', 'Lekcja nie należy do tego kursu', [ 'status' => 400 ] );
    }

    if ( !$user->has_enrolled_course( $course_id ) ) {
        return new WP_Error( 'not_enrolled', 'Użytkownik nie jest zapisany na kurs', [ 'status' => 403 ] );
    }

    $current_status = $user->get_item_status( $lesson_id, $course_id );
    if ( $current_status === 'completed' ) {
        return rest_ensure_response( [
            'passed'        => true,
            'xp_awarded'    => false,
            'next_unlocked' => false,
            'message'       => 'Lekcja już zaliczona',
        ]);
    }

    if ( $quiz_score < $min_score ) {
        return rest_ensure_response( [
            'passed'        => false,
            'xp_awarded'    => false,
            'next_unlocked' => false,
            'message'       => 'Wynik za niski. Spróbuj ponownie!',
        ]);
    }

    $result = $user->complete_lesson( $lesson_id, $course_id );

    if ( is_wp_error( $result ) ) {
        return new WP_Error( 'complete_failed', 'Nie udało się zapisać postępu', [ 'status' => 500 ] );
    }

    $next_unlocked = false;
    foreach ( $item_ids as $index => $id ) {
        if ( (string) $lesson_id === $id && isset( $item_ids[ $index + 1 ] ) ) {
            $next_unlocked = true;
            break;
        }
    }

    return rest_ensure_response( [
        'passed'        => true,
        'xp_awarded'    => true,
        'next_unlocked' => $next_unlocked,
        'message'       => 'Lekcja zaliczona!',
    ]);
}