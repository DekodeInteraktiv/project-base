<?php
/**
 * Setup T2 Custom Block Margin v2.
 *
 * @package BlockTheme
 */

declare( strict_types = 1 );
namespace BlockTheme\BlockMargin;

defined( 'ABSPATH' ) || exit;

// Hooks.
\add_filter( 't2/custom_block_margin/root_selector', __NAMESPACE__ . '\\do_override_root_selector', 10, 2 );
\add_filter( 't2/custom_block_margin/last/selectors', __NAMESPACE__ . '\\do_override_last_selectors' );
\add_filter( 't2/custom_block_margin/config', __NAMESPACE__ . '\\do_override_custom_block_margin_config', 10, 2 );
\add_filter( 't2/custom_block_margin/default/size_key', fn() => '80' );
\add_filter( 't2/custom_block_margin/last/size_key', fn() => '80' );

/**
 * Change the root selector to all contrained containers.
 *
 * @param string  $root_selector Root selector.
 * @param boolean $is_editor Is editor.
 * @return string
 */
function do_override_root_selector( string $root_selector, bool $is_editor ): string {
	return $is_editor ? ':is(.block-editor-writing-flow, body)' : 'body';
}

/**
 * Overide the last selector to include .entry-content blocks only.
 *
 * @return array
 */
function do_override_last_selectors(): array {
	return [
		// Only non-alignfull last child in entry content container.
		'body .entry-content.is-layout-constrained > :last-child:not(.alignfull)',
	];
}

/**
 * Override T2 Custom Block Margin Config.
 *
 * @param array  $config Config.
 * @param string $root_selector Root selector.
 * @return array
 */
function do_override_custom_block_margin_config( array $config, $root_selector ): array {

	// Simplify selector to include all constrained containers and selected inner block containers.
	/*
	$config['selector'] = ":is(
		{$root_selector} .wp-site-blocks,
		{$root_selector} .entry-content.is-layout-constrained,
		{$root_selector} .wp-block-post-content.is-layout-constrained,
		{$root_selector} .wp-block-group:is(.is-layout-flow, .is-layout-constrained),
		{$root_selector} .wp-block-column,
		{$root_selector} .wp-block-media-text__content,
		{$root_selector} .t2-simple-media-text__content-inner,
		{$root_selector} .wp-block-query,
		{$root_selector} [class*=\"__inner-container\"],
	)";
	*/
	$config['selector'] = "{$root_selector} :is(
		.wp-site-blocks,
		.is-layout-constrained,
		.is-layout-flow,
		.is-layout-flex.is-vertical,
	)";

	/*
	TODO:
	To use core blockGap we need a way to extend it to custom containers, e.g.:
	- wp-block-media-text__content
	- t2-simple-media-text__content
	- others?

	Possible solution:
	Override rendering for blocks and insert .is-layout-flow class:
	- For core/media+text: Add .is-layout-flow to .wp-block-media-text__content
	- For t2/simple-media-text: Add .is-layout-flow to .t2-simple-media-text__content-inner
	- For gravityforms/form: Add .is-layout-flow to .gform_heading
	*/

	$config['gaps'] = [
		'00' => [
			'selectors' => [
				// First child in any container.
				':first-child',

				// Trailing full size Groups and Covers.
				'.wp-block-group.alignfull + .wp-block-group.alignfull',
				'.wp-block-cover.alignfull + .wp-block-cover.alignfull',

				// Spacer blocks.
				'.wp-block-spacer',
				'.wp-block-spacer + *',
				'.wp-block-t2-spacer',
				'.wp-block-t2-spacer + *',
			],
		],
		'20' => [
			'selectors' => [],
		],
		'30' => [
			'selectors' => [],
		],
		'40' => [
			'selectors' => [],
		],
		'50' => [ // Medium.
			'selectors' => [
				// Small block spacing for related blocks, e.g. paragraph + paragraph or heading + paragraph.
				':is(p, .wp-block-list, .wp-block-heading, .wp-block-post-title) + :is(p, .wp-block-list)',
			],
		],
		'60' => [
			'selectors' => [],
		],
		'70' => [
			'selectors' => [],
		],
		'80' => [ // Default.
			'selectors' => [],
		],
	];

	return $config;
}
