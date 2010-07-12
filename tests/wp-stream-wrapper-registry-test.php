<?php

// Make sure the stream wrappers plugin is initialized in the proper sequence.
require_once WP_PLUGIN_DIR.'/wp-stream-wrappers/wp-stream-wrappers.php';

class WP_Stream_Wrapper_Registry_Test extends WPTestCase {

	public function test_class_attributes() {
		$this->assertClassHasStaticAttribute('stream_wrappers', 'WP_Stream_Wrapper_Registry');
		$this->assertClassHasStaticAttribute('registry', 'WP_Stream_Wrapper_Registry');
	}
	
	public function test_get_registry() {
		$registry = WP_Stream_Wrapper_Registry::get_registry();
		
		$this->assertTrue(is_object($registry), "get_registry() should return an object.");
	}
	
	public function test_register_wrapper() {
		// @todo implement
		$this->markTestIncomplete("This test has not been implemented yet");
	}
	
	public function test_unregister_wrapper() {
		// @todo implement
		$this->markTestIncomplete("This test has not been implemented yet");
	}
	
	public function test_get_stream_wrappers() {
		// @todo implement
		$this->markTestIncomplete("This test has not been implemented yet");
	}
	
	public function test_clone() {
		// @todo implement
		$this->markTestIncomplete("This test has not been implemented yet");
	}
}

?>