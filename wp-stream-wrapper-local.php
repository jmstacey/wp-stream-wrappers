<?php
/**
 * This file contains the wp-content WordPress Stream Wrapper which implements
 * the "local://" scheme. This is a simple and complete stream wrapper
 * implementation for testing, reference, and extension purposes. Other stream
 * wrappers that manipulate files on the local filesystem can quickly extend
 * this class to suit more specific needs. The WP Test [test://] stream
 * is an excellent example of this use case.
 *
 * @package Stream Wrappers
 */

/**
 * WordPress stream wrapper: test
 *
 * A simple and complete stream wrapper implementation for testing and
 * reference purposes. URIs such as "test://example.txt" are expanded
 * to a normal filesystem path where the test files are contained, for
 * example "wp-content/plugins/wp-stream-wrappers-test/files". After the URIs
 * are expanded, PHP filesystem funcations are invoked with the expanded path.
 *
 * @package    Stream Wrappers
 * @author     Jon Stacey <jon@jonsview.com>
 * @version    Release: 1.0.0
 * @link       
 * @see        WP_Stream_Wrapper_Interface
 * @since      Class available since Release 1.0.0
 */
abstract class WP_Local_Stream_Wrapper implements WP_Stream_Wrapper_Interface {
	
}

?>