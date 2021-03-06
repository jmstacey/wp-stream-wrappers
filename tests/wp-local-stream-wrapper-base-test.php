<?php
/**
 * Tests WordPress Local Stream Wrapper Base
 *
 * @package     WP_Stream_Wrappers
 * @author      Jon Stacey <jon@jonsview.com>
 * @copyright   2010 Jon Stacey
 * @license     http://wordpress.org/about/gpl/
 * @link        http://github.com/jmstacey/wp-stream-wrappers
 * @version     1.0.0
 * @since       1.0.0
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
 * @package    WP_Stream_Wrappers
 * @author     Jon Stacey <jon@jonsview.com>
 * @version    1.0.0
 * @see        WP_Local_Stream_Wrapper_Base
 * @since      1.0.0
 */
class WP_Local_Stream_Wrapper_Base_Test extends WPTestCase {
    /**
     * Test directory path
     *
     * This variable holds the local path that the test wrapper is pointed to.
     *
     * @var     string
     * @access  private
     */
    private $test_dir;

    /**
     * Somple file
     *
     * A simple file for read-only purpose tests
     *
     * @var     string
     * @access  private
     */
    private $sample_file;

    /**
     * Somple file contents
     *
     * A basic sentence that can be used in test files.
     *
     * @var     string
     * @access  private
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

        /**
         * Prepare sample file with sample contents
         */
        $this->sample_file = 'test://sample_file.txt';

        $fp = fopen($this->sample_file, 'w');
        fwrite($fp, $this->sample_content);
        fclose($fp);
        $this->assertFileExists($this->sample_file);
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
        $dir  = 'dir2/dir3/dir4';
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

        // Touch the file
        wp_touch($uri);
        $this->assertFileExists($path);

        // Unlink the file
        unlink($uri);
        $this->assertFileNotExists($path);

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
     * Tests PHP opendir(), readdir(), and rewinddir() calls
     *
     * Tests WP_Local_Stream_Wrapper_Base::dir_opendir()
     * Tests WP_Local_Stream_Wrapper_Base::dir_closedir()
     * Tests WP_Local_Stream_Wrapper_Base::dir_readdir()
     */
    public function test_opendir_family() {
        $uri = 'test://opendir_test/dir1/dir2/dir3/dir4/dir5';

        mkdir($uri, 0777, true);
        // $this->assertTrue(mkdir($uri), 0777, true); // Whoa! This does not work for some reason
        $this->assertTrue(is_dir('test://opendir_test'));
        $this->assertFileExists($uri);

        $this->assertThat($dh = opendir('test://opendir_test/dir1/dir2'),
                        $this->logicalNot(
                            $this->EqualTo(false)
                        )
        );

        $dirs = array();
        while (($d = readdir($dh)) !== false) {
            array_push($dirs, $d);
        }

        $this->assertEquals(3, count($dirs));  // Expecing: '.', '..', and 'dir3'
        $this->assertEquals('dir3', $dirs[2]); // $dir[2] should be 'dir3

        /**
         * Test rewinddir() call
         */
        $dirs = array();
        while (($d = readdir($dh)) !== false) {
            array_push($dirs, $d);
        }

        $this->assertEquals(0, count($dirs));  // Expecing: '.', '..', and 'dir3'

        rewinddir($dh);

        $dirs = array();
        while (($d = readdir($dh)) !== false) {
            array_push($dirs, $d);
        }

        $this->assertEquals(3, count($dirs));  // Expecing: '.', '..', and 'dir3'
        $this->assertEquals('dir3', $dirs[2]); // $dir[2] should be 'dir3

        closedir($dh);
    }

    /**
     * Tests PHP fseek() calls
     *
     * Tests WP_Local_Stream_Wrapper_Base::stream_seek()
     */
    public function test_fseek() {
        $uri = $this->sample_file;

        $fp   = fopen($uri, 'r');
        $data = fgets($fp, 5);
        $this->assertEquals(4, ftell($fp), "Something messed up in fgets() or ftell().");

        $this->assertEquals(0, fseek($fp, 0));

        fclose($fp);
    }

    /**
     * Tests PHP ftell() calls
     *
     * Tests WP_Local_Stream_Wrapper_Base::stream_tell()
     */
    public function test_ftell() {
        $uri = $this->sample_file;

        $fp   = fopen($uri, 'r');
        $data = fgets($fp, 12);

        $this->assertEquals(11, ftell($fp));
        fclose($fp);
    }

    /**
     * Tests PHP flock() calls
     *
     * Tests WP_Local_Stream_Wrapper_Base::stream_lock()
     */
    public function test_flock() {
        $uri = $this->sample_file;

        $fp1 = fopen($uri, 'r+');
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
     * Tests PHP stream_select() calls
     *
     * Tests WP_Local_Stream_Wrapper_Base::stream_cast()
     *
     * stream_cast() is not implemented so we expect an exception in this test.
     */
    public function test_stream_select() {
        $stream = fopen($this->sample_file, 'r');
        $read   = array($stream);
        $write  = null;
        $except = null;

        $this->setExpectedException('PHPUnit_Framework_Error');
        $this->assertFalse(stream_select($read, $write, $except, $timeout));
    }

    /**
     * Tests PHP stream_set_option() calls
     *
     * Tests WP_Local_Stream_Wrapper_Base::stream_set_option()
     *
     * Note: stream_set_option() is not implemented so we expect an exception in this test.
     */
    public function test_stream_set_option() {
        $instance = WP_Stream::new_wrapper_instance('test://');

        $this->assertThat($instance,
                        $this->logicalNot(
                            $this->EqualTo(false)
                        )
        );

        $this->setExpectedException('PHPUnit_Framework_Error');
        $this->assertFalse($instance->stream_set_option(null, null, null));
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
        $uri        = 'test://testfile.txt';
        $scheme     = WP_Stream::scheme($uri);
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