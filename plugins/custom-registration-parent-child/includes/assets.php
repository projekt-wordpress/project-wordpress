<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Init Gutenberg block
function create_block_custom_registration_parent_child_block_init() {
	wp_register_block_types_from_metadata_collection( 
        BR_REG_PATH . 'build', 
        BR_REG_PATH . 'build/blocks-manifest.php' 
    );
}
add_action( 'init', 'create_block_custom_registration_parent_child_block_init' );

// Register styles
function br_enqueue_global_styles() {
    wp_enqueue_style(
        'br-registration-block-style',
        plugins_url( 'build/custom-registration-parent-child/style-index.css', dirname(__DIR__) . '/custom-registration-parent-child.php' )
    );
}
add_action('wp_enqueue_scripts', 'br_enqueue_global_styles');