<?php
/*
Plugin Name: WP Stream Wrappers
Plugin URI: TBD
Description: WP Stream Wrappers provides the core foundation upon which wrappers for WordPress are built.
Version: 1.0
Author: Jon Stacey
Author URI: http://jonsview.com
*/

// Coding standards: http://codex.wordpress.org/WordPress_Coding_Standards
// Inline documentation standards: http://codex.wordpress.org/Inline_Documentation

define('WP_STREAM_WRAPPERS_VERSION', '1.0.0');

/** 
 * This file holds the stream wrapper registry
 */
require_once WP_PLUGIN_DIR.'/wp-stream-wrappers/wp-stream-wrapper-registry.php';

/**
 * Initializes WP Stream Wrappers
 * 
 * Prepares the WordPress Stream Wrapper registry and interfaces for use.
 *
 * @package Stream Wrappers
 * @since 1.0.0
 */
function wp_stream_wrappers_init() {
	// @todo: Initialize WP Stream Wrappers
}

// Register Stream Wrapper API Initialization function with WordPress
// We may need to evenaully initialize even before WordPress.
// It depends how far down the rabbit hole we wish to travel.
add_action('init', 'wp_stream_wrappers_init', 0, 0);

?>