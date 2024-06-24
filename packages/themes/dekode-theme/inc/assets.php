<?php
/**
 * Setup theme assets.
 *
 * @package dekode
 */

declare( strict_types=1 );
namespace Dekode\Assets;

/**
 * Hooks
 */
\add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\do_enqueue_assets' );
\add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\do_enqueue_block_editor_assets' );

/**
 * Enqueue front end styles and scripts.
 *
 * @return void
 */
function do_enqueue_assets(): void {

	// Enqueue styles.
	$style_file_path = \get_template_directory() . '/build/view.css';
	$style_file_uri  = \get_template_directory_uri() . '/build/view.css';

	if ( \file_exists( $style_file_path ) ) {
		\wp_enqueue_style( 'dekode-theme', $style_file_uri, [], \filemtime( $style_file_path ) );
	}

	// Enqueue scripts.
	$script_file_path      = \get_template_directory() . '/build/view.js';
	$script_file_uri       = \get_template_directory_uri() . '/build/view.js';
	$script_deps_file_path = \get_template_directory() . '/build/view.asset.php';

	if ( \file_exists( $script_file_path ) && \file_exists( $script_deps_file_path ) ) {
		$dependencies = require $script_deps_file_path;
		\wp_enqueue_script( 'dekode-theme', $script_file_uri, $dependencies['dependencies'], $dependencies['version'], true );
	}
}

/**
 * Enqueue editor scripts and styles.
 *
 * @return void
 */
function do_enqueue_block_editor_assets(): void {

	// Enqueue styles.
	$style_file_path = \get_template_directory() . '/build/editor.css';
	$style_file_uri  = \get_template_directory_uri() . '/build/editor.css';

	if ( \file_exists( $style_file_path ) ) {
		\wp_enqueue_style( 'dekode-theme-editor', $style_file_uri, [], \filemtime( $style_file_path ) );
	}

	// Enqueue scripts.
	$script_file_path      = \get_template_directory() . '/build/editor.js';
	$script_file_uri       = \get_template_directory_uri() . '/build/editor.js';
	$script_deps_file_path = \get_template_directory() . '/build/editor.asset.php';

	if ( \file_exists( $script_file_path ) && \file_exists( $script_deps_file_path ) ) {
		$dependencies = require $script_deps_file_path;
		\wp_enqueue_script( 'dekode-theme-editor', $script_file_uri, $dependencies['dependencies'], $dependencies['version'], true );
	}
}
