<?php
/**
 * This file contains the WordPress Stream Wrapper Registry tests. It makes
 * sure that the registry implementation is up to spec and working properly.
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
 * WordPress Stream Wrapper Registry Test cases
 *
 * This class provides tests for the Stream Wrapper Registry.
 *
 * @package    Stream Wrappers
 * @author     Jon Stacey <jon@jonsview.com>
 * @version    1.0.0
 * @see        WP_Stream_Wrapper_Registry
 * @since      1.0.0
 */
class WP_Stream_Wrapper_Registry_Test extends WPTestCase {

	/**
	 * Assert that the registry has the core attributes needed
	 */
	public function test_class_attributes() {
		$this->assertClassHasStaticAttribute('stream_wrappers', 'WP_Stream_Wrapper_Registry');
		$this->assertClassHasStaticAttribute('registry', 'WP_Stream_Wrapper_Registry');
	}

	/**
	 * Test getting the registry singleton object
	 *
	 * Tests WP_Stream_Wrapper_Registry::get_registry()
	 */
	public function test_get_registry() {
		$registry = WP_Stream_Wrapper_Registry::get_registry();

		$this->assertTrue(is_object($registry), "get_registry() should return an object.");
	}

	/**
	 * Test unregistering wrappers
	 *
	 * Tests WP_Stream_Wrapper_Registry::unregister_wrapper()
	 */
	public function test_unregister_wrapper() {
		// Assert that the test wrapper is still not registered
		$this->assertArrayNotHasKey('test', WP_Stream_Wrapper_Registry::get_stream_wrappers(), 'The test stream wrapper should not be registered yet.');

		// Register test stream wrapper
		wp_test_stream_wrapper_register();

		// Assert test stream wrapper is registered
		$this->assertArrayHasKey('test', WP_Stream_Wrapper_Registry::get_stream_wrappers(), 'The test stream wrapper should be registered here.');

		// Try overriding test stream wrapper with a new [identical] implementation
		wp_test_stream_wrapper_register();

		// Assert test stream wrapper is still registered
		$this->assertArrayHasKey('test', WP_Stream_Wrapper_Registry::get_stream_wrappers());

		// Unregister test stream wrappers
		WP_Stream_Wrapper_Registry::unregister_wrapper('test');

		// Assert test stream wrapper no longer registered
		$this->assertArrayNotHasKey('test', WP_Stream_Wrapper_Registry::get_stream_wrappers(), 'The test stream wrapper should have been unregistered.');

		// Assert false return trying to remove nonexistent stream wrapper
		$this->assertFalse(WP_Stream_Wrapper_Registry::unregister_wrapper('test'));
	}

	/**
	 * Test registering wrappers
	 *
	 * Tests WP_Stream_Wrapper_Registry::register_wrapper()
	 */
	public function test_register_wrapper() {
		// Register test stream wrapper
		wp_test_stream_wrapper_register();

		// Assert test stream wrapper is registered
		$this->assertArrayHasKey('test', WP_Stream_Wrapper_Registry::get_stream_wrappers());

		// Unregister test stream wrappers
		WP_Stream_Wrapper_Registry::unregister_wrapper('test');
	}

	/**
	 * Test getting the stream wrappers array
	 *
	 * Tests WP_Stream_Wrapper_Registry::get_stream_wrappers().
	 */
	public function test_get_stream_wrappers() {
		// Register the test stream wrapper and check the schema.
		wp_test_stream_wrapper_register();

		$stream_wrappers = WP_Stream_Wrapper_Registry::get_stream_wrappers();

		// Assert that the test wrapper is registered
		$this->assertArrayHasKey('test', $stream_wrappers, 'The test stream wrapper should be registered here.');

		// Assert that the test wrapper has the proper schema
		$this->assertEquals('WP Test Stream Wrapper', $stream_wrappers['test']['name']);
		$this->assertEquals('WP_Test_Stream_Wrapper', $stream_wrappers['test']['class']);
		$this->assertEquals('WP Test Stream Wrapper provides a simple extension of the WP_Local_Stream_Wrapper_Base class.', $stream_wrappers['test']['description']);

		// Unregister test stream wrappers
		WP_Stream_Wrapper_Registry::unregister_wrapper('test');
	}

	/**
	 * Test cloning the registry
	 *
	 * Attempting to clone the registry should trigger an error.
	 */
	public function test_clone() {
		$this->setExpectedException('PHPUnit_Framework_Error');
		$cloned_registry = clone WP_Stream_Wrapper_Registry::get_registry();
	}
}

?>