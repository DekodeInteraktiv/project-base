<?php
/**
 * Displays sites search entry header
 *
 * @package dekode
 */

declare( strict_types=1 );

?>
<header class="entry-header">
	<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
</header>
