<?php
/**
 * This file contains the wp-content WordPress Stream Wrapper which implements
 * the "local://" scheme. This is a simple and complete stream wrapper
 * implementation for testing, reference, and extension purposes. Other stream
 * wrappers that manipulate files on the local filesystem can quickly extend
 * this class to suit more specific needs. The WP Test [test://] stream
 * is an excellent example of this use case.
 *
 * @package Stream Wrappers
 */

/**
 * WordPress stream wrapper: test
 *
 * A simple and complete stream wrapper implementation for testing and
 * reference purposes. URIs such as "test://example.txt" are expanded
 * to a normal filesystem path where the test files are contained, for
 * example "wp-content/plugins/wp-stream-wrappers-test/files". After the URIs
 * are expanded, PHP filesystem funcations are invoked with the expanded path.
 *
 * @package    Stream Wrappers
 * @author     Jon Stacey <jon@jonsview.com>
 * @version    Release: 1.0.0
 * @link       http://www.php.net/manual/en/class.streamwrapper.php
 * @see        WP_Stream_Wrapper_Interface
 * @since      Class available since Release 1.0.0
 */
abstract class WP_Local_Stream_Wrapper implements WP_Stream_Wrapper_Interface {
	/**
	 * Stream context resource
	 *
	 * Note: This property must be public so PHP can populate it with
	 * the actual context resource. 
	 *
	 * @see stream_context_get_options()
	 * @var resource
	 * @access public
	 */
	public $context;
	
	/**
	 * Generic resource handle
	 *
	 * This handle is needed so that the currently instantiated object
	 * knows and has access to the actual resource.
	 *
	 * @var resource
	 * @access public
	 */
	public $handle = null;
	
	/**
	 * Instance URI (stream)
	 *
	 * A stream is referenced as "scheme://target".
	 *
	 * @var string
	 * @access protected
	 */
	protected $uri;
	
	/**
	 * Retrieves the path that the wrapper is responsible for
	 *
	 * All wrappers that extend WP_Local_Stream_Wrapper must implement
	 * this method.
	 *
	 * @package Stream Wrappers
	 * @since 1.0.0
	 */
	abstract function get_local_path();
	
	/**
	 * Sets the URI instance variable
	 *
	 * Base implementation of set_uri().
	 *
	 * @package Stream Wrappers
	 * @since 1.0.0
	 */
	function set_uri($uri) {
		$this->uri = $uri;
	}
	
	/**
	 * Returns the instance URI
	 *
	 * Base implementation of get_uri().
	 *
	 * @package Stream Wrappers
	 * @since 1.0.0
	 */
	function get_uri() {
		return $this->uri;
	}
}

?>