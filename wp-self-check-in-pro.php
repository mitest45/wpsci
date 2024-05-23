<?php

/**
 * WP Self Check-In Pro
 *
 * @package     WP Self Check-In Pro
 * @author      Rocco
 * @copyright   Rocco
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: WP Self Check-In Pro
 * Plugin URI:  #
 * Description: Manage your hotel bookings and guests information seamlessly with WP Self Check-In Pro plugin.
 * Version:     1.0.3
 * Author:      Rocco
 * Author URI:  #
 * Text Domain: wp-self-check-in
 * Domain Path: /languages
 * License:     GPL v2 or later
 * Tested up to: 6.5.3
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

register_activation_hook(__FILE__, 'wpsci_plugin_activation');

function wpsci_plugin_activation() {
    
    require_once(plugin_dir_path( __FILE__ ). 'activation.php');
}


require_once(plugin_dir_path( __FILE__ ). 'functions.php');