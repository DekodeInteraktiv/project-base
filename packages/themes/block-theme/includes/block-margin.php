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
\add_filter( 'render_block', __NAMESPACE__ . '\\do_add_block_spacing_support_to_additional_containers' );

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
	// Simplify root selector to include only containers supported by Block Spacing API.
	$config['selector'] = "{$root_selector} :is(
		.wp-site-blocks,
		.is-layout-flow,
		.is-layout-flex.is-vertical,
		.is-layout-constrained,
	)";

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
				':is(p, .wp-block-list, .wp-block-heading, .wp-block-post-title, summary) + :is(p, .wp-block-list)',
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

/**
 * Override block rendering and add .is-layout-flow to selected containers to support Block Spacing API.
 *
 * @param string $block_content Block content.
 * @return string
 */
function do_add_block_spacing_support_to_additional_containers( string $block_content ): string {
	$containers = [
		'wp-block-media-text__content',
		't2-simple-media-text__content-inner',
		't2-accordion-item__inner-container',
		't2-factbox__inner-container',
		't2-infobox__content',
		't2-faq-item__inner-container',
	];

	foreach ( $containers as $container ) {
		if ( \str_contains( $block_content, $container ) ) {
			$processor = new \WP_HTML_Tag_Processor( $block_content );
			$processor->next_tag( [ 'class_name' => $container ] );
			$processor->add_class( 'is-layout-flow' );

			$block_content = $processor->get_updated_html();
		}
	}

	return $block_content;
}
