<?php
/**
 * Custom block rendering.
 * Use this file for block render overrides.
 *
 * @package BlockTheme
 */

declare( strict_types = 1 );
namespace BlockTheme\BlockRender;

defined( 'ABSPATH' ) || exit;

use function T2\Icons\get_icon;

// Hooks.
\add_filter( 'render_block_core/search', __NAMESPACE__ . '\\do_replace_search_block_button_icon' );

/**
 * Replace the built in search icon with a T2 icon in the core/search block.
 *
 * @param string $block_content Block content.
 * @return string
 */
function do_replace_search_block_button_icon( string $block_content ): string {
	// Remove line breaks and tabs so we can safely replace the svg icon later.
	$block_content = \str_replace( "\n", '', $block_content );
	$block_content = \preg_replace( '/\t+/', '', $block_content );

	// Replace the hard coded SVG icon in the block with our own T2 search icon.
	return \preg_replace(
		'/<svg.*?<\/svg>/',
		get_icon( 'search' ),
		$block_content,
	);
}
