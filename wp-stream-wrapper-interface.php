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
interface Php_Stream_Wrapper_Interface {	
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

?>