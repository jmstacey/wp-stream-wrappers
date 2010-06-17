<?php
/**
 * This file contains the Local WordPress Stream Wrapper Base class.
 * this is a simple implementation of the WP_Stream_Wrapper_Interface
 * class that can be used by stream wrappers that manipulate files on
 * the local filesystem. A custom wrapper such as "local://" can be created
 * with just a few lines of code.
 *
 * @package Stream Wrappers
 */

/**
 * WordPress Local Stream Wrapper Base
 *
 * A simple implementation of WP_Stream_Wrapper_Interface and ready to use
 * skeleton for manipulating files on the local file system. New custom
 * tailored stream wrappers that deal with local files can be created with
 * just a few lines of code.
 *
 * Look at the WP Local Stream Wrapper (WP_Local_Stream_Wrapper) for an
 * example of how to use this base class.
 *
 * @package    Stream Wrappers
 * @author     Jon Stacey <jon@jonsview.com>
 * @version    Release: 1.0.0
 * @link       http://www.php.net/manual/en/class.streamwrapper.php
 * @see        WP_Stream_Wrapper_Interface
 * @since      Class available since Release 1.0.0
 */
abstract class WP_Local_Stream_Wrapper_Base implements WP_Stream_Wrapper_Interface {
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
	 * All wrappers that extend WP_Local_Stream_Wrapper_Base must implement
	 * this method.
	 *
	 * @package Stream Wrappers
	 * @since 1.0.0
	 */
	abstract function get_wrapper_path();
	
	/**
	 * Returns the local filesystem path
	 *
	 * @param string $uri
	 *   optional URI to be supplied when doing a move or rename.
	 *
	 * @package Stream Wrappers
	 * @see WP_Stream_Wrapper_Interface::chmod()
	 * @since 1.0.0
	 */
	protected function get_local_path($uri = null) {
		if (!isset($uri)) {
			$uri = $this->uri;
		}
		
		return $this->get_wrapper_path().'/'.WP_Stream::uri_target($uri);
	}
	
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
	
	/**
	 * Implements WP_Stream_Wrapper_Interface::chmod()
	 *
	 * @package Stream Wrappers
	 * @see WP_Stream_Wrapper_Interface::chmod()
	 * @since 1.0.0
	 */
	function chmod($mode) {
		return @chmod($this->realpath(), $mode);
	}
	
	/**
	 * Implementation of WP_Stream_Wrapper_Interface::realpath()
	 *
	 * @package Stream Wrappers
	 * @see WP_Stream_Wrapper_Interface::realpath()
	 * @since 1.0.0
	 */
	function realpath() {
		return @realpath(@this->get_wrapper_path().'/'.WP_Stream::uri_target($uri));
	}

	/**
	 * Implements WP_Stream_Wrapper_Interface::stream_open()
	 *
	 * Adds support for fopen(), file_get_contents(), file_put_contents(),
	 * etc.
	 *
	 * @param string $uri
	 *   the path to the file to open.
	 * @param string $mode
	 *   the file mode (e.g. "r" or "wb" etc.).
	 * @param bitmask $options
	 *   @todo this needs to be reworked to be WP specific
	 *   a bitmask of STREAM_USER_PATH AND STREAM_REPORT_ERRORS.
	 * @param reference &$opened_path
	 *   path actually opened.
	 * @return bool
	 *   true if file was opened successfully.
	 *
	 * @package Stream Wrappers
	 * @see http://php.net/manual/en/streamwrapper.stream-open.php
	 * @since 1.0.0
	 */
	public function stream_open($uri, $mode, $options, &$opened_path) {
		$this->uri = $uri;
		$path = $this->getLocalPath();
		$this->handle = ($options & STREAM_REPORT_ERRORS) ? fopen($path, $mode) : @fopen($path, $mode);

		if ((bool)$this->handle && $options & STREAM_USE_PATH) {
			$opened_url = $path;
		}

		return (bool)$this->handle;
	}

}

?>