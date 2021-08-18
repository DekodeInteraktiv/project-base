<?php
/**
 * Displays sites entry
 *
 * @package dekode
 */

declare( strict_types=1 );

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	get_template_part( 'template-parts/singular/entry-header' );
	get_template_part( 'template-parts/singular/entry-content' );
	?>
</article>
