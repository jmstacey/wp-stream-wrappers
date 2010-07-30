<?php
/**
 * This file contains the WordPress Stream Class tests.
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
 * Wordpress Stream Class Test cases
 *
 * This class provides tests for the Stream Class
 *
 * @package    Stream Wrappers
 * @author     Jon Stacey <jon@jonsview.com>
 * @version    Release: 1.0.0
 * @see        WP_Stream
 * @since      Class available since Release 1.0.0
 */
class WP_Stream_Test extends WPTestCase {
	
	/**
	 * Setup this test case
	 */
	function setUp() {
		// Register test stream wrapper
		wp_test_stream_wrapper_register();
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
	
	/**
	 * Tests getting the target of a stream
	 *
	 * Tests WP_Stream::target()
	 */
	public function test_target() {
		$stream   = 'test://example/path1/path2/hello_world.txt';
		$expected = 'example/path1/path2/hello_world.txt';
		
		$actual = WP_Stream::target($stream);
		
		$this->assertEquals($expected, $actual, "WP_Stream::target returned '$actual' instead of the expected '$expected'.");
	}
	
	/**
	 * Tests getting a new wrapper instance
	 *
	 * Tests WP_Stream::new_wrapper_instance()
	 */
	public function test_new_wrapper_instance() {
		$uri = 'test://example/path1/path2/hello_world.txt';
		
		$wrapper_object = WP_Stream::new_wrapper_instance($uri);
		$this->assertTrue(is_object($wrapper_object));
		
		unset($wrapper_object);

		// Call should fail if only a scheme is provided
		$this->assertFalse(WP_Stream::new_wrapper_instance('test'));

		// Call should fail if no registered wrapper is available
		$this->assertFalse(WP_Stream::new_wrapper_instance('hullabloothiswrapperdoesnotexist'));
	}
	
	/**
	 * Tests getting the wrapper class name
	 *
	 * Tests WP_Stream::wrapper_class_name()
	 */
	public function test_wrapper_class_name() {
		$scheme   = 'test';
		$expected = 'WP_Test_Stream_Wrapper';
		
		$actual = WP_Stream::wrapper_class_name($scheme);
		$this->assertEquals($expected, $actual, "WP_Stream::wrapper_class_name returned '$actual' instead of the expected '$expected'");
	}
	
	/**
	 * Tests checking the validity of a scheme
	 *
	 * Tests WP_Stream::scheme_valid()
	 */
	public function test_scheme_valid() {
		$this->assertTrue(WP_Stream::scheme_valid('test'));
		$this->assertFalse(WP_Stream::scheme_valid('nonexistentwrapper'));
	}
	
	/**
	 * Tests normalizing a stream
	 *
	 * Tests WP_Stream::normalize()
	 */
	public function test_normalize() {
		/**
		 * Test malformed URI at the scheme/target junction
		 */
		$malformed_uri = 'test:///example/path1/path2/hello_world.txt';
		$expected 	   = 'test://example/path1/path2/hello_world.txt';
		
		$this->assertEquals($expected, WP_Stream::normalize($malformed_uri));
		
		/**
		 * Test malformed URI with multiple separators in target
		 */
		$malformed_uri = 'test://example/path1//path2/hello_world.txt';
		$expected	   = 'test://example/path1/path2/hello_world.txt';
		
		$this->assertEquals($expected, WP_Stream::normalize($malformed_uri));
		
		/**
		 * Test malformed URI with mutliple problems
		 */
		$malformed_uri = 'test:////example/path1//path2//hello_world.txt';
		$expected 	   = 'test://example/path1/path2/hello_world.txt';
		
		$this->assertEquals($expected, WP_Stream::normalize($malformed_uri));
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