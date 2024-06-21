<?php
/**
 * Custom block rendering.
 *
 * @package BlockTheme
 */

declare( strict_types = 1 );
namespace BlockTheme\BlockRender;

defined( 'ABSPATH' ) || exit;

use function T2\Icons\get_icon;
use function T2\Utils\classnames;

// Table of content.
\add_filter( 'render_block_core/search', __NAMESPACE__ . '\\do_replace_search_block_button_icon' );
\add_filter( 'render_block_core/site-logo', __NAMESPACE__ . '\\do_append_site_title_to_logo' );
\add_filter( 'render_block_core/heading', __NAMESPACE__ . '\\maybe_append_doc_subtitle_attr_for_kicker_headings', 10, 2 );
\add_filter( 'render_block_core/post-featured-image', __NAMESPACE__ . '\\do_add_image_caption_to_post_featured_image', 10, 3 );
\add_filter( 'render_block_core/query-pagination-previous', __NAMESPACE__ . '\\do_replace_pagination_icons' );
\add_filter( 'render_block_core/query-pagination-next', __NAMESPACE__ . '\\do_replace_pagination_icons' );
\add_filter( 'render_block_core/quote', __NAMESPACE__ . '\\do_override_quote_rendering' );
\add_filter( 'render_block_core/html', __NAMESPACE__ . '\\do_wrap_html_block' );
\add_filter( 'render_block_gravityforms/form', __NAMESPACE__ . '\\do_wrap_gravityforms_block' );
\add_filter( 'render_block_woocommerce/mini-cart', __NAMESPACE__ . '\\do_replace_minicart_block_cart_icon' );
\add_filter( 'render_block_woocommerce/add-to-cart-form', __NAMESPACE__ . '\\do_insert_add_to_cart_form_cart_icon' );
\add_filter( 'render_block_woocommerce/store-notices', __NAMESPACE__ . '\\maybe_skip_rendering_empty_store_notices' );
\add_filter( 'render_block_woocommerce/breadcrumbs', __NAMESPACE__ . '\\maybe_skip_rendering_breadcrumbs' );
\add_filter( 'render_block_yoast-seo/breadcrumbs', __NAMESPACE__ . '\\maybe_skip_rendering_breadcrumbs' );
\add_filter( 'render_block_t2/featured-query-post', __NAMESPACE__ . '\\do_append_extra_classes_to_featured_content', 10, 2 );
\add_filter( 'render_block_t2/featured-single-post', __NAMESPACE__ . '\\do_append_extra_classes_to_featured_content', 10, 2 );
\add_filter( 'render_block_t2/featured-template-post', __NAMESPACE__ . '\\do_append_extra_classes_to_featured_content', 10, 2 );

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

/**
 * Append site title to site logo block.
 *
 * @param string $block_content Block content.
 * @return string
 */
function do_append_site_title_to_logo( string $block_content ): string {
	$processor = new \WP_HTML_Tag_Processor( $block_content );
	$processor->next_tag( 'a' );

	if ( 'home' === $processor->get_attribute( 'rel' ) ) {
		$processor->set_attribute( 'title', \get_bloginfo( 'title' ) );
	}

	return $processor->get_updated_html();
}

/**
 * Mark post kicker headings with doc-subtitle role.
 *
 * @param string $block_content Block content.
 * @param array  $parsed_block Block info.
 * @return string
 */
function maybe_append_doc_subtitle_attr_for_kicker_headings( string $block_content, array $parsed_block ): string {
	$class_name = $parsed_block['attrs']['className'] ?? '';

	if ( \str_contains( (string) $class_name, 'is-style-kicker' ) ) {
		$processor = new \WP_HTML_Tag_Processor( $block_content );
		$processor->next_tag();
		$processor->set_attribute( 'role', 'doc-subtitle' );
		return $processor->get_updated_html();
	}

	return $block_content;
}

/**
 * Override rendering of core/post-featured-image block and append the image caption to figure where it's set.
 *
 * @param string    $block_content Block content.
 * @param array     $parsed_block Block data.
 * @param \WP_Block $block Block object.
 * @return string
 */
function do_add_image_caption_to_post_featured_image( string $block_content, array $parsed_block, \WP_Block $block ): string {
	if ( isset( $block->context['postId'] ) ) {
		$post_id = $block->context['postId'];
	}

	if ( empty( $post_id ) ) {
		return $block_content;
	}

	$show_caption = $parsed_block['attrs']['showCaption'] ?? true;
	$use_custom_caption = $parsed_block['attrs']['useCustomCaption'] ?? false;
	$custom_caption = $parsed_block['attrs']['customCaption'] ?? '';

	if ( ! $show_caption ) {
		return $block_content;
	}

	$caption = $use_custom_caption ? $custom_caption : \get_the_post_thumbnail_caption( $post_id );

	if ( ! empty( $caption ) ) {
		// Append caption to figure.
		$block_content = \preg_replace(
			'/<\/figure>/',
			'<figcaption class="wp-element-caption">' . $caption . '</figcaption></figure>',
			$block_content,
		);
	}

	return $block_content;
}

/**
 * Replace pagination arrows from core/query-pagination-previous and core/query-pagination-next with T2 icons.
 * Lets hope get_query_pagination_arrow() gets a filter in the future.
 *
 * @param string $block_content Block markup.
 * @return string
 */
function do_replace_pagination_icons( string $block_content ): string {
	// Arrows.
	$block_content = \str_replace( "<span class='wp-block-query-pagination-next-arrow is-arrow-arrow' aria-hidden='true'>→</span>", get_icon( 'arrowForward' ), $block_content );
	$block_content = \str_replace( "<span class='wp-block-query-pagination-previous-arrow is-arrow-arrow' aria-hidden='true'>←</span>", get_icon( 'arrowBack' ), $block_content );

	// Chevrons.
	$block_content = \str_replace( "<span class='wp-block-query-pagination-next-arrow is-arrow-chevron' aria-hidden='true'>»</span>", get_icon( 'chevronRight' ), $block_content );
	$block_content = \str_replace( "<span class='wp-block-query-pagination-previous-arrow is-arrow-chevron' aria-hidden='true'>«</span>", get_icon( 'chevronLeft' ), $block_content );

	return $block_content;
}

/**
 * Insert <br /> after every <p> in Quote block since they are rendered as inline.
 *
 * @param string $block_content Block content.
 * @return string
 */
function do_override_quote_rendering( string $block_content ): string {
	return \preg_replace( '/<\/p>[\S+\n\r]+<p>/mi', '</p><br /><p>', $block_content );
}

/**
 * Wrap Custom HTML block content in div wrapper to enclose trailing script tag.
 *
 * @param string $block_content Original Gravity Forms block content.
 * @return string
 */
function do_wrap_html_block( string $block_content ): string {
	return sprintf( '<div class="wp-block-html">%s</div>', $block_content );
}

/**
 * Wrap Gravity Forms block in div wrapper to enclose trailing script tag.
 *
 * @param string $block_content Original Gravity Forms block content.
 * @return string
 */
function do_wrap_gravityforms_block( string $block_content ): string {
	return sprintf( '<div class="wp-block-gravityforms-form-wrapper">%s</div>', $block_content );
}

/**
 * Replace the built in cart icon with a T2 icon in the woocommerce/mini-cart block.
 *
 * @param string $block_content Block content.
 * @return string
 */
function do_replace_minicart_block_cart_icon( string $block_content ): string {
	// Remove line breaks and tabs so we can safely replace the svg icon later.
	$block_content = \str_replace( "\n", '', $block_content );
	$block_content = \preg_replace( '/\t+/', '', $block_content );

	// Replace the hard coded SVG icon in the block with our own T2 search icon.
	return \preg_replace(
		'/<svg.*?<\/svg>/',
		get_icon( 'cart' ),
		$block_content,
	);
}

/**
 * Insert cart icon next to text on single product template add to cart button.
 *
 * @param string $block_content Block content.
 * @return string
 */
function do_insert_add_to_cart_form_cart_icon( string $block_content ): string {
	// Replace the hard coded SVG icon in the block with our own T2 search icon.
	return \str_replace(
		sprintf( '%s</button>', __( 'Add to cart', 'woocommerce' ) ),
		sprintf(
			'%s%s%s%s</button>',
			__( 'Add to cart', 'woocommerce' ),
			get_icon( 'cart', classname: 'hide-for-added hide-for-adding' ),
			get_icon( 'checked', classname: 'show-for-added' ),
			get_icon( 'loading', classname: 'show-for-adding' ),
		),
		$block_content,
	);
}

/**
 * A bug in WooCommerce (currently 8.0.0) will output the store notices block wrapper and an inner wrapper from woocommerce_output_all_notices()
 * event thought there are no notices. This callback will fix that, returning empty string if only the inner wrapper is present.
 *
 * @param string $block_content Block content.
 * @return string
 */
function maybe_skip_rendering_empty_store_notices( string $block_content ): string {
	$empty_block = '<div data-block-name="woocommerce/store-notices" class="woocommerce wc-block-store-notices alignwide  alignwide"><div class="woocommerce-notices-wrapper"></div></div>';

	if ( $empty_block === $block_content ) {
		return '';
	}

	return $block_content;
}

/**
 * Avoid rendering breadcrumbs for front pages.
 *
 * @param string $block_content Breadcrumbs block content.
 * @return string
 */
function maybe_skip_rendering_breadcrumbs( string $block_content ): string {

	// Skip frontpage.
	if ( \is_front_page() ) {
		return '';
	}

	// Show WooCommerce breadcrumbs for pages which uses WooCommerce templates only.
	$show_woo_breadcrumbs = function_exists( '\is_woocommerce' ) && ( \is_woocommerce() || \is_cart() || \is_checkout() );

	switch ( \current_filter() ) {
		case 'render_block_woocommerce/breadcrumbs':
			if ( ! $show_woo_breadcrumbs ) {
				return '';
			}
			break;
		case 'render_block_yoast-seo/breadcrumbs':
			if ( $show_woo_breadcrumbs ) {
				return '';
			}
			break;
	}

	return $block_content;
}

/**
 * Materialize added block attributes from featured-single-post.js into block classnames for T2 Featured Content items.
 *
 * @param string $block_content Block content.
 * @param array  $parsed_block Block info.
 * @return string
 */
function do_append_extra_classes_to_featured_content( string $block_content, array $parsed_block ): string {
	$hide_image = $parsed_block['attrs']['hideImage'] ?? '';
	$show_title_with_red_dot = $parsed_block['attrs']['showTitleWithRedDot'] ?? '';

	if ( $hide_image || $show_title_with_red_dot ) {
		$processor = new \WP_HTML_Tag_Processor( $block_content );
		$processor->next_tag();
		$processor->add_class( classnames( [
			'has-hidden-image' => $hide_image,
			'has-show-title-with-red-dot' => $show_title_with_red_dot,
		] ) );

		return $processor->get_updated_html();
	}

	return $block_content;
}
