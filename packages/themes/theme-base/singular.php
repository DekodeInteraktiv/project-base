<?php
/**
 * The main single template file
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

			<?php
			/**
			 * Functions hooked into dekode_before_entry_content action
			 *
			 * @hooked Dekode\Setup\add_entry_content_title - 10
			 */
			do_action( 'dekode_before_entry_content', get_post() );
			?>

			<div class="entry-content">
				<?php the_content(); ?>
			</div>

			<?php do_action( 'dekode_after_entry_content', get_post() ); ?>
		</article>
		<?php
	}
}

get_footer();
