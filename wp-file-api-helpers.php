<?php
/**
 * This file contains WordPress File API helper functions.
 *
 * These helper functions are declared in the global namespace so that
 * they can be called in a similar manner to PHP's own functions.
 *
 * @package Stream Wrappers
 */

/**
 * Changes permissions of the resource
 *
 * PHP's own chmod() function has trouble with streams, so we implement
 * the correct behavior ourselves in this replacement helper function.
 *
 * This function is fully compatible with PHP's chmod() function and can be
 * called in the same way. For example, both a URI and a normal filepath
 * can be provided for the $uri argument.
 *
 * @param string $uri
 *   the stream URI, or a filepath to the file.
 * @param int $mode
 *   the octal number components specifying access restrictions. Refer
 *   to the PHP documentation for more information.
 *
 * @return bool
 *   true on success or false on failure.
 *
 * @link http://php.net/manual/en/function.chmod.php
 * @see 
 * @since 1.0.0
 */
function wp_chmod($uri, $mode = null) {
	/**
	 * Todo: Look into making these settings global, or maybe
	 * defineable by each individual stream wrapper for flexibility.
	 */
	if (!isset($mode)) {
		if (is_dir($uri)) {
			$mode = '0075';
		}
		else {
			$mode = '0664';
		}
	}

	if ($wrapper = WP_Stream::new_wrapper_instance($uri)) {
		if ($wrapper->chmod($mode)) {
			return true;
		}
	}
	else {
		if (@chmod($uri, $mode)) {
			return true;
		}
	}
	
	return false;
}

?>