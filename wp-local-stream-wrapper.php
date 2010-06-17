<?php
/**
 * This file contains the Local WordPress Stream Wrapper which implements
 * the "local://" scheme. This is a simple and complete stream wrapper
 * implementation for testing, reference, and extension purposes. Other stream
 * wrappers that manipulate files on the local filesystem can quickly extend
 * this class to suit more specific needs. The WP Test [test://] stream
 * is an excellent example of this use case.
 *
 * @package Stream Wrappers
 */

define('WP_LOCAL_STREAM_WRAPPER_VERSION', '1.0.0');

/**
 * Registers the WP Local Stream Wrapper
 * 
 * Prepares the WordPress Local Stream Wrapper for use
 *
 * @package Stream Wrappers
 * @since 1.0.0
 */
function wp_local_stream_wrapper_register() {
	$scheme = 'local'; // Wrapper scheme
	
	// Wrapper registration metadata
	$wrapper_metadata = array(
		'name' => 'WP Local Stream Wrapper',
		'class' => 'WP_Local_Stream_Wrapper',
		'description' => 'WP Local Stream Wrapper provides a ready to use base for interacting with files on the local filesystem.'
	);

	// Register this wrapper
	WP_Stream_Wrapper_Registry::register_wrapper($scheme, $wrapper_metadata);
}

// Register test stream wrapper
add_action('register_stream_wrapper', 'wp_local_stream_wrapper_register', 1, 0);

?>