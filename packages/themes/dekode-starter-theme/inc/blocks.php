<?php
/**
 * Block-related logic.
 *
 * @package DekodeStarterTheme
 **/

declare( strict_types=1 );

namespace DekodeStarterTheme\Blocks;

/**
 * Blocks setup.
 *
 * @return void
 */
function setup(): void {
	\add_action( 'init', __NAMESPACE__ . '\\register_hook_block' );
	\add_filter( 'block_type_metadata', __NAMESPACE__ . '\\load_fallback_featured_image_from_theme' );
	\add_filter( 't2/query/render_hooks', __NAMESPACE__ . '\\t2_query_add_results_title_hook', 10, 1 );
	\add_action( 't2/query/results_title_html', __NAMESPACE__ . '\\t2_query_results_title_view', 10, 2 );
	\add_filter( 't2/query/get_results/response', __NAMESPACE__ . '\\t2_query_ajax_results_title', 10, 3 );
}

/**
 * Render the query results string.
 *
 * @param \WP_Query $query Block metadata.
 *
 * @return string
 */
function query_results_title_html( \WP_Query $query ): string {

	// Bail out if no posts.
	if ( ! $query->have_posts() ) {
		return '';
	}

	$total_posts = $query->found_posts;
	$per_page    = $query->get( 'posts_per_page' );
	$paged       = $query->get( 'paged' );
	$max_pages   = $query->max_num_pages;
	$lower_no    = 1;
	$max_no      = 1;

	if ( ! empty( $paged ) && absint( $paged ) > 1 ) {
		$lower_no = ( $paged - 1 ) * $per_page + 1;
		$max_no   = $paged * $per_page;
	} else {
		$max_no = $per_page > $total_posts ? $total_posts : $per_page;
	}

	// Take into consideration the maixum number of pages.
	if ( $max_pages <= 1 || absint( $paged ) === absint( $max_pages ) ) {
		$max_no = $total_posts;
	}

	// Localize the numbers.
	$lower_no    = \absint( \number_format_i18n( $lower_no ) );
	$max_no      = \absint( \number_format_i18n( $max_no ) );
	$total_posts = \absint( \number_format_i18n( $total_posts ) );
	$suffix      = \sprintf(
		\_n( 'result', 'results', $total_posts, 'dekode-starter-theme' ),
		$total_posts
	);

	$results_string = sprintf(
		// translators: 1: Lower number, 2: Max number, 3: Total posts, 4: Suffix (result/results).
		\esc_html__(
			'Showing %1$d - %2$d of %3$d %4$s',
			'dekode-starter-theme'
		),
		$lower_no,
		$max_no,
		$total_posts,
		$suffix
	);

	return sprintf(
		'<div class="results-title">%s</div>',
		\esc_html( $results_string )
	);
}

/**
 * Register the theme/hook helper block.
 * Offers the ability to render dynamic PHP content inside it.
 * <!-- wp:theme/hook {"hook":"my_hook","data":123} /--> will trigger a "theme/hook/my_hook" action, having access to a $data variable with 123 value.
 */
function register_hook_block(): void {
	\register_block_type(
		new \WP_Block_Type( 'theme/hook', [
			'apiVersion'      => 2,
			'name'            => 'theme/hook',
			'title'           => 'Hook block',
			'uses_context'    => [ 'postId', 'postType', 'siteId' ],
			'attributes'      => [ 'hook' => [ 'type' => 'string' ] ],
			'render_callback' => function ( array $attributes, string $content, \WP_Block $block ): string {
				if ( ! isset( $attributes['hook'] ) || empty( $attributes['hook'] ) ) {
					return '';
				}

				ob_start();

				\do_action(
					"theme/hook/{$attributes['hook']}",
					array_filter( $attributes, fn( string $key ) => $key !== 'hook', ARRAY_FILTER_USE_KEY ),
					$content,
					$block
				);

				return ob_get_clean();
			},
		] )
	);
}

/**
 * Load a custom fallback image from the theme folder for t2/post-featured-image blocks.
 *
 * @param array $metadata The block metadata.
 */
function load_fallback_featured_image_from_theme( array $metadata ): array {
	if ( $metadata['name'] !== 't2/post-featured-image' ) {
		return $metadata;
	}

	$metadata['attributes']['fallbackImage']['default'] = get_template_directory_uri() . '/assets/images/featured-image-fallback.svg';

	return $metadata;
}


/**
 * Inject a new "results_title" hook before the results.
 *
 * @param array $hooks The hooks.
 *
 * @return array
 */
function t2_query_add_results_title_hook( $hooks ): array {
	$position = array_search( 'results', $hooks, true );

	$hooks = array_merge(
		array_slice( $hooks, 0, $position, true ),
		[ 'results_title' ],
		array_slice( $hooks, $position, null, true )
	);

	return $hooks;
}

/**
 * Add a title before the results (page load).
 *
 * @param array     $attributes The block attributes.
 * @param \WP_Query $query      The query.
 *
 * @return void
 */
function t2_query_results_title_view( array $attributes, \WP_Query $query ): void {
	echo \wp_kses_post( query_results_title_html( $query ) );
}

/**
 * Inject the title before the results in a new AJAX fragment.
 *
 * @param array     $response The response.
 * @param array     $data     The request data.
 * @param \WP_Query $query    The query.
 *
 * @return array
 */
function t2_query_ajax_results_title( array $response, array $data, \WP_Query $query ): array {
	if ( $query->have_posts() ) {

		$response['fragments']['.results-title'] = query_results_title_html( $query );
	} else {
		$response['fragments']['.results-title'] = '';
	}

	return $response;
}
