<?php
/**
 * Plugin Name:       Rejestracja Rodzic-Dziecko
 * Description:       System rejestracji dla rodziców i dzieci, umożliwiający tworzenie kont dla dzieci powiązanych z kontami rodziców.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Bartłomiej Handziak, Kamil Janik
 * License:           GPL-2.0-or-later
 * Text Domain:       custom-registration-parent-child
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'BR_REG_PATH', plugin_dir_path( __FILE__ ) );

require_once BR_REG_PATH . 'includes/database.php';
require_once BR_REG_PATH . 'includes/assets.php';
require_once BR_REG_PATH . 'includes/rest-api.php';

register_activation_hook( __FILE__, 'br_plugin_activation' );