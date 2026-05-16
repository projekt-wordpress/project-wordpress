<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( is_user_logged_in() ) {
    $current_user = wp_get_current_user();
    $user_display = ! empty( $current_user->display_name ) ? $current_user->display_name : $current_user->user_login;
    ?>
    <div class="br-registration-logged-in-notice" style="padding: 10px 0; font-size: 1em;">
        Jesteś zalogowany jako <?php echo esc_html( $user_display ); ?>. 
        <a href="<?php echo esc_url( wp_logout_url( home_url( $_SERVER['REQUEST_URI'] ) ) ); ?>" style="text-decoration: underline; color: #3858E9;">Wylogować</a>?
    </div>
    <?php
    return;
}

$form_role = esc_attr( $attributes['role'] ?? 'rodzic' );
?>
<div class="br-react-registration-root" data-role="<?php echo $form_role; ?>">

</div>