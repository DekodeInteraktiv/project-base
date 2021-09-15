<?php
/**
 * Application base config.
 *
 * @package Dekode
 */

declare( strict_types = 1 );

/**
 * Directory containing all of the site's files.
 *
 * @var string
 */
$root_dir = dirname( __DIR__ );

/**
 * Document Root
 *
 * @var string
 */
$webroot_dir = $root_dir . '/public';

/**
 * Get env variable.
 *
 * @param string $key Variable key.
 * @return string
 */
function env( string $key ) : string {
	return $_ENV[ $key ] ?? '';
}

/**
 * Load environment variables
 */
$dotenv = new \Symfony\Component\Dotenv\Dotenv();
$dotenv->load( $root_dir . '/.env' );

/**
 * Set up our global environment constant
 *
 * By doing so, we ensure that plugins and themes relying on the environment data
 * provided by WordPress core behave as expected depending on the applicable sitaution.
 *
 * Default: production
 */
if ( ! defined( 'WP_ENVIRONMENT_TYPE' ) ) {
	define( 'WP_ENVIRONMENT_TYPE', env( 'WP_ENVIRONMENT_TYPE' ) ?: 'production' );
}

/**
 * Load enviroment config
 */
$env_config = __DIR__ . '/environments/' . WP_ENVIRONMENT_TYPE . '.php';

if ( file_exists( $env_config ) ) {
	require_once $env_config;
}

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
	define( 'WP_SITEURL', ( env( 'WP_SITEURL' ) ?: WP_HOME . '/wp' ) );
} else {
	$scheme = 'http';
	if ( ( is_string( $https ) && 'on' === strtolower( $https ) ) || '443' === $server_port || 'https' === $http_x_fp ) {
		$scheme           = 'https';
		$_SERVER['HTTPS'] = 'on';
	}

	define( 'WP_HOME', $scheme . '://' . $http_host );
	define( 'WP_SITEURL', WP_HOME . '/wp' );
}
/**
 * Set the dirs
 */
define( 'CONTENT_DIR', '/content' );
define( 'WP_CONTENT_DIR', $webroot_dir . CONTENT_DIR );
define( 'WP_CONTENT_URL', WP_HOME . CONTENT_DIR );

if ( ! empty( $_SERVER['DOCUMENT_ROOT'] ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- Code runs before WordPress is initialized.
	// If the document root can be determined, use it as the base for the logfile location.
	$document_root = filter_input( INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_STRING );

	/*
	 * Validate that the document root path has no path traversal strings as part of it,
	 * if not fallback to default logging locations.
	 */
	if ( empty( $document_root ) || stristr( $document_root, '..' ) ) {
		define( 'WP_DEBUG_LOG', true );
	} else {
		define( 'WP_DEBUG_LOG', rtrim( dirname( $document_root ), '/' ) . '/logs/wp-debug.log' );
	}
} else {
	// Fallback to enable debug logging to WordPress' default location.
	define( 'WP_DEBUG_LOG', true );
}

/**
 * DB settings
 */
define( 'DB_NAME', env( 'DB_NAME' ) );
define( 'DB_USER', env( 'DB_USER' ) );
define( 'DB_PASSWORD', env( 'DB_PASSWORD' ) );
define( 'DB_HOST', env( 'DB_HOST' ) ?: 'localhost' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
$table_prefix = env( 'DB_PREFIX' ) ?: 'wp_';

/**
 * Disable Redis if the environment file decrees it so.
 */
if ( env( 'WP_REDIS_DISABLED' ) && 'true' === env( 'WP_REDIS_DISABLED' ) ) {
	define( 'WP_REDIS_DISABLED', true );
}

/**
 * Authentication Unique Keys and Salts
 */
define( 'AUTH_KEY', env( 'AUTH_KEY' ) );
define( 'SECURE_AUTH_KEY', env( 'SECURE_AUTH_KEY' ) );
define( 'LOGGED_IN_KEY', env( 'LOGGED_IN_KEY' ) );
define( 'NONCE_KEY', env( 'NONCE_KEY' ) );
define( 'AUTH_SALT', env( 'AUTH_SALT' ) );
define( 'SECURE_AUTH_SALT', env( 'SECURE_AUTH_SALT' ) );
define( 'LOGGED_IN_SALT', env( 'LOGGED_IN_SALT' ) );
define( 'NONCE_SALT', env( 'NONCE_SALT' ) );

/**
 * Custom Settings
 */
define( 'AUTOMATIC_UPDATER_DISABLED', true );
define( 'DISABLE_WP_CRON', env( 'DISABLE_WP_CRON' ) ?: false );
define( 'DISALLOW_FILE_EDIT', true );

if ( env( 'WP_ALLOW_MULTISITE' ) ) {
	define( 'WP_ALLOW_MULTISITE', true );
	define( 'MULTISITE', true );
	define( 'SUBDOMAIN_INSTALL', filter_var( env( 'SUBDOMAIN_INSTALL' ), FILTER_VALIDATE_BOOLEAN ) ?: false );
	// phpcs:ignore WordPress.WP.AlternativeFunctions.parse_url_parse_url
	define( 'DOMAIN_CURRENT_SITE', env( 'DOMAIN_CURRENT_SITE' ) ?: parse_url( WP_HOME, PHP_URL_HOST ) );
	define( 'PATH_CURRENT_SITE', '/' );
	define( 'SITE_ID_CURRENT_SITE', 1 );
	define( 'BLOG_ID_CURRENT_SITE', 1 );
}

/**
 * To make WP load each script on the administration page individually; protects against CVE-2018-6389 DoS attacks
 */
define( 'CONCATENATE_SCRIPTS', env( 'CONCATENATE_SCRIPTS' ) ?: false );

/**
 * Bootstrap WordPress
 */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', $webroot_dir . '/wp/' );
}

/**
 * Check required constants.
 */
$required_constants = [
	'DB_HOST',
	'DB_NAME',
	'DB_USER',
	'DB_PASSWORD',
];

foreach ( $required_constants as $constant ) {
	if ( ! defined( $constant ) ) {
		die( "Please define: $constant in your .env file" ); // phpcs:ignore
	}
}
