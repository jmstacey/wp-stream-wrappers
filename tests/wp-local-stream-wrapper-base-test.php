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
	 * Setup this test case
	 */
	function setUp() {
		// Register test stream wrapper
		wp_test_stream_wrapper_register();
		
		// Set the expected test directory
		$wrapper = WP_Stream::new_wrapper_instance('test://');
		$this->test_dir = $wrapper->get_wrapper_path();
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
	public function test_file_manipulations() {
		/**
		 * Test creating a file
		 */
		$filename = 'testfile.txt';
		$uri  = 'test://'.$filename;
		$path = $this->test_dir.'/'.$filename;
		$expected_contents = 'The miracle is this - the more we share, the more we have. -- Leonard Nimoy';
		
		// Make sure the file doesn't exist to start with
		$this->assertFileNotExists($path);
		
		// Touch the file
		touch($uri);
		$this->assertFileExists($path);
		
		// Unlink the file
		unlink($uri);
		$this->assertFileNotExists($path);
		
		// Open and write to the file
		$fh = fopen($uri, 'w');
		$fwrite($fh, $expected_contents);
		fclose($fh);
		$this->assertFileExists($path);
		$this->assertEquals(filesize($uri), filesize($path));
		
		// Open and read from the file
		$fh = fopen($uri, 'r');
		$actual_contents = fread($fh, filesize($uri));
		close($fh);
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
	 * Teardown this test case
	 */
	function tearDown() {
		// Unregister test stream wrapper
		WP_Stream_Wrapper_Registry::unregister_wrapper('test');
	}
}

?>