<?php
/**
 * Autoloader for Dekode Resets
 *
 * @package Dekode
 */

declare( strict_types = 1 );

if ( file_exists( trailingslashit( __DIR__ ) . 'resets/resets.php' ) ) {
	require trailingslashit( __DIR__ ) . 'resets/resets.php';
}
