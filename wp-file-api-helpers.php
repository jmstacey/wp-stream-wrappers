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
 * can be provided for the $uri parameter.
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
			$mode = '0775';
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

/**
 * Returns the canonical [absolute] path of the resource
 *
 * PHP's realpath() does not support stream wrappers. This helper function
 * properly adds this missing support.
 *
 * This function is fully compatible with PHP's realpath() function and can be
 * called in the same way. For example, both a URI and a normal filepath
 * can be provided for the $uri parameter.
 *
 * @param string $uri
 *   the URI or filepath from which to obtain the absolute pathname.
 *
 * @return mixed
 *   the canonicalized [absolute] pathname on success, or false on failure
 *   such as when the file does not exist.
 *
 * @link http://php.net/manual/en/function.realpath.php
 * @see realpath()
 * @see WP_Stream_Wrapper_Interface::realpath()
 * @since 1.0.0
 */
function wp_realpath($uri) {
	if ($wrapper = WP_Stream::new_wrapper_instance($uri)) {
		return $wrapper->realpath();
	}
	elseif (!empty($uri)) {
		return realpath($uri);
	}
	
	return false;
}

/**
 * Creates a file with a unique name
 *
 * PHP's tempnam() does not support stream wrappers. This helper function
 * properly adds this missing support.
 *
 * This function is fully compatible with PHP's tempnam() function and may be
 * called in the same way. For example, both a URI and a normal filepath
 * can be provided for the $uri parameter.
 *
 * Note: The function name used is temporary until the conflict with
 * wp_tempnam() in wp-admin/includes/file.php can be resolved.
 *
 * @param string $directory
 *   the directory where the temporary filename will be created.
 * @param string $prefix
 *   the prefix of the generated emporary filename.
 *   Note: Windows uses only the first three characters of $prefix
 *
 * @return mixed
 *   the new temporary filename, or false on failure.
 *
 * @link http://php.net/manual/en/function.tempnam.php
 * @see tempnam()
 * @since 1.0.0
 */
function wp_tempnam_stream_compatible($directory, $prefix) {
	/**
	 * NOTE: This is a temporary function name. This function should
	 * be named wp_tempnam(), but that currently conflicts with an existing
	 * declaration in wp-admin/includes/file.php.
	 *
	 * @todo Merge the two functions.
	 */
	$scheme = WP_Stream::scheme($directory);
	
	if ($scheme && WP_Stream::scheme_valid($scheme)) {
		$wrapper = WP_Stream::new_wrapper_instance($scheme . '://');
		
		if ($filename = tempnam($wrapper->get_wrapper_path(), $prefix)) {
			return $scheme . '://' . basename($filename);
		}
		else {
			return false;
		}
	}
	else {
		return tempnam($directory, $prefix);
	}
}

/**
 * Returns directory name component of path
 *
 * PHP's dirname() does not support stream wrappers. This helper function
 * properly adds this missing support.
 *
 * This function is fully compatible with PHP's dirname() function and may be
 * called in the same way. For example, both a URI and a normal filepath
 * can be provided for the $uri parameter.
 *
 * @param string $uri
 *   the URI or path to file.
 *
 * @return string
 *   the new temporary filename, or false on failure.
 *
 * @link http://us2.php.net/manual/en/function.dirname.php
 * @see dirname()
 * @since 1.0.0
 */
function wp_dirname($uri) {
	$scheme = WP_Stream::scheme($uri);
	
	if ($scheme && WP_Stream::scheme_valid($scheme)) {
		return WP_Stream::new_wrapper_instance($scheme . '://')->dirname($uri);
	}
	else {
		return dirname($uri);
	}
}
	
/**
 * Removes directory recursively
 *
 * IMPORTANT: This function is experimental and has not been tested.
 *
 * Attempts to remove the given directory recursively. Unlike PHP's rmdir()
 * which only removes the directory if it's empty, wp_rmdir_recursive() will
 * try to recursively delete all files and directories. Essentially this is
 * equivalent to a "rm -rf" command, or a forced rmdir() if you will.
 *
 * @param string $uri
 *   the URI or path to file.
 *
 * @return bool
 *   true on success or false on failure.
 *
 * @link http://us2.php.net/manual/en/function.rmdir.php
 * @see rmdir()
 * @since 1.0.0
 */
function wp_rmdir_recursive($uri) {
	$path = wp_realpath($uri);
	$path = rtrim($path, "/");
	
	$objects = glob($path . '/*', GLOB_MARK);
	foreach($objects as $object) {
		if (is_dir($object)) {
			wp_rmdir_recursive($object);
		}
		else {
			unlink($object);
		}
	}
	
	if (is_dir($path)) {
		return rmdir($path);
	}
	
	return true;
}

?>