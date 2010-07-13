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
 * WordPress Local Stream Wrapper Base Test cases
 *
 * This class provides tests for the WP Local Stream Wrapper Base.
 *
 * @package    Stream Wrappers
 * @author     Jon Stacey <jon@jonsview.com>
 * @version    Release: 1.0.0
 * @see        WP_Local_Stream_Wrapper
 * @since      Class available since Release 1.0.0
 */
class WP_Local_Stream_Wrapper_Base_Test extends WPTestCase {
	
	/**
	 * Setup this test case
	 */
	function setUp() {
		// @todo does anything need to be done here?
	}
	
	/**
	 * Tests getting the scheme of a stream
	 *
	 * Tests WP_Stream::scheme()
	 */
	public function test_scheme() {
		$stream   = 'test://example/path1/path2/hello_world.txt';
		$expected = 'test';
		
		$actual = WP_Stream::scheme($stream);
		
		$this->assertEquals($expected, $actual, "WP_Stream::scheme returned '$actual' instead of the expected '$expected'.");
	}
	
	
	
	
	$this->markTestIncomplete('This test has not been implemented yet.');
	
}

?>