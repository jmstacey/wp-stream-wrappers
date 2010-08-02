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
	 * Test directory path
	 *
	 * This variable holds the local path that the test wrapper is pointed to.
	 *
	 * @var string
	 * @access private
	 */
	private $test_dir;
	
	/**
	 * Somple file contents
	 *
	 * A basic sentence that can be used in test files.
	 *
	 * @var string
	 * @access private
	 */
	private $sample_content;
	
	/**
	 * Assert that the registry has the core attributes needed
	 */
	public function test_class_attributes() {
		$this->assertclassHasAttribute('context', 'WP_Local_Stream_Wrapper_Base');
		$this->assertclassHasAttribute('handle', 'WP_Local_Stream_Wrapper_Base');
		$this->assertclassHasAttribute('uri', 'WP_Local_Stream_Wrapper_Base');
	}
	
	/**
	 * Setup this test case
	 */
	function setUp() {
		do_action('init');
		
		// Register test stream wrapper
		wp_test_stream_wrapper_register();
		
		// Set the expected test directory
		$wrapper = WP_Stream::new_wrapper_instance('test://');
		$this->test_dir = $wrapper->get_wrapper_path();

		$this->sample_content = 'The miracle is this - the more we share, the more we have. -- Leonard Nimoy';
	}
	
	/**
	 * Tests creating directories
	 *
	 * Tests WP_Local_Stream_Wrapper_Base::mkdir()
	 */
	public function test_mkdir_and_rmdir() {
		/**
		 * Test creating a single directory
		 */
		$dir  = 'dir1';
		$uri  = 'test://'.$dir;
		$path = $this->test_dir.'/'.$dir;
		
		mkdir($uri);
		$this->assertFileExists($path);
		rmdir($uri);
		$this->assertFileNotExists($path);
		
		/**
		 * Test creating multiple directories recursively as needed
		 */
		$dir  = '/dir2/dir3/dir4';
		$uri  = 'test://' . $dir;
		$path = $this->test_dir.'/'.$dir;
		
		mkdir($uri, 0777, true);
		
		$error_tripped = false;
		$this->assertFileExists($path);
		try {
			$return = rmdir('test://dir2');
		}
		catch (PHPUnit_Framework_Error $e) {
			$error_tripped = true;
		}
		
		$this->assertTrue($error_tripped, "rmdir() on a non-empty directory should trigger an error.");
		$this->assertTrue(wp_rmdir_recursive('test://dir2'));
		$this->assertFileNotExists($this->test_dir.'/dir2');				
	}
	
	/**
	 * Tests creating files, writing to files, and reading from files,
	 * and deleting them again.
	 *
	 * Tests WP_Local_Stream_Wrapper_Base::fopen() and family
	 */
	public function test_basic_file_manipulations() {
		/**
		 * Test creating a file
		 */
		$filename = 'testfile.txt';
		$uri  = 'test://'.$filename;
		$path = $this->test_dir.'/'.$filename;
		$expected_contents = $this->sample_content;
		
		// Make sure the file doesn't exist to start with
		$this->assertFileNotExists($path);
		
		// // Touch the file
		// touch($uri);
		// $this->assertFileExists($path);
		// 
		// // Unlink the file
		// unlink($uri);
		// $this->assertFileNotExists($path);
		
		// Open and write to the file
		$fh = fopen($uri, 'w');
		fwrite($fh, $expected_contents);
		fclose($fh);
		$this->assertFileExists($path);
		$this->assertEquals(filesize($uri), filesize($path));
		
		// Open and read from the file
		$fh = fopen($uri, 'r');
		$actual_contents = fread($fh, filesize($uri));
		fclose($fh);
		$this->assertEquals($expected_contents, $actual_contents);
		
		// Copy the file
		$second_filename = 'testfile2.txt';
		$second_uri  = 'test://'.$second_filename;
		$second_path = $this->test_dir.'/'.$second_filename;
		
		copy($uri, $second_uri);
		$this->assertFileExists($this->test_dir.'/'.$second_filename);
		$this->assertEquals(filesize($uri), filesize($second_uri));
		
		// Unlink original file
		unlink($uri);
		$this->assertFileNotExists($path);
		
		// Rename second file to first file
		rename($second_uri, $uri);
		$this->assertFileNotExists($second_path);
		$this->assertfileExists($path);
		
		// Cleanup
		unlink($uri);
		$this->assertfileNotExists($path);	
	}
	
	/**
	 * @todo test touch() because it currently isn't working. It probably
	 * has something to do with the way stream wrapper implementation use
	 * realpath and the file doesn't exist.
	 */
	
	/**
	 * Tests PHP opendir() calls
	 *
	 * Tests WP_Local_Stream_Wrapper_Base::dir_opendir()
	 */
	public function test_opendir() {
		// @todo implement test_opendir()
		$this->markTestIncomplete('opendir() test has not been implemented yet.');
	}
	
	/**
	 * Tests PHP closedir() calls
	 *
	 * Tests WP_Local_Stream_Wrapper_Base::dir_closedir()
	 */
	public function test_closedir() {
		// @todo implement test_closedir()
		$this->markTestIncomplete('closedir() test has not been implemented yet.');
	}
	
	/**
	 * Tests PHP readdir() calls
	 *
	 * Tests WP_Local_Stream_Wrapper_Base::dir_readdir()
	 */
	public function test_readdir() {
		// @todo implement test_readdir()
		$this->markTestIncomplete('readdir() test has not been implemented yet.');
	}
	
	/**
	 * Tests PHP rewinddir() calls
	 *
	 * Tests WP_Local_Stream_Wrapper_Base::dir_rewinddir()
	 */
	public function test_rewinddir() {
		// @todo implement test_rewinddir()
		$this->markTestIncomplete('rewinddir() test has not been implemented yet.');
	}
	
	/**
	 * Tests PHP fseek() calls
	 *
	 * Tests WP_Local_Stream_Wrapper_Base::stream_seek()
	 */
	public function test_fseek() {
		// @todo implement test_fseek()
		$this->markTestIncomplete('fseek() test has not been implemented yet.');
	}
	
	/**
	 * Tests PHP ftell() calls
	 *
	 * Tests WP_Local_Stream_Wrapper_Base::stream_tell()
	 */
	public function test_ftell() {
		// @todo implement test_ftell()
		$this->markTestIncomplete('ftell() test has not been implemented yet.');
	}
	
	/**
	 * Tests PHP flock() calls
	 *
	 * Tests WP_Local_Stream_Wrapper_Base::stream_lock()
	 */
	public function test_flock() {
		$uri = 'test://lock_test.txt';
		
		$fp1 = fopen($uri, 'w+');
		$this->assertTrue(flock($fp1, LOCK_EX | LOCK_NB), "Couldn't acquire file lock.");
		fwrite($fp1, $this->sample_content);
		
		$fp2 = fopen($uri, 'r+');
		$this->assertFalse(flock($fp2, LOCK_EX | LOCK_NB), "We should not be able to acquire a lock here.");
		
		$this->assertTrue(flock($fp1, LOCK_UN), "Couldn't release file lock.");
		$this->assertTrue(flock($fp2, LOCK_EX | LOCK_NB), "We should be able to acquire a lock here.");
		$this->assertTrue(flock($fp2, LOCK_UN), "Couldn't release file lock.");
		
		fclose($fp1);
		fclose($fp2);
		
		unlink($uri);
		$this->assertFileNotExists($uri, "The lock_test.txt file should have been removed.");
	}
	
	/**
	 * Tests getting the web accessible URL of a file.
	 *
	 * Tests generic call routing of
	 * $wrapper_instance->get_web_accessible_uril() to the appropriate wrapper
	 * implementation. This tests calls to both the test:// and local://
	 * wrapper implementations. These functions are part of the wrapper
	 * interface and not directly implemented in the local stream wrapper
	 * base class.
	 */
	public function test_get_web_accessible_url() {
		/**
		 * Create a test file
		 */
		$filename = 'testfile.txt';
		$uri  = 'test://'.$filename;
		$path = $this->test_dir.'/'.$filename;
		$expected_contents = 'The miracle is this - the more we share, the more we have. -- Leonard Nimoy';
		
		// Make sure the file doesn't exist to start with
		$this->assertFileNotExists($path);
		
		// Open and write to the file
		$fh = fopen($uri, 'w');
		fwrite($fh, $expected_contents);
		fclose($fh);
		$this->assertFileExists($path);
		
		$expected = content_url().'/stream_tests/'.$filename;
		$actual   = WP_Stream::new_wrapper_instance($uri)->get_web_accessible_url();
		
		$this->assertEquals($expected, $actual);
		
		/**
		 * Test the WP Local Stream Wrapper Implmentation
		 */
		$filename = 'testfile.txt';
		$uri  = 'local://'.$filename;
		
		$expected = content_url().'/'.$filename;
		$actual   = WP_Stream::new_wrapper_instance($uri)->get_web_accessible_url();
	}
	
	/**
	 * Tests getting the wrapper path
	 *
	 * Tests generic call routing of
	 * $wrapper_instance->get_web_accessible_uril() to the appropriate wrapper
	 * implementation. This tests calls to both the test:// and local://
	 * wrapper implementations. These functions are part of the wrapper
	 * interface and not directly implemented in the local stream wrapper
	 * base class.
	 */
	public function test_get_wrapper_path() {
		/**
		 * Test test stream wrapper implementation
		 */
		$actual = WP_Stream::new_wrapper_instance('test://')->get_wrapper_path();
		$this->assertEquals($this->test_dir, $actual);
		
		/**
		 * Test local stream wrapper implementation
		 */
		$actual = WP_Stream::new_wrapper_instance('local://')->get_wrapper_path();
		$this->assertEquals(WP_CONTENT_DIR, $actual);
	}
	
	/**
	 * Test wrapper checking dependencies
	 *
	 * @todo Figure out how to test failure with WP Automated Tests
	 */
	public function test_local_stream_wrapper_dependency_check() {
		// At the moment this simply simulates the plugins loaded action
		// so that the local wrapper code coverage is counted
		do_action('plugins_loaded');
	}
	
	/**
	 * Tests setting and getting URI
	 *
	 * Tests the $uri attribute of 
	 */
	public function test_uri_attribute() {
		$uri 		= 'test://testfile.txt';
		$scheme 	= WP_Stream::scheme($uri);
		$class_name = WP_Stream::wrapper_class_name($scheme);
		
		$instance = WP_Stream::new_wrapper_instance($uri);
		$this->assertEquals($uri, $instance->get_uri());
		
		$new_uri = 'test://testfile2.txt';
		$instance->set_uri($new_uri);
		$this->assertEquals($new_uri, $instance->get_uri());
	}
	
	/**
	 * Teardown this test case
	 */
	function tearDown() {
		// Remove any remaining test files
		wp_rmdir_recursive('test://');
		
		// Unregister test stream wrapper
		WP_Stream_Wrapper_Registry::unregister_wrapper('test');
	}
}

?>