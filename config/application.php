<?php
/**
 * Application base config.
 *
 * @package Dekode
 */

declare( strict_types = 1 );

$http_host   = filter_input( INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_STRING );
$server_port = filter_input( INPUT_SERVER, 'SERVER_PORT', FILTER_SANITIZE_STRING );
$http_x_fp   = filter_input( INPUT_SERVER, 'HTTP_X_FORWARDED_PROTO', FILTER_SANITIZE_STRING );
$https       = filter_input( INPUT_SERVER, 'HTTPS', FILTER_SANITIZE_STRING );

if ( defined( 'WP_CLI' ) && WP_CLI && ! isset( $_SERVER['HTTP_HOST'] ) ) {
	$http_host = env( 'WP_CLI_HOME' );
}

/**
 * Conditionally use, or generate, `WP_HOME` and `WP_SITEURL`.
 */
if ( env( 'WP_HOME' ) ) {
	define( 'WP_HOME', env( 'WP_HOME' ) );
	define( 'WP_SITEURL', ( env( 'WP_SITEURL' ) ?: WP_HOME ) );
} else {
	$scheme = 'http';
	if ( ( is_string( $https ) && 'on' === strtolower( $https ) ) || '443' === $server_port || 'https' === $http_x_fp ) {
		$scheme           = 'https';
		$_SERVER['HTTPS'] = 'on';
	}

	define( 'WP_HOME', $scheme . '://' . $http_host );
	define( 'WP_SITEURL', WP_HOME );
}
