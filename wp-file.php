<?php
/**
 * This file contains the WordPress File API that is compatible with stream
 * wrappers. WP_File could also be known as WP_Stream (if that makes more
 * sense). A WP_File is a stream. A stream is a resource object which
 * exhibits streamable behavior.
 *
 * @link http://www.php.net/manual/en/intro.stream.php
 *
 * @package Stream Wrappers
 */

/**
 * WordPress File Object and helper methods
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
class WP_File {
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
	 * Returns the scheme of the current file object's URI
	 *
	 * @return string
	 *   String containing the name of the scheme.
	 *
	 * @access public
	 * @see 
	 * @since Method available since Release 1.0.0
	 */
	public function get_uri_scheme() {
		/*
			TODO Implement get_uri_scheme()
		*/
	}

	/**
	 * Returns the target of the current file object's URI
	 *
	 * @return string
	 *   String containing the target (path). For example, the URI
	 *   "local://foobar/test.txt" would return "foobar/test.txt".
	 *
	 * @access public
	 * @see 
	 * @since Method available since Release 1.0.0
	 */
	public function get_uri_target() {
		/*
			TODO Implement get_uri_target()
		*/
	}
	
	/**
	 * Teturns a reference to the stream wrapper class responsible for
	 * the current file object's URI.
	 *
	 * The scheme determines the stream wrapper class that should be used by
	 * consulting the WP stream wrapper registry.
	 *
	 * @param string
	 *   String containing the URI stream. A stream is referenced as
	 *   "scheme://target".
	 * @return object
	 *   Returns a new stream wrapper object appropriate for the given
	 *   URI, or false if no suitable registered wrapper could be found.
	 *   For example, a URI of "local://example.txt" would return a new
	 *   WordPress local stream wrapper object (WP_Local_Stream_Wrapper).
	 *
	 * @access public
	 * @see 
	 * @since Method available since Release 1.0.0
	 */
	public function get_stream_wrapper_instance() {
		/*
			TODO Implement get_stream_wrapper_instance()
		*/
	}
	
	/**
	 * Normalizes a URI by making it syntactically correct
	 *
	 * The following actions are performed on the URI and the changes
	 * are saved in-place. That is, this function will manipulate the
	 * $uri instance variable of the current file object.
	 *
	 * Note: This is a helper function that can be called without a WP_File
	 * instance. It does not automatically update the URI of an instance.
	 * That is, if you want to change the URI of a file object you will need
	 * to call this function followed by set_uri().
	 *
	 * @param string
	 *   String containing the URI to normalize.
	 * @return string
	 *   String containing the normalized URI after the modifications listed
	 *   in the function description have been performed.
	 *
	 * @access public
	 * @static
	 * @see 
	 * @since Method available since Release 1.0.0
	 */
	public static function normalize_uri($uri) {
		/*
			TODO Implement normalize_uri()
		*/
	}
	
	/**
	 * Returns the stream wrapper class name for a given scheme
	 *
	 * This is a helper function that can be called without a WP_File
	 * instance.
	 *
	 * @param string
	 *   String containing the stream scheme.
	 * @return string
	 *   String containing the class name of the registered handler. If no
	 *   handler exists for this scheme, false is returned.
	 *
	 * @access public
	 * @static
	 * @see 
	 * @since Method available since Release 1.0.0
	 */
	public static function stream_wrapper_class_name($scheme) {
		/*
			TODO Implement stream_wrapper_class_name()
		*/
	}

	/**
	 * Checks the validity of a given stream wrapper scheme
	 *
	 * Confirms that there is a registered stream handler for the given
	 * scheme and that it is callable. This is useful if you want to confirm
	 * a valid scheme without creating a new instance of the registered
	 * wrpper.
	 *
	 * This is a helper function that can be called without a WP_File
	 * instance. 
	 *
	 * @param string
	 *   String containing the URI scheme. A stream is referenced as
	 *   "scheme://target".
	 * @return bool
	 *   Boolean true returned if string is the name of a valide stream, or
	 *   boolean false if the scheme does not have a registered wrapper.
	 *
	 * @access public
	 * @static
	 * @see 
	 * @since Method available since Release 1.0.0
	 */
	public static function stream_wrapper_scheme_valid($scheme) {
		/*
			TODO Implement stream_wrapper_scheme_valid()
		*/
	}

}
	
?>