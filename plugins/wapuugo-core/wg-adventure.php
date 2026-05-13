<?php
/*
Plugin Name: WapuuGo - Adventure Map
Description: Główna wtyczka do obsługi Mapy.
Version: 1.0
*/

if ( !defined( 'ABSPATH' ) ) exit;

require_once plugin_dir_path( __FILE__ ) . 'includes/api.php';

add_shortcode( 'wg_adventure_map', function () {

    if ( !is_user_logged_in() ) {
        return '<p>Musisz być zalogowany, aby widzieć mapę!</p>';
    }

    wp_enqueue_script( 'wg-map-js', plugin_dir_url( __FILE__ ) . 'js/map.js', [], '1.0', true );

    wp_localize_script( 'wg-map-js', 'WgAdventureData', [
        'apiUrl'   => rest_url( 'wapuugo/v1/map-progress' ),
        'nonce'    => wp_create_nonce( 'wp_rest' ),
        'courseId' => 348, // TODO: docelowo dynamicznie z parametru strony lub opcji wtyczki
    ]);

    return '<div id="wg-map-container" style="min-height: 300px; border: 2px dashed #ccc; padding: 20px;">
        Ładowanie mapy...
    </div>';
});