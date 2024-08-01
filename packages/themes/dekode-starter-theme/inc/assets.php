<?php
/**
 * Theme assets setup.
 *
 * @package DekodeStarterTheme
 **/

declare( strict_types=1 );

namespace DekodeStarterTheme\Assets;

/**
 * Assets setup.
 *
 * @return void
 */
function setup(): void {
	\add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\do_enqueue_assets' );
	\add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\do_enqueue_block_editor_assets' );
}

/**
 * Enqueue front end styles and scripts.
 *
 * @return void
 */
function do_enqueue_assets(): void {

	// Enqueue styles.
	$style_file_path = \get_template_directory() . '/build/style.css';
	$style_file_uri  = \get_template_directory_uri() . '/build/style.css';

	if ( \file_exists( $style_file_path ) ) {
		\wp_enqueue_style( 'dekode-starter-theme', $style_file_uri, [], \filemtime( $style_file_path ) );
	}

	// Enqueue scripts.
	$script_file_path      = \get_template_directory() . '/build/index.js';
	$script_file_uri       = \get_template_directory_uri() . '/build/index.js';
	$script_deps_file_path = \get_template_directory() . '/build/index.asset.php';

	if ( \file_exists( $script_file_path ) && \file_exists( $script_deps_file_path ) ) {
		$dependencies = require $script_deps_file_path;
		\wp_enqueue_script( 'dekode-starter-theme', $script_file_uri, $dependencies['dependencies'], $dependencies['version'], true );
	}
}

/**
 * Enqueue editor scripts and styles.
 *
 * @return void
 */
function do_enqueue_block_editor_assets(): void {

	// Enqueue styles.
	$style_file_path = \get_template_directory() . '/build/editor-styles.css';
	$style_file_uri  = \get_template_directory_uri() . '/build/editor-styles.css';

	if ( \file_exists( $style_file_path ) ) {
		\wp_enqueue_style( 'dekode-starter-theme-editor', $style_file_uri, [], \filemtime( $style_file_path ) );
	}

	// Enqueue scripts.
	$script_file_path      = \get_template_directory() . '/build/editor.js';
	$script_file_uri       = \get_template_directory_uri() . '/build/editor.js';
	$script_deps_file_path = \get_template_directory() . '/build/editor.asset.php';

	if ( \file_exists( $script_file_path ) && \file_exists( $script_deps_file_path ) ) {
		$dependencies = require $script_deps_file_path;
		\wp_enqueue_script( 'dekode-starter-theme-editor', $script_file_uri, $dependencies['dependencies'], $dependencies['version'], false );
	}
}
