<?php
/**
 * This file contains the Local WordPress Stream Wrapper implementation
 * for the "local://" scheme. This is a simple and complete stream wrapper
 * implementation for use and reference purposes.
 *
 * @package Stream Wrappers
 */

/**
 * WordPress local (local://) stream wrapper class
 *
 * A simple stream wrapper that implements access to files on the local
 * filesystem. Additionally, this wrapper provides a simple example of
 * how to build a custom wrapper on top of the
 * WP_Local_Stream_Wrapper_Base class.
 *
 * @package    Stream Wrappers
 * @author     Jon Stacey <jon@jonsview.com>
 * @version    1.0.0
 * @link
 * @see        WP_Local_Stream_Wrapper_Base
 * @since      1.0.0
 */
class WP_Local_Stream_Wrapper extends WP_Local_Stream_Wrapper_Base {
    /**
     * Implements WP_Stream_Wrapper_Base::get_wrapper_path()
     *
     * Retrieves the path that this wrapper is responsible for. This allows
     * with minimal development effor the addition and customization of
     * wrappers specific to different local filesystem locations.
     *
     * @return string
     *   the path that this wrapper is responsible for.
     *
     * @see WP_Local_Stream_Wrapper_Base::get_wrapper_path()
     * @since 1.0.0
     */
    public function get_wrapper_path() {
        /**
         * TODO: consider making this path configurable through the
         * administration area.
         */
        return WP_CONTENT_DIR;
    }

    /**
     * Implements WP_Stream_Wrapper_Interface::get_web_accessible_url()
     *
     * Returns a URL that can be accessed from a browser. For example,
     * the web URL of the internal URI "local://example.txt" might be
     * "http://www.example.com/wp-content/example.txt".
     *
     * @return string
     *   the web accessible URL for the resource.
     *
     * @see WP_Local_Stream_Wrapper_Interface::get_web_accessible_url()
     * @since 1.0.0
     */
    public function get_web_accessible_url() {
        $path = str_replace('\\', '/', WP_Stream::target($this->uri));

        return content_url($path);
    }
}

?>