<?php
/**
 * Plugin Name:  Example Block
 * Description:  Example Block
 * Version:      1.0.0
 * Author:       Dekode Interaktiv
 *
 * @package dekode
 */

declare( strict_types = 1 );

namespace Dekode\BlockLibrary\ExampleBlock;

defined( 'ABSPATH' ) || exit;

// Table of content.
\add_action( 'init', __NAMESPACE__ . '\\register_block_type' );

/**
 * Register plugin Block Type.
 *
 * @return void
 */
function register_block_type(): void {
	\register_block_type( __DIR__, [
		'render_callback' => __NAMESPACE__ . '\\render_block_type',
	] );
}

/**
 * Render block type.
 *
 * @param array     $attributes Block attributes.
 * @param string    $content Block default content.
 * @param \WP_Block $block Block instance.
 *
 * @return string Generated block markup.
 */
function render_block_type( array $attributes, string $content, \WP_Block $block ): string { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	return sprintf(
		'<div %s>
			Hi from Example Block 👋
		</div>',
		\get_block_wrapper_attributes(),
	);
}
