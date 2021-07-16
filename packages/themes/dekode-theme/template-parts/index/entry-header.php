<?php
/**
 * Displays sites index entry header
 *
 * @package dekode
 */

declare( strict_types=1 );

?>
<header class="entry-header">
	<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
</header>
