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
	 * Test file name
	 *
	 * This variable holds the name fo the test file
	 *
	 * @var string
	 * @access private
	 */
	private $filename;
	
	/**
	 * Test file URI
	 *
	 * This variable holds the URI of the test file
	 *
	 * @var string
	 * @access private
	 */
	private $uri;
	
	/**
	 * Test file path
	 *
	 * This variable holds the local path that the URI resides at.
	 *
	 * @var string
	 * @access private
	 */
	private $path;
	
	/**
	 * Test file contents
	 *
	 * This variable holds the expected contents of the test file.
	 *
	 * @var string
	 * @access private
	 */
	private $expected_contents;
	
	/**
	 * Test directory path
	 *
	 * This variable holds the local path that the test wrapper is pointed to.
	 *
	 * @var string
	 * @access private
	 */
	private $test_dir;
	
	/**
	 * Setup this test case
	 */
	function setUp() {
		// Register test stream wrapper
		wp_test_stream_wrapper_register();
		
		/**
		 * Initialize instance variables
		 */
		$wrapper 		= WP_Stream::new_wrapper_instance('test://');
		$this->test_dir = $wrapper->get_wrapper_path();
		
		$this->filename = 'testfile.txt';
		$this->uri		= 'test://'.$this->filename;
		$this->path		= $this->test_dir.'/'.$this->filename;
		
		$this->expected_contents = 'The miracle is this - the more we share, the more we have. -- Leonard Nimoy';
		
		/**
		 * Setup and assert starting evironment
		 */
		if (file_exists($this->uri)) {
			unlink($this->uri);
		}
		
		$this->assertFileNotExists($this->uri);
		$this->assertFileNotExists($this->path);
		
		// Open and write to the file
		$fh = fopen($this->uri, 'w');
		fwrite($fh, $this->expected_contents);
		fclose($fh);
		$this->assertFileExists($this->path);
		$this->assertEquals(filesize($this->uri), filesize($this->path));
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
		$perms 		 = fileperms($this->uri);
		$perms_octal = substr(sprintf('%o', $perms), -4);
		
		$this->assertType('int', $perms);
		
		$this->assertEquals($perms, fileperms($this->path));
		
		$this->assertTrue(wp_chmod($this->uri, 0000));
		clearstatcache();
		$this->assertNotEquals($perms, fileperms($this->uri));
		
		$cur_octal = substr(sprintf('%o', fileperms($this->uri)), -4);
		$this->assertEquals(0000, $cur_octal);
		$this->assertEquals(fileperms($this->uri), fileperms($this->path));
		
		$this->assertTrue(wp_chmod($this->uri, $perms));
		clearstatcache();
		$cur_octal = substr(sprintf('%o', fileperms($this->uri)), -4);
		$this->assertEquals($perms_octal, $cur_octal);
		$this->assertEquals(fileperms($this->uri), fileperms($this->path));
	}
	
	/**
	 * Tests getting cononical [absolute] path of file
	 *
	 * Tests wp_realpath()
	 */
	public function test_wp_realpath() {
		/**
		 * Test using URI
		 */
		$this->assertEquals($this->path, wp_realpath($this->uri));
		$this->assertFalse(wp_realpath('test://somedir/../'.$this->filename));
		
		/**
		 * Test using path
		 */
		$this->assertEquals($this->path, wp_realpath($this->path));
		$this->assertEquals($this->path, wp_realpath($this->test_dir.'/../stream_tests/'.$this->filename));
	}
	
	/**
	 * Teardown this test case
	 */
	function tearDown() {
		unlink($this->uri);
		$this->assertFileNotExists($this->uri);
		
		// Unregister test stream wrapper
		WP_Stream_Wrapper_Registry::unregister_wrapper('test');
	}
}

?>