<?php
/*
Plugin Name: WapuuGo - Adventure Map
Description: Główna wtyczka do obsługi Mapy.
Version: 1.0
*/

if ( !defined( 'ABSPATH' ) ) exit;

// Mapa jako shortcode (E2-1)
add_shortcode('wg_adventure_map', function() {
    
    if ( !is_user_logged_in() ) {
        return '<p>Musisz być zalogowany, aby widzieć mapę!</p>';
    }

    // MOCKOWANIE
    $mock_progress = [
        'current_level' => 3,
        'unlocked_lessons' => [1, 2, 3], 
        'total_xp' => 450
    ];

    wp_enqueue_script('wg-map-js', plugin_dir_url(__FILE__) . 'js/map.js', [], '1.0', true);

    // Przekazanie danych (teraz mockowanych) do JS
    wp_localize_script('wg-map-js', 'WgAdventureData', [
        'progress' => $mock_progress
    ]);

    return '<div id="wg-map-container" style="min-height: 300px; border: 2px dashed #ccc; padding: 20px;">
        MAPA
    </div>';
});