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
 * Initializes WP Stream Wrappers Registry
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
 * An example of how to write code to PEAR's standards
 *
 * Docblock comments start with "/**" at the top.  Notice how the "/"
 * lines up with the normal indenting and the asterisks on subsequent rows
 * are in line with the first asterisk.  The last line of comment text
 * should be immediately followed on the next line by the closing asterisk
 * and slash and then the item you are commenting on should be on the next
 * line below that.  Don't add extra lines.  Please put a blank line
 * between paragraphs as well as between the end of the description and
 * the start of the @tags.  Wrap comments before 80 columns in order to
 * ease readability for a wide variety of users.
 *
 * Docblocks can only be used for programming constructs which allow them
 * (classes, properties, methods, defines, includes, globals).  See the
 * phpDocumentor documentation for more information.
 * http://phpdoc.org/docs/HTMLSmartyConverter/default/phpDocumentor/tutorial_phpDocumentor.howto.pkg.html
 *
 * The Javadoc Style Guide is an excellent resource for figuring out
 * how to say what needs to be said in docblock comments.  Much of what is
 * written here is a summary of what is found there, though there are some
 * cases where what's said here overrides what is said there.
 * http://java.sun.com/j2se/javadoc/writingdoccomments/index.html#styleguide
 *
 * The first line of any docblock is the summary.  Make them one short
 * sentence, without a period at the end.  Summaries for classes, properties
 * and constants should omit the subject and simply state the object,
 * because they are describing things rather than actions or behaviors.
 *
 * Below are the tags commonly used for classes. @category through @version
 * are required.  The remainder should only be used when necessary.
 * Please use them in the order they appear here.  phpDocumentor has
 * several other tags available, feel free to use them.
 *
 * @package    Stream Wrappers
 * @author     Jon Stacey <jon@jonsview.com>
 * @version    Release: 1.0.0
 * @link       
 * @see        
 * @since      Class available since Release 1.0.0
 */

class WP_Stream_Wrapper_Registry {
	
	private static $registry;
	
	private function __construct() {
		// Run register_stream_wrapper action which tells stream wrapper
		// plugins that it's time to register themselves.
		do_action('register_stream_wrapper');
	}
	
	public static function get_registry() {
		if (!self::$registry) {
			self::$registry = new WP_Stream_Wrapper_Registry();
		}
		
		return self::$registry;
	}
	
}

?>