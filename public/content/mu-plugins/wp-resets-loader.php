<?php
/**
 * Autoloader for Dekode WP Resets
 *
 * @package Dekode
 */

declare( strict_types = 1 );

if ( file_exists( trailingslashit( __DIR__ ) . 'wp-resets/resets.php' ) ) {
	require trailingslashit( __DIR__ ) . 'wp-resets/resets.php';
}
