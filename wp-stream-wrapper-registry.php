<?php
/**
 * This file contains the Stream Wrapper Registry. The registry keeps track of
 * the scheme and implementation class of each registered wrapper. The
 * WordPress registry differs from PHP's native registry. The WordPress
 * registry builds on top of PHPs native support and adds more functionality.
 * For example, PHP does not currently provide native capabilities to ask
 * "what wrapper class is responsible for handling this URI or scheme?" This
 * ability is vital to providing functions specific to WordPress.
 *
 * It is important to note that the WP Wrapper Registry does not register
 * PHP's built-in wrappers such as 'http' or 'ssl'. These wrappers may still
 * be used with PHP functions normally, but any functions that rely on the
 * WP Registry will fail because the wrapper will not be found. For example,
 * the call StreamWrapperRegistry::valid_scheme('http') would return false
 * despite PHP's built-in http wrapper being available. In most cases this
 * will not be an issue because PHP's wrappers do not contain attributes
 * specific to WordPress.
 *
 * @package Stream Wrappers
 */


/**
 * Initializes WP Stream Wrapper Registry
 * 
 * Builds the stream wrapper registry. This is a helper function that simply
 * makes sure that the registry gets built before use.
 *
 * @package Stream Wrappers
 * @since 1.0.0
 */
function wp_stream_wrapper_registry_init() {
	$registry = WP_Stream_Wrapper_Registry::get_registry();
}


/**
 * WP Stream Wrapper Registry
 *
 * Stores information about registered WordPress stream wrappers in
 * a single location for easy access and reference.
 *
 * @package    Stream Wrappers
 * @author     Jon Stacey <jon@jonsview.com>
 * @version    Release: 1.0.0
 * @link       
 * @see        
 * @since      Class available since Release 1.0.0
 */
class WP_Stream_Wrapper_Registry {

	/**
	 * The wrapper registry object
	 *
	 * The stream wrapper registry singleton.
	 *
	 * @var object
	 * @access private
	 */
	private static $registry;
	
	/**
	 * The wrapper registry
	 *
	 * An array containining registered stream wrappers in the following
	 * format:
	 *
	 * $registry[$scheme][$info];
	 * $scheme is the string version of the scheme (e.g. 'http')
	 * $info is an array with additional metadata as follows:
	 * 		'name' => 'wrapper name',
	 *		'class' => 'wrapper implementation class name',
	 *		'description' => 'brief description of wrapper.'
	 *
	 * @var array
	 * @access private
	 */
	private static $stream_wrappers = array();
	
	/**
	 * Initializes the WP_Stream_Wrapper_Regisitry singleton object
	 *
	 * @access private
	 * @since Method available since Release 1.0.0
	 */
	private function __construct() {
		// Run register_stream_wrapper action which tells stream wrapper
		// plugins that it's time to register themselves.
		do_action('register_stream_wrapper');
	}
	
	/**
	 * Gets the entire stream wrapper registry
	 *
	 * Returns the entire stream wrapper registry, or initializes a new
	 * registry if one does not exist.
	 *
	 * @return object
	 *   A singleton object for the stream wrapper registry.
	 *
	 * @access public
	 * @static
	 * @see WP_Stream_Wrapper_Registry::get_stream_wrappers()
	 * @since Method available since Release 1.0.0
	 */
	public static function get_registry() {
		if (!isset(self::$registry)) {
			self::$registry = new WP_Stream_Wrapper_Registry();
		}
		
		return self::$registry;
	}
	
	/**
	 * Registers given stream wrapper
	 *
	 * Registers the given stream wrapper and populates the stream
	 * wrapper registry with its metadata.
	 *
	 * Here is an example of what wrapper metadata might look like for
	 * a wrapper implementing the sample scheme (e.g. sample://resource).
	 * <code>
	 * $wrapper_metadata = array(
	 *		'name' => 'Sample wrapper',
	 *		'class' => 'WP_Stream_Wrapper_Sample',
	 *		'description' => 'A sample WordPress stream wrapper.'
	 * );
	 * </code>
	 *
	 * @param string
	 *   String containing the scheme implemented by wrapper (e.g. 'sample').
	 * @param array 
	 *   Stream wrapper metadata array. See array structure example above.
	 *
	 * @return array
	 *   An array containing all metadata for all registered wrappers.
	 *
	 * @access public
	 * @static
	 * @see WP_Stream_Wrapper_Registry::unregister_wrapper()
	 * @since Method available since Release 1.0.0
	 */
	public static function register_wrapper($scheme, $metadata) {
		// Ask PHP for registered wrappers (stream_get_wrappers()).
		// If a wrapper is already registered with this scheme, override.
		if (in_array($scheme, stream_get_wrappers(), true)) {
			stream_wrapper_unregister($scheme);
		}
		
		self::$stream_wrappers[$scheme] = $metadata; // Debug purposes only
	
		// if (stream_wrapper_register($scheme, $metadata['class'])) {
		// 	// If registered with PHP succesfully, add to registry.
		// 	self::$stream_wrappers[$scheme] = $metadata;
		// }
		// else {
		// 	// Throw an error. This should be very rare.
		// 	// @todo: Is this proper use of WP_Error?
		// 	$error = new WP_Error();
		// 	$error.WP_Error('199001', "Unable to register wrapper implementing scheme '$scheme'");
		// }
	}
	
	/**
	 * Gets the stream wrappers registration array
	 *
	 * Returns the heart of the stream wrappers registry: the
	 * array of registerd stream wrapper metadata.
	 *
	 * @return array
	 *   An array containing all metadata for registered wrappers.
	 *
	 * @access public
	 * @static
	 * @see WP_Stream_Wrapper_Registry::register_wrapper()
	 * @since Method available since Release 1.0.0
	 */
	public static function get_stream_wrappers() {
		return self::$stream_wrappers;
	}
	
	/**
	 * Prevents users from cloning the registry
	 *
	 * This registry must remain singular throughout execution. Multiple
	 * copies of the registry could make things very confusing. Additionally,
	 * no use cases can currentlybe conceived in which an identical copy of
	 * the registry would be needed.
	 */
    public function __clone()
    {
        trigger_error('Cloning the WP Stream Wrapper Registry is not allowed.', E_USER_ERROR);
    }
}

?>