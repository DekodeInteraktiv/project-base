<?php
/**
 * Displays sites index entry
 *
 * @package dekode
 */

declare( strict_types=1 );

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	get_template_part( 'template-parts/index/entry-header' );
	get_template_part( 'template-parts/index/entry-content' );
	?>
</article>
