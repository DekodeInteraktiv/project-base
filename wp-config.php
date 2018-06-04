<?php
/**
 * Base WordPress config
 *
 * @package Base
 */

$http_host   = filter_input( INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_STRING );
$server_port = filter_input( INPUT_SERVER, 'SERVER_PORT', FILTER_SANITIZE_STRING );
$http_x_fp   = filter_input( INPUT_SERVER, 'HTTP_X_FORWARDED_PROTO', FILTER_SANITIZE_STRING );
$https       = filter_input( INPUT_SERVER, 'HTTPS', FILTER_SANITIZE_STRING );

if ( file_exists( __DIR__ . '/local-config.php' ) ) {
	include __DIR__ . '/local-config.php';
} elseif ( false !== strpos( $http_host, 'stage' ) || false !== strpos( $http_host, '.dev02.' ) ) {
	if ( file_exists( __DIR__ . '/stage-config.php' ) ) {
		include __DIR__ . '/stage-config.php';
	} else {
		die( 'Missing stage config file.' );
	}
} else {
	if ( file_exists( __DIR__ . '/prod-config.php' ) ) {
		include __DIR__ . '/prod-config.php';
	} else {
		die( 'Missing prod config file.' );
	}
}

/**
 * Set the custom content directory
 */
$scheme = defined( 'DEKODE_SCHEME' ) ? DEKODE_SCHEME : 'http';
if ( 'on' === strtolower( $https ) || '443' === $server_port || 'https' === $http_x_fp ) {
	$scheme           = 'https';
	$_SERVER['HTTPS'] = 'on';
}


define( 'WP_CONTENT_DIR', __DIR__ . '/content' );
define( 'WP_CONTENT_URL', $scheme . '://' . $http_host . '/content' );

if ( ! defined( 'WP_SITEURL' ) ) {
	define( 'WP_SITEURL', $scheme . '://' . $http_host . '/wp' );
}

if ( ! defined( 'WP_HOME' ) ) {
	define( 'WP_HOME', $scheme . '://' . $http_host );
}

/**
 * Hide Sucuri ads
 */
if ( ! defined( 'SUCURISCAN_HIDE_ADS' ) ) {
	define( 'SUCURISCAN_HIDE_ADS', true );
}

/**
 * You almost certainly do not want to change these
 */
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

/**
 * Salts, for security
 * Grab these from: https://api.wordpress.org/secret-key/1.1/salt
 * Must be unqiue on every project
 */
define( 'AUTH_KEY', '' );
define( 'SECURE_AUTH_KEY', '' );
define( 'LOGGED_IN_KEY', '' );
define( 'NONCE_KEY', '' );
define( 'AUTH_SALT', '' );
define( 'SECURE_AUTH_SALT', '' );
define( 'LOGGED_IN_SALT', '' );
define( 'NONCE_SALT', '' );

if ( ! strlen( AUTH_KEY ) ) {
	echo '<h1>Hold on!</h1>';
	echo '<p>The first thing you have to do now, is to go to <a href="https://api.wordpress.org/secret-key/1.1/salt">https://api.wordpress.org/secret-key/1.1/salt</a> to grab some new salts.</p>';
	echo '<p>Paste the entire output in wp-config.php. You should easily see where.</p>';
	exit;
}

/**
 * Table prefix
 */
$table_prefix = '';

if ( ! strlen( $table_prefix ) ) {
	echo '<h1>Hold on!</h1><p>You need to edit <code style="color: #c00;">wp-config.php</code>:<br>See if you can find a line that looks like <code style="color: #c00;">$table_prefix = \'\';</code> in there.<br>Set this to something unique for this project, like <code style="color: #c00;">wp_</code></p>';
	exit;
}

/**
 * Hide errors
 */
if ( ! defined( 'WP_DEBUG_DISPLAY' ) ) {
	define( 'WP_DEBUG_DISPLAY', false );
}

/**
 * Absolute path to the WordPress directory
 */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/wp/' );
}

/**
 * WP CLI
 */
if ( ! defined( 'WP_CLI' ) ) {
	require_once ABSPATH . 'wp-settings.php';
}
