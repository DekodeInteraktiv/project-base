<?php
/**
 * The base configuration for WordPress.
 * This file will be copied into /public by Composer after WordPress is installed.
 *
 * @package Dekode
 */

declare( strict_types = 1 );

/* Application root directory. */
$app_root = dirname( __DIR__ );

/* Autoload Composer packages. */
require_once $app_root . '/vendor/autoload.php';

/* Load environment variables */
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

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', env( 'DB_NAME', 'local' ) );

/** Database username */
define( 'DB_USER', env( 'DB_USER', 'root' ) );

/** Database password */
define( 'DB_PASSWORD', env( 'DB_PASSWORD', 'root' ) );

/** Database hostname */
define( 'DB_HOST', env( 'DB_HOST', 'localhost' ) );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', env( 'DB_CHARSET', 'utf8mb4' ) );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', env( 'DB_COLLATE', '' ) );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY', env( 'AUTH_KEY' ) );
define( 'SECURE_AUTH_KEY', env( 'SECURE_AUTH_KEY' ) );
define( 'LOGGED_IN_KEY', env( 'LOGGED_IN_KEY' ) );
define( 'NONCE_KEY', env( 'NONCE_KEY' ) );
define( 'AUTH_SALT', env( 'AUTH_SALT' ) );
define( 'SECURE_AUTH_SALT', env( 'SECURE_AUTH_SALT' ) );
define( 'LOGGED_IN_SALT', env( 'LOGGED_IN_SALT' ) );
define( 'NONCE_SALT', env( 'NONCE_SALT' ) );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = env( 'DB_PREFIX', 'wp_' ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', env( 'WP_DEBUG', false ) );
define( 'WP_DEBUG_DISPLAY', env( 'WP_DEBUG_DISPLAY', false ) );
define( 'SCRIPT_DEBUG', env( 'SCRIPT_DEBUG', false ) );
define( 'SAVEQUERIES', env( 'SAVEQUERIES', false ) );
define( 'WP_ENVIRONMENT_TYPE', env( 'WP_ENVIRONMENT_TYPE', 'production' ) );

/* Add any custom values between this line and the "stop editing" line. */

define( 'AUTOMATIC_UPDATER_DISABLED', true );
define( 'DISABLE_WP_CRON', env( 'DISABLE_WP_CRON', false ) );
define( 'DISALLOW_FILE_EDIT', env( 'DISALLOW_FILE_EDIT', true ) );
define( 'DISALLOW_FILE_MODS', env( 'DISALLOW_FILE_MODS', true ) );

// To make WP load each script on the administration page individually; protects against CVE-2018-6389 DoS attacks.
define( 'CONCATENATE_SCRIPTS', env( 'CONCATENATE_SCRIPTS', false ) );

/* Disable Redis if the environment file decrees it so. */
define( 'WP_REDIS_DISABLED', env( 'WP_REDIS_DISABLED', false ) );

if ( defined( 'WP_CLI' ) && WP_CLI && env( 'MYSQLI_DEFAULT_SOCKET' ) ) {
	ini_set( 'mysqli.default_socket', env( 'MYSQLI_DEFAULT_SOCKET' ) ); // phpcs:ignore
}

/* Multisite */
define( 'WP_ALLOW_MULTISITE', env( 'WP_ALLOW_MULTISITE', false ) );
define( 'MULTISITE', env( 'MULTISITE', false ) );

if ( env( 'MULTISITE', false ) ) {
	define( 'SUBDOMAIN_INSTALL', env( 'SUBDOMAIN_INSTALL', false ) );
	define( 'DOMAIN_CURRENT_SITE', env( 'DOMAIN_CURRENT_SITE', parse_url( WP_HOME, PHP_URL_HOST ) ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.parse_url_parse_url
	define( 'PATH_CURRENT_SITE', '/' );
	define( 'SITE_ID_CURRENT_SITE', 1 );
	define( 'BLOG_ID_CURRENT_SITE', 1 );
}

if ( defined( 'WP_DEBUG' ) && WP_DEBUG && ! empty( $_SERVER['DOCUMENT_ROOT'] ) ) {
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . '/wp-settings.php';

// TODO: Remove when application.php is ready to be deleted.
require_once $app_root . '/config/application.php';
