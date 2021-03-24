<?php
/**
 * Functions to register assets.
 *
 * @package dekode
 */

declare( strict_types=1 );
namespace Dekode\Assets;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Silence is golden.' );
}

/**
 * Get uri to asset file
 *
 * @param  string $file Filename.
 * @return string
 */
function get_file_uri( string $file ): string {
	return get_template_directory_uri() . '/build/' . $file;
}

/**
 * File file path
 *
 * @param  string $file Filename.
 * @return string
 */
function get_file_path( string $file ): string {
	return get_template_directory() . '/build/' . $file;
}


/**
 * Enqueue scripts and styles for the client.
 */
function scripts_and_styles() {
	/**
	 * Stylesheets
	 */
	$style_path = get_file_path( 'style.css' );
	if ( file_exists( $style_path ) ) {
		wp_enqueue_style(
			'dekode',
			get_file_uri( 'style.css' ),
			[],
			filemtime( $style_path )
		);
	}

	/**
	 * JavaScripts
	 */
	$index_js_path = get_file_path( 'index.js' );
	if ( file_exists( $index_js_path ) ) {
		$script_deps_path    = get_file_path( 'index.asset.php' );
		$script_dependencies = file_exists( $script_deps_path ) ?
			require $script_deps_path :
			[
				'dependencies' => [],
				'version'      => filemtime( $index_js_path ),
			];

		wp_enqueue_script(
			'dekode',
			get_file_uri( 'index.js' ),
			$script_dependencies['dependencies'],
			$script_dependencies['version'],
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\scripts_and_styles' );

/**
 * Enqueue editor style for the WordPress editor.
 */
function editor() {
	wp_enqueue_style( 'dekode-editor', get_template_directory_uri() . '/build/editor.css', [], DEKODE_THEME_VERSION );
}
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\editor' );
