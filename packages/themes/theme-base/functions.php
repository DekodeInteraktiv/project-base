<?php
/**
 * The main funtions file
 *
 * @package dekode
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Silence is golden.' );
}

if ( ! function_exists( 'get_dekode_theme_version' ) ) {
	/**
	 * Get the current theme version. Used for asset loading.
	 *
	 * @return string Version number or random number
	 */
	function get_dekode_theme_version() : string {
		$theme_data = wp_get_theme( get_template() );
		$version    = $theme_data->get( 'Version' );

		return $version ?: (string) wp_rand();
	}
}

define( 'DEKODE_THEME_VERSION', get_dekode_theme_version() );

require __DIR__ . '/inc/assets.php';
require __DIR__ . '/inc/setup.php';
require __DIR__ . '/inc/setup-header.php';
require __DIR__ . '/inc/setup-footer.php';
