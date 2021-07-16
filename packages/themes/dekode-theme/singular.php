<?php
/**
 * The main single template file
 *
 * @package dekode
 */

declare( strict_types=1 );

get_header();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();

		get_template_part( 'template-parts/singular/entry' );
	}
}

get_footer();
