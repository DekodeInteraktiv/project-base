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
\add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\do_enqueue_assets' );
\add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\do_enqueue_block_editor_assets' );
\add_filter( 't2/extension/enqueue_theme_block_styles/deps', __NAMESPACE__ . '\\do_override_t2_enqueue_theme_block_styles_deps', 65 );

/**
 * Setup navigation menus and theme supports.
 *
 * @return void
 */
function do_after_setup_theme(): void {

	// Theme text domain.
	\load_theme_textdomain( 'block-theme', \get_template_directory() . '/languages' );

	// Add theme supports.
	\add_theme_support( 'responsive-embeds' );
	\add_theme_support( 'post-thumbnails' );
	\add_theme_support( 'custom-logo' );
}

/**
 * Enqueue frontend scripts and styles.
 *
 * @return void
 */
function do_enqueue_assets(): void {

	// Bundled stylesheet.
	if ( \file_exists( \get_template_directory() . '/build/view.css' ) ) {
		$assets_version = \filemtime( \get_template_directory() . '/build/view.css' );

		// Set global styles as dependency to ensure correct loading order.
		$deps = [ 'global-styles' ];

		/* phpcs:disable
		// Optionally set all WooCommerce styling as dependency.
		if ( \class_exists( 'WooCommerce' ) ) {
			// Optionally incluce Woo styling if plugin is active.
			$deps[] = 'woocommerce-blocktheme';
			$deps[] = 'woocommerce-general';
			$deps[] = 'woocommerce-layout';
		}
		phpcs:enable
		*/

		\wp_enqueue_style( 'block-theme', \get_template_directory_uri() . '/build/view.css', $deps, $assets_version );
	}

	// Bundled scripts.
	$build_file  = \get_template_directory() . '/build/view.js';
	$assets_file = \get_template_directory() . '/build/view.asset.php';

	if ( \file_exists( $build_file ) && \file_exists( $assets_file ) ) {
		$assets = require $assets_file;
		\wp_enqueue_script( 'block-theme', \get_template_directory_uri() . '/build/view.js', $assets['dependencies'], $assets['version'], true );
	}
}

/**
 * Enqueue editor style for the WordPress editor.
 *
 * @return void
 */
function do_enqueue_block_editor_assets(): void {
	if ( ! is_admin() ) {
		return;
	}

	// Bundled stylesheet.
	if ( \file_exists( \get_template_directory() . '/build/editor.css' ) ) {
		$assets_version = \filemtime( \get_template_directory() . '/build/editor.css' );
		\wp_enqueue_style( 'block-theme', \get_template_directory_uri() . '/build/editor.css', [ 'global-styles-css-custom-properties' ], $assets_version );
	}

	// Bundled scripts.
	$build_file  = \get_template_directory() . '/build/editor.js';
	$assets_file = \get_template_directory() . '/build/editor.asset.php';

	if ( \file_exists( $build_file ) && \file_exists( $assets_file ) ) {
		$assets = require $assets_file;
		\wp_enqueue_script( 'block-theme', \get_template_directory_uri() . '/build/editor.js', $assets['dependencies'], $assets['version'], true );

		\wp_set_script_translations( 'block-theme', 'block-theme', \get_template_directory() . '/languages' );
		\wp_enqueue_script( 'block-theme' );
	}
}

/**
 * Set theme style as depenency for all block styles to ensure correct loading order.
 *
 * @param array $deps Dependencies.
 * @return array
 */
function do_override_t2_enqueue_theme_block_styles_deps( array $deps ): array {
	$deps[] = 'block-theme';
	return $deps;
}
