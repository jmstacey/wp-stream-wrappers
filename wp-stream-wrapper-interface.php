<?php
/**
 * WordPress Stream Wrapper Interface
 *
 * This interface must be implemented [extended] by any stream wrappers that
 * wish to interact with the WordPress Stream Wrappers plugin.
 *
 * A stream is simply an abstraction of any kind of data. For example, a
 * stream could be a local file on the hard drive, a web page, or even input
 * from STDIN.
 *
 * Note: PHP 5 fopen() only supports URIs in the form of "scheme://target"
 * despite the fact that according to RFC 3986 a URI's scheme component
 * delimiter is in general just ":", and not "://". As a result of this
 * PHP limitation and for the sake of consistency, WordPress will only
 * accept URIs in full form (i.e. "scheme://target"). More information
 * on this problem can be found at the following links.
 *
 * @link http://www.faqs.org/rfcs/rfc3986.html
 * @link http://bugs.php.net/bug.php?id=47070
 * @link http://us3.php.net/manual/en/function.fopen.php
 *
 * @package     Stream Wrappers
 * @author      Jon Stacey <jon@jonsview.com>
 * @copyright   2010 Jon Stacey
 * @license     http://wordpress.org/about/gpl/
 * @link        http://github.com/jmstacey/wp-stream-wrappers
 * @version     1.0.0
 * @since       1.0.0
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
 * @version    1.0.0
 * @link       http://www.php.net/manual/en/class.streamwrapper.php
 * @see        WP_Stream_Wrapper_Interface
 * @since      1.0.0
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
 * @version    1.0.0
 * @link
 * @see        PHP_Stream_Wrapper_Interface
 * @since      1.0.0
 */
interface WP_Stream_Wrapper_Interface extends PHP_Stream_Wrapper_Interface {
    /**
     * Returns the URI of the current file object
     *
     * A URI stream is referenced as "scheme://target"
     *
     * @return string
     *   String containing the current WP_File URI.
     *
     * @access  public
     * @see     set_uri()
     * @since   1.0.0
     */
    public function get_uri();

    /**
     * Sets the URI of the current file object
     *
     * A URI stream is referenced as "scheme://target"
     *
     * @param string
     *   String containing the new URI for the WP_File object.
     *
     * @access  public
     * @see
     * @since   1.0.0
     */
    public function set_uri($uri);

    /**
     * Changes permissions of the resource
     *
     * This functionality does not exist in the official stream wraper
     * interface, and so is implemented just for WordPress.
     *
     * @param int $mode
     *   an integer value for the permissions. Consult PHP chmod()
     *   documentation for more information.
     *
     * @return bool
     *   true on success or false on failure.
     *
     * @access  public
     * @link    http://php.net/manual/en/function.chmod.php
     * @see
     * @since   1.0.0
     */
    public function chmod($mode);

    /**
     * Returns the directory name component of given path
     *
     * PHP's dirname() function does not support stream wrappers. This
     * default is provided so that individual wrappers may implement
     * their own solutions. See WP_Local_Stream_Wrapper_Base for an example.
     *
     * @param string $uri
     *   the URI or path.
     *
     * @return mixed
     *   the new temporary filename, or false on failure.
     *
     * @access  public
     * @link    http://us2.php.net/manual/en/function.dirname.php
     * @see
     * @since   1.0.0
     */
    public function dirname($uri);

    /**
     * Returns the canonical [absolute] path of the resource
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
     * @access  public
     * @link    http://php.net/manual/en/function.realpath.php
     * @see
     * @since   1.0.0
     */
    public function realpath();

    /**
     * Returns the web accessible URL for the resource
     *
     * Returns a URL that can be accessed from a browser. For example,
     * the web URL of the internal URI "local://example.txt" might be
     * "http://www.example.com/wp-content/example.txt".
     *
     * All wrappers that intend to make their resources available
     * in this way must implement this method.
     *
     * @return string
     *   the web accessible URL for the resource.
     *
     * @access  public
     * @see
     * @since   1.0.0
     */
    public function get_web_accessible_url();
}

?>