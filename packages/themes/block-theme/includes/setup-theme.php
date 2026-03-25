<?php
/**
 * Setup default theme functionality
 *
 * @package BlockTheme
 */

declare( strict_types = 1 );

namespace BlockTheme\SetupTheme;

defined( 'ABSPATH' ) || exit;

// Hooks.
\add_action( 'after_setup_theme', __NAMESPACE__ . '\\do_after_setup_theme' );

// Enqueue the theme assets.
\add_action( 'enqueue_block_assets', __NAMESPACE__ . '\\do_enqueue_block_assets' );
\add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\do_enqueue_block_editor_assets' );

/**
 * Setup navigation menus and theme supports.
 *
 * @return void
 */
function do_after_setup_theme(): void {

	// Add theme supports.
	\add_theme_support( 'responsive-embeds' );
	\add_theme_support( 'post-thumbnails' );
	\add_theme_support( 'custom-logo' );
}

/**
 * Enqueue the theme assets.
 *
 * @param  bool $for_editor Aim to load the editor assets or not.
 * @return void
 */
function load_theme_assets( bool $for_editor = false ): void {

	// Aim to make things more readable and predictable below in the code.
	$dir = \trailingslashit( \get_template_directory() ) . 'build/';
	$url = \trailingslashit( \get_template_directory_uri() ) . 'build/';

	if ( empty( $for_editor ) ) {
		$handle = 'block-theme';
		$assets = \file_exists( $dir . 'view.asset.php' ) ? require $dir . 'view.asset.php' : [];

		// Bundled stylesheet.
		if ( \file_exists( $dir . 'view.css' ) && ! \wp_style_is( $handle ) ) {
			$ver = $assets['version'] ?? \filemtime( $dir . 'view.css' );
			\wp_enqueue_style( $handle, $url . 'view.css', [], $ver );
		}

		// Bundled scripts.
		$build_file = $dir . 'view.js';
		if ( \file_exists( $build_file ) && ! \wp_script_is( $handle ) ) {
			$dep = $assets['dependencies'] ?? [];
			$ver = $assets['version'] ?? \filemtime( $dir . 'view.js' );
			\wp_enqueue_script( $handle, $url . 'view.js', $dep, $ver, true );
		}
	} else {
		$handle = 'block-theme-editor';
		$assets = \file_exists( $dir . 'editor.asset.php' ) ? require $dir . 'editor.asset.php' : [];

		// Bundled stylesheet.
		if ( \file_exists( $dir . 'editor.css' ) && ! \wp_style_is( $handle ) ) {
			$ver = $assets['version'] ?? \filemtime( $dir . 'editor.css' );
			\wp_enqueue_style( $handle, $url . 'editor.css', [ 'global-styles-css-custom-properties' ], $ver );
		}

		// Bundled scripts.
		$build_file = $dir . 'editor.js';
		if ( \file_exists( $build_file ) && ! \wp_script_is( $handle ) ) {
			$dep = $assets['dependencies'] ?? [];
			$ver = $assets['version'] ?? \filemtime( $dir . 'editor.js' );
			\wp_enqueue_script( $handle, $url . 'editor.js', $dep, $ver, true );
			\wp_set_script_translations( $handle, 'block-theme', \get_template_directory() . '/languages' );
		}
	}
}

/**
 * Enqueue the theme scripts and styles in the WordPress editor and iframe.
 * Both view and editor assets should be passed.
 *
 * @return void
 */
function do_enqueue_block_assets(): void {
	load_theme_assets();

	// Avoid running on normal front-end requests; only load in editor or editor iframe.
	if ( ! \is_admin() && ( ! \function_exists( '\is_block_editor' ) || ! \is_block_editor() ) ) {
		// No need to continue.
		return;
	}

	load_theme_assets( true );
}

/**
 * Enqueue the theme scripts and styles in the WordPress editor.
 * This is primarily a compatibility hook for editor contexts where
 * apiVersion 3 is not enabled for all components or the editor is not
 * rendered in an iframe. View assets are enqueued via
 * {@see do_enqueue_block_assets()}, while this function ensures the
 * corresponding editor assets are available.
 *
 * @return void
 */
function do_enqueue_block_editor_assets(): void {
	// Avoid running on normal front-end requests; only load in editor or editor iframe.
	if ( ! \is_admin() && ( ! \function_exists( '\is_block_editor' ) || ! \is_block_editor() ) ) {
		// No need to continue.
		return;
	}

	load_theme_assets( true );
}
