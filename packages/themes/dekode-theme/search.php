<?php
/**
 * Search template
 *
 * @package dekode
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Silence is golden.' );
}

get_header();

get_search_form();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		?>
		<header class="entry-header">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		</header>
		<?php
	}
}

get_footer();
