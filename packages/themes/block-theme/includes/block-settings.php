<?php
/**
 * Custom block settings.
 *
 * @package BlockTheme
 */

declare( strict_types = 1 );
namespace BlockTheme\BlockSettings;

defined( 'ABSPATH' ) || exit;

// Table of content.
\add_filter( 'register_block_type_args', __NAMESPACE__ . '\\do_override_block_type_args', 10, 2 );
\add_action( 'init', __NAMESPACE__ . '\\do_register_block_styles' );
\add_filter( 'allowed_block_types_all', __NAMESPACE__ . '\\do_override_allowed_block_types_all', 40 );

/**
 * Override block type registration args.
 *
 * @param array  $args Block args.
 * @param string $name Block name.
 * @return array
 */
function do_override_block_type_args( array $args, string $name ): array {
	// Override block type args here. See example below where the Query block is changed to wide by default.

	// Set Query as wide by default.
	if ( 'core/query' === $name ) {
		$args['attributes']['align'] = [
			'type'    => 'string',
			'default' => 'wide',
		];
	}

	return $args;
}

/**
 * Register custom block styles.
 *
 * @return void
 */
function do_register_block_styles(): void {
	// Register custom block styles here.
}

/**
 * Limit allowed block types for theme.
 *
 * @return array|bool List of allowed blocks or true for all blocks.
 */
function do_override_allowed_block_types_all(): array|bool {
	return true;

	// phpcs:disable
	/* return [
		'core/block',
		'core/button',
		'core/buttons',
		'core/column',
		'core/columns',
		'core/cover',
		'core/embed',
		'core/group',
		'core/heading',
		'core/html',
		'core/image',
		'core/list-item',
		'core/list',
		'core/media-text',
		'core/paragraph',
		'core/post-excerpt',
		'core/post-featured-image',
		'core/post-template',
		'core/post-title',
		'core/pullquote',
		'core/query-pagination',
		'core/query',
		'core/quote',
		'core/search',
		'core/separator',
		'core/site-logo',
		'core/social-link',
		'core/social-links',
		'core/table',
		'core/template-part',
		'core/video',
		'gravityforms/form',
		't2/accordion-item',
		't2/accordion',
		't2/factbox',
		't2/featured-content-layout',
		't2/featured-query-post',
		't2/featured-single-post',
		't2/featured-template-post',
		't2/icon',
		't2/ingress',
		't2/link-list-item',
		't2/link-list',
		't2/nav-menu',
		't2/people',
		't2/spacer',
		't2/state-toggle',
		't2/statistic-item',
		't2/statistics',
		'woocommerce/cart',
		'woocommerce/classic-shortcode',
		'woocommerce/handpicked-products',
		'woocommerce/product-category',
		'woocommerce/product-tag',
		'woocommerce/products-by-attribute',
	]; */
	// phpcs:disable
}
