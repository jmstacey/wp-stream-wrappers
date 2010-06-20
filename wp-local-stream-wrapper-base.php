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
	 * Implements WP_Stream_Wrapper_Interface::dir_closedir()
	 *
	 * This function is called in response to PHP's closedir().
	 *
	 * @return bool
	 *   true on success.
	 *
	 * @package Stream Wrappers
	 * @see WP_Stream_Wrapper_Interface::dir_closedir()
	 * @see closedir()
	 * @link http://php.net/manual/en/streamwrapper.dir-closedir.php
	 * @since 1.0.0
	 */
	public function dir_closedir() {
		return closedir($this->handle);
	}
	
	/**
	 * Implements WP_Stream_Wrapper_Interface::dir_opendir()
	 *
	 * This function is called in response to PHP's opendir().
	 *
	 * @param  string @uri
	 *   the URI passed to opendir().
	 * @param  unkown $options
	 *   whether or not to enforce safe mode.
	 * @return bool
	 *   true on success.
	 *
	 * @package Stream Wrappers
	 * @see WP_Stream_Wrapper_Interface::dir_opendir()
	 * @see opendir()
	 * @link http://php.net/manual/en/streamwrapper.dir-opendir.php
	 * @since 1.0.0
	 */
	public function dir_opendir($uri, $options) {
		$this->uri = $uri;
		$this->handle = opendir($this->get_local_path());
		
		return (bool)$this->handle;
	}
	
	/**
	 * Implements WP_Stream_Wrapper_Interface::dir_readdir()
	 *
	 * This function is called in response to PHP's readdir().
	 *
	 * @return mixed
	 *   the next filename (string), or false (bool) if there are no more
	 * files in the directory.
	 *
	 * @package Stream Wrappers
	 * @see WP_Stream_Wrapper_Interface::dir_readdir()
	 * @see readdir()
	 * @link http://php.net/manual/en/streamwrapper.dir-readdir.php
	 * @since 1.0.0
	 */
	public function dir_readdir() {
		return readdir($this->handle);
	}
	
	/**
	 * Implements WP_Stream_Wrapper_Interface::dir_rewinddir()
	 *
	 * This function is called in response to PHP's rewinddir().
	 *
	 * @return bool
	 *   true on success or false on failure.
	 *
	 * @package Stream Wrappers
	 * @see WP_Stream_Wrapper_Interface::dir_rewinddir()
	 * @see rewinddir()
	 * @link http://php.net/manual/en/streamwrapper.dir-rewinddir.php
	 * @since 1.0.0
	 */
	public function dir_rewinddir() {
		return rewinddir($this->handle);
	}
	
	/**
	 * Implements WP_Stream_Wrapper_Interface::mkdir()
	 *
	 * This function is called in response to PHP's mkdir().
	 *
	 * @param string $uri
	 *   URI of the directory which should be created.
	 * @param int $mode
	 *   the value passed to PHP's mkdir().
	 * @param int $options
	 *   a bitwise mask of values, such as STREAM_MKDIR_RECURSIVE.
	 * @return bool
	 *   true on success or false on failure.
	 *
	 * @package Stream Wrappers
	 * @see WP_Stream_Wrapper_Interface::dir_mkdir()
	 * @see mkdir()
	 * @link http://php.net/manual/en/streamwrapper.mkdir.php
	 * @since 1.0.0
	 */
	public function mkdir($uri, $mode, $options) {
		$this->uri = $uri;
		$recursive = (bool)($options & STREAM_MKDIR_RECURSIVE);
		if ($options & STREAM_REPORT_ERRORS) {
			return mkdir($this->get_local_path(), $mode, $recursive);
		}
		else {
			return @mkdir($this->get_local_path(), $mode, $recursive);
		}
	}

	/**
	 * Implements WP_Stream_Wrapper_Interface::rename()
	 *
	 * This function is called in response to PHP's rename().
	 *
	 * @param string $from_uri
	 *   the URI to the current file.
	 * @param string $to_uri
	 *   the URI which $from_uri should be renamed to.
	 * @return bool
	 *   true on success or false on failure.
	 *
	 * @package Stream Wrappers
	 * @see WP_Stream_Wrapper_Interface::rename()
	 * @see rename()
	 * @link http://php.net/manual/en/streamwrapper.rename.php
	 * @since 1.0.0
	 */
	public function rename($from_uri, $to_uri) {
		return rename($this->get_local_path($from_uri), $this->get_local_path($to_uri));
	}
	
	/**
	 * Implements WP_Stream_Wrapper_Interface::rmdir()
	 *
	 * This function is called in response to PHP's rmdir().
	 *
	 * @param string $uri
	 *   the directory which should be removed.
	 * @param string $options
	 *   a bitwise mask of valus, such as STREAM_REPORT_ERRORS.
	 * @return bool
	 *   true on success or false on failure.
	 *
	 * @package Stream Wrappers
	 * @see WP_Stream_Wrapper_Interface::rmdir()
	 * @see rmdir()
	 * @link http://php.net/manual/en/streamwrapper.rmdir.php
	 * @since 1.0.0
	 */
	public function rmdir($uri, $options) {
		$this->uri = $uri;
		if ($options & STREAM_REPORT_ERRORS) {
			return rmdir($this->getLocalPath());
		}
		else {
			return @rmdir($this->getLocalPath());
		}
	}
	
	/**
	 * Implements WP_Stream_Wrapper_Interface::stream_close()
	 *
	 * This function is called in response to PHP's fclose().
	 *
	 * @return none
	 *   No value is returned.
	 *
	 * @package Stream Wrappers
	 * @see WP_Stream_Wrapper_Interface::stream_close()
	 * @see fclose()
	 * @link http://php.net/manual/en/streamwrapper.stream-close.php
	 * @since 1.0.0
	 */
	public function stream_close() {
		return fclose($this->handle);
	}
	
	/**
	 * Implements WP_Stream_Wrapper_Interface::stream_eof()
	 *
	 * This function is called in response to PHP's feof().
	 *
	 * @return bool
	 *   true if the read/write position is at the end of the stream and
	 *   no more data is available to be read, otherwise false.
	 *
	 * @package Stream Wrappers
	 * @see WP_Stream_Wrapper_Interface::stream_eof()
	 * @see feof()
	 * @link http://php.net/manual/en/streamwrapper.stream-eof.php
	 * @since 1.0.0
	 */
	public function stream_close() {
		return feof($this->handle);
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
	 * @see WP_Stream_Wrapper_Interface::stream_open()
	 * @link http://php.net/manual/en/streamwrapper.stream-open.php
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