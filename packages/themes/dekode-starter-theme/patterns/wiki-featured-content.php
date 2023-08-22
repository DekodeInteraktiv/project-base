<?php
/**
 * Title: Wiki Featured Content
 * Slug: dekode-starter-theme/wiki-featured-content
 * Categories: text
 * Description: Simple block grouping for showing Wiki featured content on different pages.
 * Keywords: wiki, featured content
 *
 * @package DekodeStarterTheme
 */

?>

<!-- wp:t2/section {"layout":"wide", "className":"wiki-featured-posts"} -->
<!-- wp:heading -->
<h2 class="wp-block-heading">
	<?php esc_html_e( 'Knowledge base', 'dekode-starter-theme' ); ?>
</h2>
<!-- /wp:heading -->

<!-- wp:t2/featured-content-layout /-->

<!-- wp:t2/post-archive-link {"postType":"wiki","buttonAlignment":"right"} /-->
<!-- /wp:t2/section -->
