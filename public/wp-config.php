<?php
/**
 * The base configuration for WordPress.
 *
 * @package Dekode
 */

declare( strict_types = 1 );

// Application root directory.
$app_root = dirname( __DIR__ );

// Autoload Composer packages.
require_once $app_root . '/vendor/autoload.php';

// Load environment variables.
if ( file_exists( $app_root . '/.env' ) ) {
	$dotenv = new \Symfony\Component\Dotenv\Dotenv();
	$dotenv->load( $app_root . '/.env' );
}

/**
 * Get env variable with empty fallback.
 *
 * @param string $key Variable key.
 * @param mixed  $default Default value if key is not set.
 * @return mixed
 */
function env( string $key, $default = '' ) { // phpcs:ignore NeutronStandard.Functions.TypeHint.NoArgumentType, NeutronStandard.Functions.TypeHint.NoReturnType
	$value = $_ENV[ $key ] ?? $default;

	// Return bool value for 'true' or 'false'.
	if ( in_array( $value, [ 'true', 'false' ], true ) ) {
		return boolval( $value );
	}

	return $value;
}

// Conditionally use, or generate WP_HOME and WP_SITEURL.
if ( env( 'WP_HOME' ) ) {
	define( 'WP_HOME', env( 'WP_HOME' ) );
	define( 'WP_SITEURL', env( 'WP_SITEURL', WP_HOME ) );
} else {
	$http_host   = filter_input( INPUT_SERVER, 'HTTP_HOST', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
	$server_port = filter_input( INPUT_SERVER, 'SERVER_PORT', FILTER_SANITIZE_NUMBER_INT );
	$http_x_fp   = filter_input( INPUT_SERVER, 'HTTP_X_FORWARDED_PROTO', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
	$https       = filter_input( INPUT_SERVER, 'HTTPS', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
	$scheme      = 'http';

	if ( ( is_string( $https ) && 'on' === strtolower( $https ) ) || '443' === $server_port || 'https' === $http_x_fp ) {
		$scheme           = 'https';
		$_SERVER['HTTPS'] = 'on';
	}

	define( 'WP_HOME', $scheme . '://' . $http_host );
	define( 'WP_SITEURL', WP_HOME );
}

// Set custom content directory.
define( 'WP_CONTENT_DIR', $app_root . '/public/content' );
define( 'WP_CONTENT_URL', WP_HOME . '/content' );

// Database settings.
define( 'DB_HOST', env( 'DB_HOST', 'localhost' ) );
define( 'DB_NAME', env( 'DB_NAME', 'local' ) );
define( 'DB_USER', env( 'DB_USER', 'root' ) );
define( 'DB_PASSWORD', env( 'DB_PASSWORD', 'root' ) );
define( 'DB_CHARSET', env( 'DB_CHARSET', 'utf8mb4' ) );
define( 'DB_COLLATE', env( 'DB_COLLATE', '' ) );

// Database table prefix.
$table_prefix = env( 'DB_PREFIX', 'wp_' ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

// Authentication unique keys and salts.
define( 'AUTH_KEY', env( 'AUTH_KEY' ) );
define( 'SECURE_AUTH_KEY', env( 'SECURE_AUTH_KEY' ) );
define( 'LOGGED_IN_KEY', env( 'LOGGED_IN_KEY' ) );
define( 'NONCE_KEY', env( 'NONCE_KEY' ) );
define( 'AUTH_SALT', env( 'AUTH_SALT' ) );
define( 'SECURE_AUTH_SALT', env( 'SECURE_AUTH_SALT' ) );
define( 'LOGGED_IN_SALT', env( 'LOGGED_IN_SALT' ) );
define( 'NONCE_SALT', env( 'NONCE_SALT' ) );

// Debugging mode and environment type.
define( 'WP_DEBUG', env( 'WP_DEBUG', false ) );
define( 'WP_DEBUG_DISPLAY', env( 'WP_DEBUG_DISPLAY', false ) );
define( 'SCRIPT_DEBUG', env( 'SCRIPT_DEBUG', false ) );
define( 'SAVEQUERIES', env( 'SAVEQUERIES', false ) );
define( 'WP_ENVIRONMENT_TYPE', env( 'WP_ENVIRONMENT_TYPE', 'production' ) );

define( 'AUTOMATIC_UPDATER_DISABLED', true );
define( 'DISABLE_WP_CRON', env( 'DISABLE_WP_CRON', false ) );
define( 'DISALLOW_FILE_EDIT', env( 'DISALLOW_FILE_EDIT', true ) );
define( 'DISALLOW_FILE_MODS', env( 'DISALLOW_FILE_MODS', true ) );

// To make WP load each script on the administration page individually; protects against CVE-2018-6389 DoS attacks.
define( 'CONCATENATE_SCRIPTS', env( 'CONCATENATE_SCRIPTS', false ) );

// Disable Redis if the environment file decrees it so.
define( 'WP_REDIS_DISABLED', env( 'WP_REDIS_DISABLED', false ) );

if ( defined( 'WP_CLI' ) && WP_CLI && env( 'MYSQLI_DEFAULT_SOCKET' ) ) {
	ini_set( 'mysqli.default_socket', env( 'MYSQLI_DEFAULT_SOCKET' ) ); // phpcs:ignore
}

// Multisite.
define( 'WP_ALLOW_MULTISITE', env( 'WP_ALLOW_MULTISITE', false ) );
define( 'MULTISITE', env( 'MULTISITE', false ) );

if ( MULTISITE ) {
	define( 'SUBDOMAIN_INSTALL', env( 'SUBDOMAIN_INSTALL', false ) );
	define( 'DOMAIN_CURRENT_SITE', env( 'DOMAIN_CURRENT_SITE', parse_url( WP_HOME, PHP_URL_HOST ) ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.parse_url_parse_url
	define( 'PATH_CURRENT_SITE', '/' );
	define( 'SITE_ID_CURRENT_SITE', 1 );
	define( 'BLOG_ID_CURRENT_SITE', 1 );
}

if ( WP_DEBUG && ! empty( $_SERVER['DOCUMENT_ROOT'] ) ) {
	// If the document root can be determined, use it as the base for the logfile location.
	$document_root = filter_input( INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_STRING );

	/*
	 * Validate that the document root path has no path traversal strings as part of it,
	 * if not fallback to default logging locations.
	 */
	if ( ! empty( $document_root ) && true !== stristr( $document_root, '..' ) ) {
		define( 'WP_DEBUG_LOG', rtrim( dirname( $document_root ), '/' ) . '/logs/wp-debug.log' );
	}
}

// Proxy settings.
if ( env( 'WP_PROXY_HOST' ) ) {
	define( 'WP_PROXY_HOST', env( 'WP_PROXY_HOST' ) );
}

if ( env( 'WP_PROXY_PORT' ) ) {
	define( 'WP_PROXY_PORT', env( 'WP_PROXY_PORT' ) );
}

if ( env( 'WP_PROXY_BYPASS_HOSTS' ) ) {
	define( 'WP_PROXY_BYPASS_HOSTS', env( 'WP_PROXY_BYPASS_HOSTS' ) );
}

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', $app_root . 'public/wp/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . '/wp-settings.php';
