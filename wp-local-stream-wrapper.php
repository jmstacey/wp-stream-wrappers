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
		'description' => 'WP Local Stream Wrapper provides a simple example of leveraging the WP_Local_Stream_Wrapper_Base class to create simple wrappers capable of manipulating local files.'
	);

	// Register this wrapper
	WP_Stream_Wrapper_Registry::register_wrapper($scheme, $wrapper_metadata);
}

// Register test stream wrapper
add_action('register_stream_wrapper', 'wp_local_stream_wrapper_register', 1, 0);

/**
 * WordPress local (local://) stream wrapper class
 *
 * A simple stream wrapper that implements access to files on the local
 * filesystem. Additionally, this wrapper provides a simple example of
 * how to build a custom wrapper on top of the
 * WP_Local_Stream_Wrapper_Base class.
 *
 * @package    Stream Wrappers
 * @author     Jon Stacey <jon@jonsview.com>
 * @version    Release: 1.0.0
 * @link       
 * @see        WP_Local_Stream_Wrapper_Base
 * @since      Class available since Release 1.0.0
 */
class WP_Local_Stream_Wrapper extends WP_Local_Stream_Wrapper_Base {
	/**
	 * Implements WP_Stream_Wrapper_Base::get_wrapper_path()
	 * 
	 * Retrieves the path that this wrapper is responsible for. This allows
	 * with minimal development effor the addition and customization of
	 * wrappers specific to different local filesystem locations.
	 *
	 * @return string
	 *   the path that this wrapper is responsible for.
	 *
	 * @see WP_Local_Stream_Wrapper_Base::get_wrapper_path()
	 * @since 1.0.0
	 */
	public function get_wrapper_path() {
		/**
		 * TODO: consider making this path configurable through the
		 * administration area.
		 */
		return WP_CONTENT_DIR;
	}
	
	/**
	 * Implements WP_Stream_Wrapper_Interface::get_web_accessible_url()
	 *
	 * Returns a URL that can be accessed from a browser. For example,
	 * the web URL of the internal URI "local://example.txt" might be
	 * "http://www.example.com/wp-content/example.txt".
	 *
	 * @return string
	 *   the web accessible URL for the resource.
	 *
	 * @see WP_Local_Stream_Wrapper_Interface::get_web_accessible_url()
	 * @since 1.0.0
	 */
	public function get_web_accessible_url() {
		$path = str_replace('\\', '/', WP_Stream::uri_target($this->uri));
		
		return content_url().'/'.self::get_wrapper_path().'/'.$path;
	}
}

?>