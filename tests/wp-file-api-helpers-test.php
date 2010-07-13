<?php
/**
 * This file contains the WordPress File API helpers tests.
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
 * WordPress File API Helpers tests
 *
 * This class provides tests for the WordPress File API Helper functions.
 *
 * @package    Stream Wrappers
 * @author     Jon Stacey <jon@jonsview.com>
 * @version    Release: 1.0.0
 * @see        WP_Local_Stream_Wrapper_Base
 * @since      Class available since Release 1.0.0
 */
class WP_File_Helpers_Test extends WPTestCase {
	
	/**
	 * Setup this test case
	 */
	function setUp() {
		// Register test stream wrapper
		wp_test_stream_wrapper_register();
		
		// @todo create a test directory with some example files and directories
	}
	
	/**
	 * The following things should be tested in this first round:
	 *
	 * - wp_chmod()
	 * - wp_realpath()
	 * - wp_tempnam_stream_compatible()
	 * - wp_dirname()
	 */
	
	/**
	 * Tests changing permissions of file or directory
	 *
	 * Tests wp_chmod()
	 */
	public function test_wp_chmod() {
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
	
	/**
	 * Teardown this test case
	 */
	function tearDown() {
		// Unregister test stream wrapper
		WP_Stream_Wrapper_Registry::unregister_wrapper('test');
		
		// @todo delete the test directory
	}
}

?>