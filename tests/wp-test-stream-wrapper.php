<?php
/**
 * This file contains the Test WordPress Stream Wrapper.
 *
 * This wrapper is used only for testing purposes.
 *
 * @package Stream Wrappers
 */

/**
 * Registers the WP Test Stream Wrapper
 * 
 * Helper function that prepares the WordPress Test Stream Wrapper for use.
 *
 * @package Stream Wrappers
 * @since 1.0.0
 */
function wp_test_stream_wrapper_register() {
	$scheme = 'test'; // Wrapper scheme
	
	// Wrapper registration metadata
	$wrapper_metadata = array(
		'name' => 'WP Test Stream Wrapper',
		'class' => 'WP_Test_Stream_Wrapper',
		'description' => 'WP Test Stream Wrapper provides a simple extension of the WP_Local_Stream_Wrapper_Base class.'
	);

	// Register this wrapper
	WP_Stream_Wrapper_Registry::register_wrapper($scheme, $wrapper_metadata);
}

/**
 * WordPress test (test://) stream wrapper class
 *
 * A simple stream wrapper used to test the stream wrappers plugin suite.
 *
 * @package    Stream Wrappers
 * @author     Jon Stacey <jon@jonsview.com>
 * @version    Release: 1.0.0
 * @link       
 * @see        WP_Local_Stream_Wrapper_Base
 * @since      Class available since Release 1.0.0
 */
class WP_Test_Stream_Wrapper extends WP_Local_Stream_Wrapper_Base {
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
		// @todo change this to the test directory
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
		$path = str_replace('\\', '/', WP_Stream::target($this->uri));
		
		return content_url().'/'.self::get_wrapper_path().'/'.$path;
	}
}

?>