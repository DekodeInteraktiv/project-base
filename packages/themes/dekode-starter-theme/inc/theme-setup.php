<?php
/**
 * Theme setup.
 *
 * @package DekodeStarterTheme
 **/

declare( strict_types=1 );

namespace DekodeStarterTheme\Setup;

/**
 * Theme setup.
 *
 * @return void
 */
function setup(): void {
	\add_action( 'after_setup_theme', __NAMESPACE__ . '\\theme_setup' );
}

/**
 * Add pattern category.
 */
function add_pattern_category(): void {
	\register_block_pattern_category(
		'dekode-starter-theme',
		[
			'label' => 'Dekode Starter Theme',
		]
	);
}

/**
 * Theme setup.
 *
 * @return void
 */
function theme_setup(): void {
	// Some supports are automatically enabled for block themes, others are configured in `theme.json`
	// https://developer.wordpress.org/themes/block-themes/block-theme-setup/#theme-support.
	\add_theme_support( 'editor-styles' );
	\add_editor_style( 'build/editor.css' );

	add_pattern_category();
}
