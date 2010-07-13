<?php
/**
 * This file contains the WordPress Local Stream Wrapper Base tests.
 *
 * @package Stream Wrappers
 */

/** 
 * Initialize the Stream Wrappers Plugin in the proper sequence
 */
require_once WP_PLUGIN_DIR.'/wp-stream-wrappers/wp-stream-wrappers.php';

/** 
 * This file contains the WP Test Stream Wrapper class.
 */
require_once WP_PLUGIN_DIR.'/wp-stream-wrappers/tests/wp-test-stream-wrapper.php';

/**
 * WordPress Local Stream Wrapper Base Test cases
 *
 * This class provides tests for the WP Local Stream Wrapper Base class.
 * The test stream wrapper is used to perform tests which builds on this
 * base class.
 *
 * @package    Stream Wrappers
 * @author     Jon Stacey <jon@jonsview.com>
 * @version    Release: 1.0.0
 * @see        WP_Local_Stream_Wrapper_Base
 * @since      Class available since Release 1.0.0
 */
class WP_Local_Stream_Wrapper_Base_Test extends WPTestCase {
	
	/**
	 * Setup this test case
	 */
	function setUp() {
		// Register test stream wrapper
		wp_test_stream_wrapper_register();
	}
	
	/**
	 * The following things should be tested in this first round:
	 *
	 * - mkdir()
	 * - rmdir()
	 * - creating a file
	 * - Writing to a file
	 * - unlinking (deleting) a file
	 * - wp_chmod()
	 * - wp_realpath()
	 * - wp_tempnam_stream_compatible()
	 * - wp_dirname()
	 * - get_local_path()
	 * - get_wrapper_path()
	 */
	
	/**
	 * Tests creating directories
	 *
	 * Tests WP_Local_Stream_Wrapper_Base::mkdir()
	 */
	public function test_mkdir() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
	
	/**
	 * Teardown this test case
	 */
	function tearDown() {
		// Unregister test stream wrapper
		WP_Stream_Wrapper_Registry::unregister_wrapper('test');
	}
}

?>