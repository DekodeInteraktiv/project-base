<?php
/**
 * The main template file
 *
 * @package dekode
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Silence is golden.' );
}

get_header();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
			</header>

			<?php if ( has_excerpt() ) : ?>
				<div class="entry-excerpt">
					<?php the_excerpt(); ?>
				</div>
			<?php endif; ?>
		</article>
		<?php
	}
}

get_footer();
