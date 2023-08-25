<?php
/**
 * Theme icons setup.
 *
 * @package DekodeStarterTheme
 **/

declare( strict_types = 1 );
namespace DekodeStarterTheme\Icons;

/**
 * Theme icons setup.
 */
function setup(): void {

	// Check if the theme icons font is built.
	if ( is_theme_icons_ready() ) {

		// Preload the theme icons font.
		\add_action( 'wp_head', __NAMESPACE__ . '\\preload_icon_font', 5 );

		// Enqueue the theme icons font.
		\add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_icon_font' );

		// Import theme icons and add them to T2 icons.
		\add_filter( 't2_icons', __NAMESPACE__ . '\\import_theme_icons' );
	} else {
		// Enqueue svg icons.
		\add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_svg_icons' );
	}
}

/**
 * Checks if the theme icons font is built.
 */
function is_theme_icons_ready(): bool {
	return \is_readable( get_theme_icons_path( 'theme-icons.asset.php' ) )
		&& \file_exists( get_theme_icons_path( 'theme-icons.css' ) );
}

/**
 * Preload theme icons font to avoid FOIT or FOUT.
 *
 * FOIT = Flash of Invisible Text
 * FOUT = Flash of Unstyled Text.
 */
function preload_icon_font() {
	$asset   = require get_theme_icons_path( 'theme-icons.asset.php' );
	$version = $asset['version'] ?? 0;

	if ( $version ) {
		\vprintf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin="anonymous">', [
			\esc_url( get_theme_icons_url( "theme-icons.woff2?t={$version}" ) ),
		] );
	}
}

/**
 * Enqueue the theme icons font.
 */
function enqueue_icon_font(): void {
	$asset   = require get_theme_icons_path( 'theme-icons.asset.php' );
	$version = $asset['version'] ?? 0;

	if ( $version ) {
		$source = get_theme_icons_url( 'theme-icons.css' );
		\wp_enqueue_style( 'font-theme-icons', $source, [], $version );
	}
}

/**
 * Imports theme icon fonts into T2 icons.
 *
 * @param array $icons An array of T2 icons.
 */
function import_theme_icons( array $icons ) {
	$file = get_theme_icons_path( 'theme-icons.json' );

	if ( \is_readable( $file ) ) {
		$content  = \file_get_contents( $file ); // phpcs:ignore
		$imported = \json_decode( $content, true ) ?: [];

		foreach ( $imported as $name => $paths ) {
			$icons[ camelize( $name ) ] = join( '',
				array_map( function ( string $value ): string {
					return sprintf( '<path d="%s" />', $value );
				}, $paths )
			);
		}
	}

	return $icons;
}

/**
 * Enqueue svg icons.
 */
function enqueue_svg_icons(): void {
	$asset_path = \get_template_directory() . '/build/icons.asset.php';

	if ( \is_readable( $asset_path ) ) {
		$asset  = require $asset_path;
		$source = \get_template_directory_uri() . '/build/icons.css';
		\wp_enqueue_style( 'theme-svg-icons', $source, [], $asset['version'] ?? '' );
	}
}

/**
 * Gets the full path to a theme icon file.
 *
 * @param string $local The local path.
 */
function get_theme_icons_path( string $local ): string {
	$font_dir = '/assets/fonts/theme-icons/';
	return \get_template_directory() . $font_dir . ltrim( $local, '/\\' );
}

/**
 * Gets the full url to a theme icon file.
 *
 * @param string $local The local path.
 */
function get_theme_icons_url( string $local ): string {
	$font_dir = '/assets/fonts/theme-icons/';
	return \get_template_directory_uri() . $font_dir . ltrim( $local, '/\\' );
}

/**
 * Converts hyphen separated names into camel case.
 *
 * @param string $name The name to convert.
 * @param string $separator The separator.
 */
function camelize( string $name, string $separator = '-' ): string {
	return lcfirst( str_replace( $separator, '', ucwords( $name, $separator ) ) );
}
