<?php
/**
 * This file contains the WordPress Stream Wrappers helper class. It provides
 * useful functions that are used by WordPress Wrapper implementations.
 *
 * @package Stream Wrappers
 */

/**
 * WordPress Stream File Object and helper methods
 *
 * @todo add more thourough description here
 *
 * @package    Stream Wrappers
 * @author     Jon Stacey <jon@jonsview.com>
 * @version    Release: 1.0.0
 * @link       http://www.php.net/manual/en/intro.stream.php
 * @see        
 * @since      Class available since Release 1.0.0
 */
class WP_Stream {
	/**
	 * Returns the scheme of a stream
	 *
	 * A stream is referenced as "scheme://target".
	 *
	 * Example usage of this static method:
	 * <code>
	 * $uri = "local://example.txt"
	 * $ret = WP_Stream::scheme($uri);
	 * // $ret is "local"
	 * </code>
	 *
	 * @param string $uri
	 *   the stream URI referenced as "scheme://target".
	 * @return mixed
	 *   the name of the scheme, or false if there is no scheme.
	 *
	 * @access public
	 * @static
	 * @see 
	 * @since Method available since Release 1.0.0
	 */
	public static function scheme($uri) {
		$components = explode('://', $uri, 2);

		return count($components) == 2 ? $components[0] : false;
	}

	/**
	 * Returns the target of a stream
	 *
	 * A stream is referenced as "scheme://target".
	 *
	 * Example usage of this static method:
	 * <code>
	 * $uri = "local://foobar/example.txt"
	 * $ret = WP_Stream::target($uri);
	 * // $ret is "foobar/example.txt"
	 * </code>
	 *
	 * @param string $uri
	 *   the stream URI referenced as "scheme://target".
	 * @return string
	 *   the target (a.k.a. path) of the stream. An empty string ('') is
	 *   returned if the stream does not have an explicit target, such as
	 *   in the case of "scheme://"
	 *
	 * @access public
	 * @static
	 * @see 
	 * @since Method available since Release 1.0.0
	 */
	public static function target($uri) {
		list($scheme, $target) = explode('://', $uri, 2);
		
		// Remove unnecessary leading and traling slashes.
		return trim($target, '\/');
	}
	
	/**
	 * Returns a new instance of the wrapper responsible for given stream URI
	 *
	 * Example use:
	 * <code>
	 * // Option 1 - in this case the wrapper instance is initialized with the
	 * // given full URI "local://example.txt"
	 * $uri = "local://example.txt"
	 * $instance = WP_Stream::wrapper_instance($uri);
	 * 
	 * // Option 2 - in this case only the scheme (in proper stream reference
	 * // form), so the wrapper instance is initialized without a 
	 * // target "local://".
	 * $scheme = "local"
	 * $instance = WP_Stream:wrapper_instance($scheme);
	 * </code>
	 * In both options in the above example, an instance of
	 * WP_Local_Stream_Wrapper is returned.
	 *
	 * Note: Even if a scheme is used, or a URI without a target, the instance
	 * URI will be initialized as a full stream. That is, the instance URI
	 * will be "scheme://", which has both a scheme and target despite target
	 * being empty.
	 *
	 * @param string $uri
	 *   the stream URI. For example, both "local://example.txt" or
	 *   "local://" are acceptable.
	 * @return object
	 *   a new stream wrapper instance for the given URI. Returns false if
	 *   a registered wrapper cannot be found.
	 *
	 * @access public
	 * @static
	 * @see 
	 * @since Method available since Release 1.0.0
	 */
	public static function new_wrapper_instance($uri) {
		$scheme 	= WP_Stream::scheme($uri);
		$class_name = WP_Stream::wrapper_class_name($scheme);
		
		if (class_exists($class_name)) {
			$instance = new $class_name();
			$instance->set_uri($uri);
			return $instance;
		} else {
			return false;
		}
	}
	
	/**
	 * Returns ths class name of the wrapper implementation for given scheme
	 *
	 * @param string $scheme
	 *   the stream scheme.
	 * @return string
	 *   the class name of the registered wrapper handler, or false if
	 *   there is no registered handler.
	 *
	 * @access public
	 * @static
	 * @see 
	 * @since Method available since Release 1.0.0
	 */
	public static function wrapper_class_name($scheme) {
		$wrappers = WP_Stream_Wrapper_Registry::get_stream_wrappers();
		return empty($wrappers[$scheme]) ? false : $wrappers[$scheme]['class'];
	}

	/**
	 * Checks the validity of a given stream wrapper scheme
	 *
	 * Confirms that there is a registered stream handler for the given
	 * scheme and that it is callable. This is useful if you want to confirm
	 * a valid scheme without creating a new instance of the registered
	 * wrapper.
	 *
	 * @param string $scheme
	 *   the stream scheme.
	 * @return bool
	 *   true if the scheme is valid, or false if the scheme does not have
	 *   a registered handler.
	 *
	 * @access public
	 * @static
	 * @see 
	 * @since Method available since Release 1.0.0
	 */
	public static function scheme_valid($scheme) {
		$class_name = WP_Stream::wrapper_class_name($scheme);
		
		if (class_exists($class_name)) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Normalizes a stream URI by making it syntactically correct
	 *
	 * The following actions are performed on the stream URI that is
	 * returned.
	 *
	 * - Removing leading slashes from target.
	 *
	 * @param string $uri
	 *   the stream URI to normalize.
	 * @return string
	 *   the normalized stream URI after the modifications listed in the
	 *   function description have been performed.
	 *
	 * @access public
	 * @static
	 * @see 
	 * @since Method available since Release 1.0.0
	 */
	public static function normalize($uri) {
		$scheme = WP_Stream::scheme($uri);
		
		if ($scheme && WP_Stream::scheme_valid($scheme)) {
			$target = WP_Stream::target($uri);
			
			if ($target !== false) {
				$uri = $scheme . '://' . $target;
			}
		}
		
		return $uri;
	}

}
	
?>