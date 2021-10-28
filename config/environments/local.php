<?php
/**
 * Application local config.
 *
 * @package Dekode
 */

declare( strict_types = 1 );

define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', true );
# phpcs:ignore define( 'SCRIPT_DEBUG', true );
# phpcs:ignore define( 'SAVEQUERIES', true );

if ( defined( 'WP_CLI' ) && WP_CLI && env( 'MYSQLI_DEFAULT_SOCKET' ) ) {
	ini_set( 'mysqli.default_socket', env( 'MYSQLI_DEFAULT_SOCKET' ) ); // phpcs:ignore
}
