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
	 * Initializes the abstract implementation of WP_Local_Stream_Wrapper_Base
	 *
	 * @access public
	 * @see WP_Local_Stream_Wrapper
	 * @since Method available since Release 1.0.0
	 */
	public function __construct() {
		// The local stream wrapper needs no initialization at this point.
	}
	
	
	/**
	 * Retrieves the path that the wrapper is responsible for
	 *
	 * All wrappers that extend WP_Local_Stream_Wrapper_Base must implement
	 * this method.
	 *
	 * This allows, with minimal development effort, the addition and
	 * customization of wrappers specific to different local filesystem
	 * paths.
	 *
	 * @return string
	 *   the path that this wrapper is responsible for.
	 *
	 * @since 1.0.0
	 */
	abstract function get_wrapper_path();
	
	/**
	 * Returns the local filesystem path
	 *
	 * @param string $uri
	 *   optional URI to be supplied when doing a move or rename.
	 *
	 * @see WP_Stream_Wrapper_Interface::chmod()
	 * @since 1.0.0
	 */
	protected function get_local_path($uri = null) {
		if (!isset($uri)) {
			$uri = $this->uri;
		}
		
		return $this->get_wrapper_path().'/'.WP_Stream::target($uri);
	}
	
	/**
	 * Sets the URI instance variable
	 *
	 * Base implementation of set_uri().
	 *
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
	 * @since 1.0.0
	 */
	function get_uri() {
		return $this->uri;
	}
	
	/**
	 * Implements WP_Stream_Wrapper_Interface::chmod()
	 *
	 * @see WP_Stream_Wrapper_Interface::chmod()
	 * @since 1.0.0
	 */
	function chmod($mode) {
		return @chmod($this->realpath(), $mode);
	}
	
	/**
	 * Implementation of WP_Stream_Wrapper_Interface::realpath()
	 *
	 * @see WP_Stream_Wrapper_Interface::realpath()
	 * @since 1.0.0
	 */
	function realpath() {
		return @realpath($this->get_wrapper_path().'/'.WP_Stream::target($uri));
	}
	
	/**
	 * Implements WP_Stream_Wrapper_Interface::dir_closedir()
	 *
	 * This function is called in response to PHP's closedir().
	 *
	 * @return bool
	 *   true on success.
	 *
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
	 *
	 * @return bool
	 *   true on success.
	 *
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
	 *
	 * @return bool
	 *   true on success or false on failure.
	 *
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
	 *
	 * @return bool
	 *   true on success or false on failure.
	 *
	 * @see WP_Stream_Wrapper_Interface::rename()
	 * @see rename()
	 * @link http://php.net/manual/en/streamwrapper.rename.php
	 * @since 1.0.0
	 */
	public function rename($from_uri, $to_uri) {
		return rename($this->get_local_path($from_uri), $this->get_local_path($to_uri));
	}
	
	/**
	 * Implements WP_Stream_Wrapper_Interface::dirname()
	 *
	 * This function is typically invoked through wp_dirname().
	 *
	 * @param string $uri
	 *   the URI or path.
	 *
	 * @return mixed
	 *   the new temporary filename, or false on failure.
	 *
	 * @see WP_Stream_Wrapper_Interface::dirname()
	 * @see wp_dirname()
	 * @since 1.0.0
	 */
	public function dirname($uri) {
		list($scheme, $target) = explode('://', $uri, 2);
		$target  = WP_Stream::target($uri);
		$dirname = dirname($target);
		
		if ($dirname == '.') {
			$dirname = '';
		}
		
		return $scheme . '://' . $dirname;
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
	 *
	 * @return bool
	 *   true on success or false on failure.
	 *
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
	 * Implements WP_Stream_Wrapper_Interface::stream_cast()
	 *
	 * This function is called in response to PHP's stream_select().
	 *
	 * @param int $cast_as
	 *   STREAM_CAST_FOR_SELECT or STREAM_CAST_AS_STREAM
	 *
	 * @return mixed
	 *   the underlying stream resource used by the wrapper, or false.
	 *
	 * @see WP_Stream_Wrapper_Interface::stream_cast()
	 * @see stream_select()
	 * @link http://php.net/manual/en/streamwrapper.stream-cast.php
	 * @since 1.0.0
	 */
	public function stream_cast($cast_as) {
		return stream_select($cast_as);
	}
	
	/**
	 * Implements WP_Stream_Wrapper_Interface::stream_close()
	 *
	 * This function is called in response to PHP's fclose().
	 *
	 * @return none
	 *   No value is returned.
	 *
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
	 * @see WP_Stream_Wrapper_Interface::stream_eof()
	 * @see feof()
	 * @link http://php.net/manual/en/streamwrapper.stream-eof.php
	 * @since 1.0.0
	 */
	public function stream_eof() {
		return feof($this->handle);
	}
	
	/**
	 * Implements WP_Stream_Wrapper_Interface::stream_flush()
	 *
	 * This function is called in response to PHP's fflush().
	 *
	 * @return bool
	 *   true if the cached data was successfully stored (or there was no
	 *   data to store), or false if the data could not be stored.
	 *
	 * @see WP_Stream_Wrapper_Interface::stream_flush()
	 * @see fflush()
	 * @link http://php.net/manual/en/streamwrapper.stream-flush.php
	 * @since 1.0.0
	 */
	public function stream_flush() {
		return fflush($this->handle);
	}	
	
	/**
	 * Implements WP_Stream_Wrapper_Interface::stream_lock()
	 *
	 * This function is called in response to PHP's flock().
	 *
	 * @param mode $operation
	 *   one of the following:
	 *   - LOCK_SH to acquire a shared lock (reader)
	 *   - LOCK_EX to acquire an exclusive lock (writer)
	 *   - LOCK_UN to release a lock (shared or exclusive)
	 *   - LOCK_NB if you don't want flock() to block while locking
	 *
	 * @return bool
	 *   true on success or false on failure.
	 *
	 * @see WP_Stream_Wrapper_Interface::stream_lock()
	 * @see flock()
	 * @link http://php.net/manual/en/streamwrapper.stream-lock.php
	 * @since 1.0.0
	 */
	public function stream_lock($operation) {
		return flock($this->handle, $operation);
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
	 *
	 * @return bool
	 *   true if file was opened successfully.
	 *
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
	
	/**
	 * Implements WP_Stream_Wrapper_Interface::stream_read()
	 *
	 * This function is called in response to PHP's fread() and fgets().
	 *
	 * @param int $count
	 *   how many bytes of data from the current position should be returned.
	 *
	 * @return mixed
	 *   If there are less than $count bytes available, return as many as are
	 *   available. If no more data is available, return either false or an
	 *   empty string.
	 *
	 * @see WP_Stream_Wrapper_Interface::stream_read()
	 * @see fread() and fgets()
	 * @link http://php.net/manual/en/streamwrapper.stream-read.php
	 * @since 1.0.0
	 */
	public function stream_read($count) {
		return fread($this->handle, $count);
	}
	
	/**
	 * Implements WP_Stream_Wrapper_Interface::stream_seek()
	 *
	 * This function is called in response to PHP's fseek().
	 *
	 * @param int $offset
	 *   the stream offset to seek to.
	 * @param int $whence = SEEK_SET
	 *   possible valuse include:
	 *   - SEEK_SET to set position equal to $offset bytes
	 *   - SEEK_CUR to set position to current location plus $offset
	 *   - SEEK_END to set position to end-of-file plus $offset
	 *
	 * @return bool
	 *   true if the position was updated, false otherwise.
	 *
	 * @see WP_Stream_Wrapper_Interface::stream_seek()
	 * @see fseek()
	 * @link http://php.net/manual/en/streamwrapper.stream-seek.php
	 * @since 1.0.0
	 */
	public function stream_seek($offset, $whence) {
		return fseek($this->handle, $offset, $whence);
	}
	
	/**
	 * Implements WP_Stream_Wrapper_Interface::stream_set_option()
	 *
	 * WARNING: The WP Local Stream Wrapper Base class does not
	 * implement this method. It is here simply as a placeholder.
	 *
	 * This function is called to set options on the stream.
	 *
	 * @param int $option
	 * @param int $arg1
	 * @param int $arg2
	 *
	 * @return bool
	 *   false is always returned. This method is not implemened in the stream
	 *   wrappers plugin as of version 1.0.0.
	 *
	 * @see WP_Stream_Wrapper_Interface::stream_set_option()
	 * @see 
	 * @link http://php.net/manual/en/streamwrapper.stream-set-option.php
	 * @since 1.0.0
	 */
	public function stream_set_option($option, $arg1, $arg2) {
		return false; // This method is not implemented.
	}
	
	/**
	 * Implements WP_Stream_Wrapper_Interface::stream_stat()
	 *
	 * This function is called in response to PHP's fstat().
	 *
	 * @return mixed
	 *   see PHP's stat() documentation
	 *
	 * @see WP_Stream_Wrapper_Interface::stream_stat()
	 * @see stat()
	 * @link http://www.php.net/manual/en/function.stat.php
	 * @since 1.0.0
	 */
	public function stream_stat() {
		return fstat($this->handle);
	}
	
	/**
	 * Implements WP_Stream_Wrapper_Interface::stream_tell()
	 *
	 * This function is called in response to PHP's ftell().
	 *
	 * @return int
	 *   the current position of the stream.
	 *
	 * @see WP_Stream_Wrapper_Interface::stream_tell()
	 * @see ftell()
	 * @link http://php.net/manual/en/streamwrapper.stream-tell.php
	 * @since 1.0.0
	 */
	public function stream_tell() {
		return ftell($this->handle);
	}
	
	/**
	 * Implements WP_Stream_Wrapper_Interface::stream_write()
	 *
	 * This function is called in response to PHP's fwrite().
	 *
	 * @param string $data
	 *   the data to store in the underlying stream.
	 *
	 * @return int
	 *   the number of bytes that were successfully stored, or 0
	 *   if none could be stored.
	 *
	 * @see WP_Stream_Wrapper_Interface::stream_write()
	 * @see fwrite()
	 * @link http://php.net/manual/en/streamwrapper.stream-write.php
	 * @since 1.0.0
	 */
	public function stream_write($data) {
		return fwrite($this->handle, $data);
	}
	
	/**
	 * Implements WP_Stream_Wrapper_Interface::unlink()
	 *
	 * This function is called in response to PHP's unlink().
	 *
	 * @param string $uri
	 *   the file which should be deleted.
	 *
	 * @return bool
	 *   true on success or false on failure.
	 *
	 * @see WP_Stream_Wrapper_Interface::unlink()
	 * @see unlink()
	 * @link http://php.net/manual/en/streamwrapper.unlink.php
	 * @since 1.0.0
	 */
	public function unlink($uri) {
		$this->uri = $uri; // set instance URI
		return unlink($this->get_local_path());
	}
	
	/**
	 * Implements WP_Stream_Wrapper_Interface::url_stat()
	 *
	 * This function is called in response to all PHP stat() related
	 * functions. See streamWrapper::url_stat() documentation on the PHP
	 * Web site for more information.
	 *
	 * @param string $uri
	 *   the uri to get information about.
	 * @param $flags
	 *   additional flags set by the streams API. 
	 *   See streamWrapper:url_stat().
	 *
	 * @return mixed
	 *   as many elements as stat() does.
	 *
	 * @see WP_Stream_Wrapper_Interface::url_stat()
	 * @see stat()
	 * @link http://php.net/manual/en/streamwrapper.url-stat.php
	 * @since 1.0.0
	 */
	public function url_stat($uri, $flags) {
		$this->uri = $uri; // set instance URI
		
		if ($flags & STREAM_URL_STAT_QUIET) {
			return @stat($this->get_local_path());
		}
		else {
			return stat($this->get_local_path());
		}
	}
}

?>