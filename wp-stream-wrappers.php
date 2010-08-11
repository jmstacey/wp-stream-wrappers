<?php
/**
 * WP Stream Wrappers Plugin
 *
 * WP Stream wrappers provides a foundation for next generation WordPress
 * file handling capabilities.
 *
 * @package     Stream Wrappers
 * @author      Jon Stacey <jon@jonsview.com>
 * @copyright   2010 Jon Stacey
 * @license     http://wordpress.org/about/gpl/
 * @link        http://github.com/jmstacey/wp-stream-wrappers
 * @version     1.0.0
 * @since       1.0.0
 */

/*
Plugin Name: WP Stream Wrappers
Plugin URI: http://github.com/jmstacey/wp-stream-wrappers
Description: Providing a foundation for next generation WordPress file handling capabilities.
Version: 1.0.0
Author: Jon Stacey
Author URI: http://jonsview.com
*/

define('WP_STREAM_WRAPPERS_VERSION', '1.0.0');

/**
 * Initialization takes place in the following order:
 *
 * Step 1: Initialize stream wrapper registry
 * Step 2: Load WP_Stream utilities class
 * Step 3: Load WP File API Helpers
 * Step 4: Load the WP Stream Wrapper Interface
 * Step 5: Load the base local registry wrapper implementation
 * Step 6: Load the WP Local Stream wrapper [implements local://]
 */

/** 
 * This file holds the stream wrapper registry
 */
require_once('wp-stream-wrapper-registry.php');

/** 
 * This file contains the WP_Class utilities class
 */
require_once('wp-stream-class.php');

/** 
 * This file holds the WordPress File API Helper functions
 */
require_once('wp-file-api-helpers.php');

/** 
 * This file contains the WP Stream Wrapper interface that WordPress
 * stream wrappers use
 */
require_once('wp-stream-wrapper-interface.php');

/** 
 * This file contains the base WP Local Stream wrapper class
 */
require_once('wp-local-stream-wrapper-base.php');

/** 
 * This file contains the WP Local Stream wrapper implementation
 */
require_once('wp-local-stream-wrapper/wp-local-stream-wrapper.php');

/**
 * Initializes WP Stream Wrappers
 * 
 * Prepares the WordPress Stream Wrapper registry and interfaces for use.
 *
 * @package Stream Wrappers
 * @since 1.0.0
 */
function wp_stream_wrappers_init() {
	wp_stream_wrapper_registry_init();
}

/**
 * Register Stream Wrapper initialization function with WordPress.
 */
add_action('init', 'wp_stream_wrappers_init', 0, 0);

?>