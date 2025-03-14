<?php
/**
 * Setup T2 Icons.
 *
 * @package BlockTheme
 */

declare( strict_types = 1 );
namespace BlockTheme\T2Icons;

defined( 'ABSPATH' ) || exit;

// Hooks.
\add_filter( 't2_icons', __NAMESPACE__ . '\\register_icons' );

/**
 * Append custom icons.
 *
 * @param array $icons Icons.
 * @return array
 */
function register_icons( array $icons ): array {
	return $icons;
}
