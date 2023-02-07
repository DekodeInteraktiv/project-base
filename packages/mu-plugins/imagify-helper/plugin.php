<?php
/**
 * Plugin Name: Imagify upload path controller.
 * Description: Provide the correct upload root paths in a multisite environment using Dekodes custom path definitions.
 * Version:     1.0.0
 * Author:      Dekode
 *
 * @package Dekode/MU
 *
 * Inspired by the `Imagify Custom Site Root` plugin.
 */

declare( strict_types = 1 );

namespace Dekode\MUPlugins;

/**
 * Hooks
 */
\add_filter( 'imagify_site_root', __NAMESPACE__ . '\\imagify_site_root_override', 20 );

/**
 * Conditionally override the Imagify plugin root path.
 *
 * Our custom setup has the upload directory outside the WordPress root, and also names it differently from `wp-content`.
 * This confuses the Imagify plugin when in a multisite environment, working on a primary site, so some magic is needed here.
 *
 * @param string|null $root_path Root path.
 * @return string|null;
 */
function imagify_site_root_override( ?string $root_path ): ?string {
	if ( ! function_exists( 'imagify_get_filesystem' ) ) {
		return $root_path;
	}

	$upload_basedir = imagify_get_filesystem()->get_upload_basedir( true );

	// If the detected upload path is not one with our custom upload directory, make no changes to the already determined root path.
	if ( false === strpos( $upload_basedir, '/content/' ) ) {
		return $root_path;
	}

	$upload_basedir = explode( '/content/', $upload_basedir );
	$upload_basedir = reset( $upload_basedir );

	return trailingslashit( $upload_basedir );
}
