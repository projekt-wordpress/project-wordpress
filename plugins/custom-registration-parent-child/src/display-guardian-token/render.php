<?php
if ( ! defined( 'ABSPATH' ) ) exit;

$nonce = wp_create_nonce( 'wp_rest' );
$wrapper_attributes = get_block_wrapper_attributes( [
    'class' => 'br-token-wrapper',
    'data-nonce' => $nonce
] );
?>

<div <?php echo $wrapper_attributes; ?>>
    </div>