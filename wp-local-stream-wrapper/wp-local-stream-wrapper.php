<?php
/**
 * WordPress Local Stream Wrapper
 *
 * This file contains the local stream wrapper which implements the
 * "local" scheme. This is a simple and complete stream wrapper
 * for testing, reference, and extension purposes. Other stream wrappers
 * that manipulate files on the local filesystem can quickly extend this class
 * to suit more specific needs. The WP Test [test://] stream wrapper is an
 * excellent example of this use case.
 *
 * Developers: If you are using this wrapper as a tempalte for a new wrapper
 * and intend to extend WP_Local_Stream_Wrapper_Base you are responsible for
 * dependency checks. That is, if a user loads your wrapper before having
 * installed and activated the WP Stream Wrappers plugin, show an
 * error message to the admin user and avoid causing a PHP error by trying to
 * extend a non-existent class. See the implementation in this file for one
 * way to handle this problem in a consistent manner.
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
 * Version of WP Local Stream Wrapper
 */
define('WP_LOCAL_STREAM_WRAPPER_VERSION', '1.0.0');

/**
 * Registers the WP Local Stream Wrapper
 *
 * Information about this stream wrapper:
 *
 * Scheme     : local
 * Class      : WP_Local_Stream_Wrapper
 * Description: WP Local Stream Wrapper provides a simple example of
 *              leveraging the WP_Local_Stream_Wrapper_Base class to create
 *              simple wrappers capable of manipulating local files.
 *
 * @since   1.0.0
 */
function wp_local_stream_wrapper_register() {
    $scheme = 'local'; // Wrapper scheme

    // Wrapper registration metadata
    $wrapper_metadata = array(
        'name' => 'WP Local Stream Wrapper',
        'class' => 'WP_Local_Stream_Wrapper',
        'description' => 'WP Local Stream Wrapper provides a simple example of leveraging the WP_Local_Stream_Wrapper_Base class to create simple wrappers capable of manipulating local files.'
    );

    /**
     * This file contains the WP Local Stream wrapper implementation
     */
    require_once('wp-local-stream-wrapper-class.php');

    // Register this wrapper
    WP_Stream_Wrapper_Registry::register_wrapper($scheme, $wrapper_metadata);
}

/**
 * Checks plugin dependencies
 *
 * Note: This particular wrapper does not truly require this because
 * it is part of the core plugin. However, it is included here to serve
 * as an example to other developers creating separate wrapper plugins.
 *
 * @since   1.0.0
 */
function wp_local_stream_wrapper_dependency_check() {
    if (!has_action('register_stream_wrapper')) {
        // @todo: figure out how to test with WP Automated Tests

        // Notify the admin that the stream wrappers plugin is needed
        add_action('admin_notices', 'wp_local_stream_wrapper_show_error');

        return new WP_Error('stream-wrapper-dependency-error', __("The WP Stream Wrappers Plugin is required, but is not installed."));
    }
}

/**
 * Shows dependency error message to admin users
 *
 * A message is displayed to admin users about the requirement of
 * the WP Stram Wrappers Plugin.
 *
 * @since   1.0.0
 */
function wp_local_stream_wrapper_show_error() {
    // @todo: Update href to a useful page in the documentation
    print('<div id="message" class="error">Unable to register the WP Local Stream Wrapper (local://). The WP Stream Wrappers plugin is required. <a href="http://wiki.github.com/jmstacey/wp-stream-wrappers/unable-to-register-wrapper-error">More info...</a></div>');
}

/**
 * Register action hooks
 */
add_action('register_stream_wrapper', 'wp_local_stream_wrapper_register', 1, 0);
add_action('plugins_loaded', 'wp_local_stream_wrapper_dependency_check');

?>