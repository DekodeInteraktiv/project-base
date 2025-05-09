<?php
/**
 * Theme functions, definitions and setup
 *
 * @package BlockTheme
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || exit;

// Autoload package Composer dependencies.
if ( \file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

// Require all files in includes folder.
\array_map( fn( $f ) => require_once $f, \glob( __DIR__ . '/includes/*.php' ) );

// Require all block.php files in build/ folder.
\array_map( fn( $f ) => require_once $f, \glob( __DIR__ . '/build/*/block.php' ) );
