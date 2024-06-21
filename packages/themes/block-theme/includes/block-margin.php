<?php
/**
 * Setup T2 Block Margin.
 *
 * @package BlockTheme
 */

declare( strict_types = 1 );
namespace BlockTheme\BlockMargin;

defined( 'ABSPATH' ) || exit;

// Table of content.
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
	$config['selector'] = ":is(
		{$root_selector} .wp-site-blocks,
		{$root_selector} .entry-content.is-layout-constrained,
		{$root_selector} .wp-block-post-content.is-layout-constrained,
		{$root_selector} .wp-block-group:is(.is-layout-flow, .is-layout-constrained),
		{$root_selector} .wp-block-column,
		{$root_selector} .wp-block-media-text__content,
		{$root_selector} .wp-block-query,
		{$root_selector} [class*=\"__inner-container\"],
	)";

	$config['gaps'] = [
		'none' => [
			'size' => '0',
			'selectors' => [
				// First child in any container.
				':first-child',

				// Trailing full size Groups and Covers.
				'.wp-block-group.alignfull + .wp-block-group.alignfull',
				'.wp-block-cover.alignfull + .wp-block-cover.alignfull',

				// Spacer block.
				'.wp-block-t2-spacer',
				'.wp-block-t2-spacer + *',
			],
		],
		'20' => [
			'size' => [
				'min' => 4,
				'max' => 4,
			],
			'selectors' => [],
		],
		'30' => [
			'size' => [
				'min' => 8,
				'max' => 8,
			],
			'selectors' => [],
		],
		'40' => [
			'size' => [
				'min' => 12,
				'max' => 12,
			],
			'selectors' => [],
		],
		'50' => [
			'size' => [
				'min' => 16,
				'max' => 16,
			],
			'selectors' => [],
		],
		'60' => [
			'size' => [
				'min' => 24,
				'max' => 24,
			],
			'selectors' => [
				// Default small gap for related blocks, e.g. paragraph + paragraph.
				':is(
					h1,
					h2, h3, h4, h5, h6
					p,
					.wp-block-columns,
					.wp-block-buttons,
					.wp-block-image,
					.wp-block-list,
					.wp-block-post-excerpt,
					.wp-block-post-featured-image,
					.wp-block-pullquote,
					.wp-block-quote,
					.wp-block-t2-ingress,
				) + :is(
					p,
					.t2-featured-content-layout,
					.wp-block-buttons,
					.wp-block-image,
					.wp-block-list,
					.wp-block-post-excerpt,
					.wp-block-post-featured-image,
					.wp-block-pullquote,
					.wp-block-quote,
					.wp-block-t2-ingress,
				)',
				// Content directly following sub headings.
				':is(h1, h2, h3, h4, h5, h6) + :is(p, .wp-block-list, .wp-block-video, .wp-block-embed, .wp-block-html, .wp-block-post-excerpt, .wp-block-buttons)',
				':is(h2, h3, h4, h5, h6) + :is(.wp-block-group, .wp-block-query, .wc-block-grid, .t2-accordion, .t2-link-list, .t2-featured-content-layout, .wp-block-t2-factbox, .products, .wp-block-fundraising-donation-form)',
				// Trailing sub headings.
				':is(h2) + :is(h3, h4, h5, h6)',
				':is(h3) + :is(h4, h5, h6)',
				':is(h4) + :is(h5, h6)',
				':is(h5) + :is(h6)',
			],
		],
		'70' => [
			'size' => [
				'min' => 32,
				'max' => 32,
			],
			'selectors' => [],
		],
		// Default.
		'80' => [
			'size' => [
				'min' => 48,
				'max' => 48,
			],
		],
	];

	return $config;
}
