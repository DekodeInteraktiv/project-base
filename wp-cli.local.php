<?php
/**
 * The Local WP CLI configuration.
 *
 * @package Dekode
 */

declare( strict_types = 1 );

// Application root directory.
$app_root = __DIR__;

// Autoload Composer packages.
require_once $app_root . '/vendor/autoload.php';

// Load environment variables.
if ( file_exists( $app_root . '/.env' ) ) {
	$dotenv = new \Symfony\Component\Dotenv\Dotenv();
	$dotenv->load( $app_root . '/.env' );
}

define( 'DB_USER', $_ENV['DB_USER'] ?? 'root' );
define( 'DB_PASSWORD', $_ENV['DB_PASSWORD'] ?? 'root' );

// Only display fatal run-time errors.
// See http://php.net/manual/en/errorfunc.constants.php.
error_reporting( E_ERROR );

// Disable WordPress debug mode.
// See https://codex.wordpress.org/WP_DEBUG.
define( 'WP_DEBUG', false );
