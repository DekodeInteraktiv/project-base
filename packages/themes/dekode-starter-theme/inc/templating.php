<?php
/**
 * Templating logic.
 *
 * @package DekodeStarterTheme
 **/

declare( strict_types=1 );

namespace DekodeStarterTheme\Templating;

use function T2\Icons\get_icon;

/**
 * Template setup.
 *
 * @return void
 */
function setup(): void {
	\add_filter( 'excerpt_length', __NAMESPACE__ . '\\limit_excerpt_length' );
	\add_action( 'theme/hook/search-result-meta', __NAMESPACE__ . '\\render_search_result_meta' );
	\add_filter( 'wpseo_breadcrumb_separator', __NAMESPACE__ . '\\customize_yoast_separator' );
	\add_filter( 'register_post_type_args', __NAMESPACE__ . '\\default_block_templates', 10, 2 );
	\add_action( 'wp_footer', __NAMESPACE__ . '\\remove_action_wiki_aside_footer', 9 );

	/* Get active T2 blocks and extensions.*/
	$active_t2_extensions = [];
	$active_t2_blocks     = [];

	if ( \function_exists( '\T2\Extensions\get_active_extensions' ) ) {
		$active_t2_extensions = \T2\Extensions\get_active_extensions();
	}

	if ( \function_exists( '\T2\Blocks\get_active_blocks' ) ) {
		$active_t2_blocks = \T2\Blocks\get_active_blocks();
	}

	/* Add filters and actions based on active T2 blocks and extensions. */
	if ( \in_array( 't2/query', $active_t2_blocks, true ) ) {
		\add_filter( 't2/query/pagination_args', __NAMESPACE__ . '\\pagination_args', 10 );
	}

	if ( \in_array( 't2/wiki', $active_t2_extensions, true ) ) {
		\add_action( 'theme/hook/wiki-page-title', __NAMESPACE__ . '\\render_wiki_page_title' );
		\add_action( 'theme/hook/wiki-aside', __NAMESPACE__ . '\\render_wiki_aside' );
	}
}

/**
 * Limit excerpt length.
 */
function limit_excerpt_length(): int {
	return 20;
}

/**
 * Render a single search result meta line, based on post type.
 */
function render_search_result_meta(): void {
	global $post;

	$items           = [];
	$event_post_type = function_exists( 'DekodeEvents\Config\get_event_post_type' ) ? \DekodeEvents\Config\get_event_post_type() : 'event';
	$wiki_post_type  = function_exists( 'T2\Extensions\Library\Wiki\Config\get_wiki_post_type' ) ? \T2\Extensions\Library\Wiki\Config\get_wiki_post_type() : 'wiki';

	switch ( $post->post_type ) {
		default:
			break;
		// Post: publication date and list of category terms.
		case 'post':
			$items['date'] = sprintf(
				'<time datetime="%1$s">%2$s</time>',
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date() )
			);

			if ( has_category() ) {
				$items['terms-list'] = sprintf(
					'<span class="categories">%1$s</span>',
					wp_kses_post( get_the_category_list( ', ' ) )
				);
			}
			break;
		// Page: publication date.
		case 'page':
			$items['date'] = sprintf(
				'<time datetime="%1$s">%2$s</time>',
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date() )
			);
			break;
		// Event: event date and location.
		case $event_post_type:
			if ( function_exists( 'DekodeEvents\BlockLibrary\EventInfo\output_info_block_date_item' ) ) {
				ob_start();
				\DekodeEvents\BlockLibrary\EventInfo\output_info_block_date_item( $post, [], 'span', false );
				$items['date'] = ob_get_clean();
			}

			if ( function_exists( 'DekodeEvents\BlockLibrary\EventInfo\output_info_block_location_item' ) ) {
				ob_start();
				\DekodeEvents\BlockLibrary\EventInfo\output_info_block_location_item( $post, [], 'span', false );
				$items['location'] = ob_get_clean();
			}
			break;
		// Wiki: publication date and list of topic terms.
		case $wiki_post_type:
			$items['date'] = sprintf(
				'<time datetime="%1$s">%2$s</time>',
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date() )
			);

			if ( function_exists( 'T2\Extensions\Library\Wiki\Config\get_topic_taxonomy' ) ) {
				$topic_taxonomy = \T2\Extensions\Library\Wiki\Config\get_topic_taxonomy();
				$terms          = get_the_terms( $post, $topic_taxonomy );

				if ( ! empty( $terms ) ) {
					$items['terms-list'] = sprintf(
						'<span class="topics">%1$s</span>',
						wp_kses_post( get_the_term_list( $post->ID, $topic_taxonomy, '', ', ', '' ) )
					);
				}
			}
			break;
	}

	if ( ! empty( $items ) ) {
		printf(
			'<ul class="meta">%1$s</ul>',
			implode( '', array_map( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				function ( string $item_key, string $html ): string {
					return sprintf(
						'<li%2$s>%1$s</li>',
						$html,
						! empty( $item_key ) ? sprintf( ' class="%1$s"', esc_attr( $item_key ) ) : ''
					);
				},
				array_keys( $items ),
				array_values( $items )
			) )
		);
	}
}

/**
 * Render Wiki page title.
 */
function render_wiki_page_title(): void {
	$term = \get_queried_object();
	printf(
		'<h1 class="wiki-page-title">%1$s %2$s
			<span class="description">%3$s</span>
		</h1>',
		\esc_html__( 'Knowledge Base', 'dekode-starter-theme' ),
		\esc_html( $term->name ),
		\esc_html( $term->description )
	);
}

/**
 * Render Wiki aside table of contents
 */
function render_wiki_aside(): void {
	if ( \function_exists( 'T2\Extensions\Library\Wiki\Config\get_wiki_post_type' ) ) {
		if ( ! \is_singular( \T2\Extensions\Library\Wiki\Config\get_wiki_post_type() ) ) {
			return;
		}
	}

	if ( \function_exists( 'T2\Extensions\Library\Wiki\Templating\output_wiki_components_sidebar' ) ) {
		\T2\Extensions\Library\Wiki\Templating\output_wiki_components_sidebar();
	}
}

/**
 * Remove the wiki aside action footer.
 */
function remove_action_wiki_aside_footer(): void {
	if ( \function_exists( 'T2\Extensions\Library\Wiki\Config\get_wiki_post_type' ) ) {
		if ( ! \is_singular( \T2\Extensions\Library\Wiki\Config\get_wiki_post_type() ) ) {
			return;
		}
	}

	if ( \function_exists( 'T2\Extensions\Library\Wiki\Templating\output_wiki_components_sidebar' ) ) {
		\remove_action( 'wp_footer', 'T2\Extensions\Library\Wiki\Templating\output_wiki_components_sidebar' );
	}
}

/**
 * Register default block templates per post type.
 *
 * @param array  $args      Array of arguments for the block template.
 * @param string $post_type The post type.
 *
 * @return array
 */
function default_block_templates( array $args, string $post_type ): array {
	$wiki = get_post_type_object( 'wiki' );

	if ( 'wiki' === $post_type ) {
		$args['template'] = [
			[
				't2/section',
				[
					'layout'    => 'wide',
					'className' => 'wiki-featured-posts',
				],
				[
					[
						'core/heading',
						[
							'level'   => 2,
							'content' => esc_html__( 'Knowledge base', 'dekode-starter-theme' ),
						],
					],
					[
						't2/featured-content-layout',
						[
							'query' => [
								'perPage'  => 3,
								'pages'    => 1,
								'offset'   => 0,
								'postType' => 'wiki',
								'order'    => 'DESC',
								'orderBy'  => 'date',
							],
						],
						[
							[ 't2/featured-query-post' ],
							[ 't2/featured-query-post' ],
							[ 't2/featured-query-post' ],
						],
					],
				],
			],
		];
	}

	return $args;
}

/**
 * Set pagination args.
 *
 * @param array $arg The pagination args.
 *
 * @return array
 */
function pagination_args( array $arg ): array {
	$arg['end_size']  = 1;
	$arg['mid_size']  = 1;
	$arg['prev_text'] = sprintf( '%2$s<span>%1$s</span>', esc_html__( 'Previous', 'dekode-starter-theme' ), get_icon( 'arrowBack' ) );
	$arg['next_text'] = sprintf( '<span>%1$s</span>%2$s', esc_html__( 'Next', 'dekode-starter-theme' ), get_icon( 'arrowForward' ) );

	return $arg;
}

/**
 * Customize Yoast breadcrumb separator.
 *
 * @return string Customized separator.
 */
function customize_yoast_separator(): string {
	return '<span class="breadcrumb-separator">' . get_icon( 'chevronRight', '16', '16' ) . '</span>';
}
