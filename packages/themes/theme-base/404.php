<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package dekode
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Silence is golden.' );
}

get_header();
?>

<main id="main" class="site-main">
	<header class="entry-header">
		<h1 class="entry-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'dekode' ); ?></h1>
	</header>
	<div class="entry-content">
		<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'dekode' ); ?></p>
		<?php get_search_form(); ?>
	</div>
</main>

<?php
get_footer();
