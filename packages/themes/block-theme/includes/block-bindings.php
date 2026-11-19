<?php
/**
 * Block Bindings sources.
 *
 * @package BlockTheme
 */

declare( strict_types = 1 );

namespace BlockTheme\BlockBindings;

defined( 'ABSPATH' ) || exit;

// Hooks.
\add_action( 'init', __NAMESPACE__ . '\\register_sources' );

/**
 * Register block binding sources.
 *
 * @return void
 */
function register_sources(): void {
	\register_block_bindings_source(
		'block-theme/copyright',
		[
			'label'              => \__( 'Copyright', 'block-theme' ),
			'get_value_callback' => __NAMESPACE__ . '\\get_copyright',
		]
	);
}

/**
 * Get the copyright text: "© {current year} {site name}".
 *
 * @return string
 */
function get_copyright(): string {
	return \sprintf(
		'© %1$s %2$s',
		\wp_date( 'Y' ),
		\esc_html( \get_bloginfo( 'name' ) )
	);
}
