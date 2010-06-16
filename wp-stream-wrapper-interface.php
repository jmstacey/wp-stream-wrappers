<?php
/**
 * This file contains the WordPress Stream Wrapper Interface. This interface
 * must be implemented [extended] by any stream wrappers that wish to interact
 * with the WordPress Stream Wrappers plugin.
 *
 * A stream is simply an abstraction of any kind of data. For example, a
 * stream could be a local file on the hard drive, a web page, or even
 * input from STDIN.
 *
 * IMPORTANT: PHP 5 fopen() only supports URIs in the form of
 * "scheme://target" despite the fact that according to RFC 3986 a URI's
 * scheme component delimiter is in general just ":", and not "://".
 * As a result of this PHP limitation and for consitency WordPress will
 * will only accept URIs in full form (i.e. "scheme://target").
 *
 * @link http://www.faqs.org/rfcs/rfc3986.html
 * @link http://bugs.php.net/bug.php?id=47070
 * @link http://us3.php.net/manual/en/function.fopen.php
 *
 * @package Stream Wrappers
 */



?>