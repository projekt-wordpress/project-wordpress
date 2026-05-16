<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Init run after activation of plugin - creates wp_user_guardian_child table if not exists
function br_plugin_activation() {
    global $wpdb;
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    $charset_collate = $wpdb->get_charset_collate();

	// 1. Table of guardian tokens
    $table_tokens = $wpdb->prefix . 'guardian_tokens';
    $sql_tokens = "CREATE TABLE $table_tokens (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        guardian_id bigint(20) NOT NULL,
        token varchar(50) NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id),
        UNIQUE KEY token (token)
    ) $charset_collate;";
    dbDelta( $sql_tokens );

    // 2. Table of relations between guardians and children
    $table_relations = $wpdb->prefix . 'user_guardian_child';
    $sql_relations = "CREATE TABLE $table_relations (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        guardian_id bigint(20) NOT NULL,
        child_id bigint(20) NOT NULL,
        token_id bigint(20) NOT NULL, 
        PRIMARY KEY  (id)
    ) $charset_collate;";
    dbDelta( $sql_relations );

	// Add custom roles 'rodzic' and 'dziecko' if they don't exist
    add_role( 'rodzic', 'Rodzic', array( 'read' => true ) );
    add_role( 'dziecko', 'Dziecko', array( 'read' => true ) );
}