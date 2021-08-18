<?php
/**
 * Search template
 *
 * @package dekode
 */

declare( strict_types=1 );

get_header();

get_template_part( 'template-parts/search/form' );

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();

		get_template_part( 'template-parts/search/entry' );
	}
}

get_footer();
