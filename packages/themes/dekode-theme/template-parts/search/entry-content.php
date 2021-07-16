<?php
/**
 * Displays sites search entry content
 *
 * @package dekode
 */

declare( strict_types=1 );

?>
<div class="entry-content">
	<?php if ( has_excerpt() ) : ?>
		<div class="entry-excerpt">
			<?php the_excerpt(); ?>
		</div>
	<?php endif; ?>
</div>
