<?php
/*
Plugin Name: Stream Wrapper API
Plugin URI: TBD
Description: Stream Wrapper API provides the core foundation upon which wrappers for WordPress are built.
Version: 1.0
Author: Jon Stacey
Author URI: http://jonsview.com
*/

// Coding standards: http://codex.wordpress.org/WordPress_Coding_Standards
// Inline documentation standards: http://codex.wordpress.org/Inline_Documentation

define('STREAM_WRAPPER_API_VERSION', '1.0');

/** 
 * This file holds the stream wrapper registry
 */
//require_once ABSPATH.'/wp-config.php';

/**
 * Initializes the Stream Wrapper API
 * 
 * Prepares the Stream Wrapper API for use.
 *
 * @package Stream Wrappers
 * @since 1.0
 */
function stream_wrapper_api_init()  {
	// TODO: Initialize Streams API
}

// Register Stream Wrapper API Initialization function with WordPress
add_action('init', 'stream_wrapper_api_init', 0, 0);

?>