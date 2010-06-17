<?php
/**
 * This file contains the WordPress Stream Wrapper Interface. This interface
 * must be implemented [extended] by any stream wrappers that wish to interact
 * with the WordPress Stream Wrappers plugin.
 *
 * A stream is simply an abstraction of any kind of data. For example, a
 * stream could be a local file on the hard drive, a web page, or even
 * input from STDIN.
 *
 * Note: PHP 5 fopen() only supports URIs in the form of
 * "scheme://target" despite the fact that according to RFC 3986 a URI's
 * scheme component delimiter is in general just ":", and not "://".
 * As a result of this PHP limitation and for consitency WordPress will
 * will only accept URIs in full form (i.e. "scheme://target").
 *
 * @link http://www.faqs.org/rfcs/rfc3986.html
 * @link http://bugs.php.net/bug.php?id=47070
 * @link http://us3.php.net/manual/en/function.fopen.php
 *
 * @package Stream Wrappers
 */


/**
 * Generic PHP stream wrapper interface
 *
 * A simple prototype of the stream wrapper class used by the WordPress
 * stream wrapper interface. Refer to WP_Stream_Wrapper_Interface for
 * further documentation on resources and methods.
 *
 * Note: This interface should not be directly implemented. Instead, extend
 * WP_Stream_Wrapper_Interface.
 *
 * @package    Stream Wrappers
 * @author     Jon Stacey <jon@jonsview.com>
 * @version    Release: 1.0.0
 * @link       http://www.php.net/manual/en/class.streamwrapper.php
 * @see        WP_Stream_Wrapper_Interface
 * @since      Class available since Release 1.0.0
 */
interface PHP_Stream_Wrapper_Interface {	
	public function __construct();
	
	public function dir_closedir();	
	public function dir_opendir($uri, $options);
	public function dir_readdir();
	public function dir_rewinddir();
	
	public function mkdir($uri, $mode, $options);
	public function rename($from_uri, $to_uri);
	public function rmdir($uri, $options);
	
	public function stream_cast($cast_as);
	public function stream_close();
	public function stream_eof();
	public function stream_flush();
	public function stream_lock($operation);
	public function stream_open($uri, $mode, $options, &$opened_uri);
	public function stream_read($count);
	public function stream_seek($offset, $whence);
	public function stream_set_option($option, $arg1, $arg2);
	public function stream_stat();
	public function stream_tell();
	public function stream_write($data);
	public function unlink($uri);
	
	public function url_stat($uri, $flags);
}

/**
 * WordPress Stream Wrapper Extension
 *
 * Extends the PHP_Stream_Wrapper_Interface with methods expected by
 * WordPress stream wrapper classes
 *
 * @package    Stream Wrappers
 * @author     Jon Stacey <jon@jonsview.com>
 * @version    Release: 1.0.0
 * @link       
 * @see        PHP_Stream_Wrapper_Interface
 * @since      Class available since Release 1.0.0
 */
interface WP_Stream_Wrapper_Interface extends PHP_Stream_Wrapper_Interface {	
	/**
	 * URI (stream) of the current file object
	 *
	 * A stream is referenced as "scheme://target".
	 *
	 * @var string
	 * @access private
	 */
	private $uri;
	
	/**
	 * Returns the URI of the current file object
	 *
	 * A URI stream is referenced as "scheme://target"
	 *
	 * @return string
	 *   String containing the current WP_File URI.
	 *
	 * @access public
	 * @see set_uri()
	 * @since Method available since Release 1.0.0
	 */
	public function get_uri() {
		return $this->uri;
	}
	
	/**
	 * Sets the URI of the current file object
	 *
	 * A URI stream is referenced as "scheme://target"
	 *
	 * @param string
	 *   String containing the new URI for the WP_File object.
	 *
	 * @access public
	 * @see 
	 * @since Method available since Release 1.0.0
	 */
	public function set_uri($uri) {
		// TODO: Normalize the URI first?
		$this->uri = $uri;
	}
	
	/**
	 * Changes permissions of the resource
	 *
	 * This functionality does not exist in the official stream wraper
	 * interface, and so is implemented just for WordPress.
	 *
	 * @param int $mode
	 *   an integer value for the permissions. Consult PHP chmod()
	 *   documentation for more information.
	 * @return bool
	 *   true on success or false on failure.
	 *
	 * @access public
	 * @link http://php.net/manual/en/function.chmod.php
	 * @see 
	 * @since Method available since Release 1.0.0
	 */
	public function chmod($mode);
	
	/**
	 * Returns the canonical, absolute path of the resource
	 *
	 * PHP's realpath() does not support stream wrappers. This default is
	 * provided so that individual wrappers may implement their own
	 * solutions. See WP_Local_Stream_Wrapper_Base for an example.
	 *
	 * @return string
	 *   a string with absolute pathname on success [as implemented by
	 *   WP_Local_Stream_Wrapper_Base], or false on failure or if the
	 *   registered wrapper does not provide an implementation.
	 *
	 * @access public
	 * @link http://php.net/manual/en/function.chmod.php
	 * @see 
	 * @since Method available since Release 1.0.0
	 */
	public function realpath();
	
}


?>