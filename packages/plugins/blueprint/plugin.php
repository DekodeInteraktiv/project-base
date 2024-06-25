<?php
/**
 * Plugin Name:  Blueprint
 * Description:  Blueprint plugin with assets and blocks.
 * Version:      1.0.0
 * Author:       Dekode Interaktiv AS
 * Author URI:   https://dekode.no/
 * Text Domain:  blueprint
 *
 * @package dekode
 */

declare( strict_types = 1 );

namespace Dekode\Blueprint;

use function T2\Blocks\get_block_meta;

defined( 'ABSPATH' ) || exit;

// Require all block.php files in build/ folder.
\array_map( fn( $f ) => require_once $f, \glob( __DIR__ . '/build/*/block.php' ) );

// Hooks.
\add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\do_enqueue_assets' );
\add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\do_enqueue_block_editor_assets' );
\add_action( 'init', __NAMESPACE__ . '\\load_textdomain' );
\add_filter( 'allowed_block_types_all', __NAMESPACE__ . '\\do_override_allowed_block_types_all', 50 );

/**
 * Enqueue frontend scripts and styles.
 *
 * @return void
 */
function do_enqueue_assets(): void {
	do_enqueue_plugin_assets( 'view', [ 'dekode-theme' ], [ 'dekode-theme' ] );
}

/**
 * Enqueue block editor scripts and styles.
 *
 * @return void
 */
function do_enqueue_block_editor_assets(): void {
	do_enqueue_plugin_assets( 'editor', [ 'dekode-theme' ] );
}

/**
 * Load plugin text domain.
 *
 * @return void.
 */
function load_textdomain(): void {
	\load_plugin_textdomain( 'blueprint', false, \dirname( \plugin_basename( __FILE__ ) ) . '/languages' );
}

/**
 * Automatically whitelist all block library block types.
 *
 * @param array|bool $blocks Allowed block types.
 * @return array|bool
 */
function do_override_allowed_block_types_all( $blocks ) {
	if ( ! \is_array( $blocks ) ) {
		return $blocks;
	}

	$block_library_blocks = \array_map( function ( string $path ): string {
		$block_meta = get_block_meta( $path );
		return $block_meta['name'];
	}, \glob( __DIR__ . '/src/*', GLOB_ONLYDIR ) );

	return \array_merge( $blocks, $block_library_blocks );
}


/**
 * Enqueue plugin assets for entry.
 *
 * @param string $entry View or editor.
 * @param array  $style_dependencies Stylesheet dependenies.
 * @param array  $script_dependencies Script dependenies.
 * @return void
 */
function do_enqueue_plugin_assets( string $entry = 'view', array $style_dependencies = [], array $script_dependencies = [] ): void {
	$plugin_path      = \plugin_dir_path( __FILE__ );
	$script_deps_file = \get_template_directory() . "/build/$entry.asset.php";

	// Bundled stylesheet.
	if ( \file_exists( "$plugin_path/build/$entry.css" ) ) {
		$assets_version = \filemtime( "$plugin_path/build/$entry.css" );
		\wp_enqueue_style( 'blueprint', \plugins_url( "build/$entry.css", __FILE__ ), $style_dependencies, $assets_version );
	}

	// Bundled scripts.
	if ( \file_exists( "$plugin_path/build/$entry.js" ) && \file_exists( $script_deps_file ) ) {
		$dependencies = require $script_deps_file;
		$dependencies = \array_merge( $dependencies, $script_dependencies );
		\wp_enqueue_script( 'blueprint', \plugins_url( "build/$entry.js", __FILE__ ), $dependencies['dependencies'], $dependencies['version'], true );
	}
}
