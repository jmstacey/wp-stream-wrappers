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
 * @category   CategoryName
 * @package    PackageName
 * @author     Original Author <author@example.com>
 * @author     Another Author <another@example.com>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      Class available since Release 1.2.0
 * @deprecated Class deprecated in Release 2.0.0
 */
class Streams_Wrapper_Registry {
	
	
}

?>