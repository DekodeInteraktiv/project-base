<?php
/**
 * Displays sites main search
 *
 * @package dekode
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Silence is golden.' );
}

?>
<div class="site-header--search">

	<button
		type="button"
		class="site-header--search--toggle"
		aria-controls="site-search"
		aria-expanded="false"
		aria-haspopup="true"
	>
		<svg
			aria-label="<?php esc_attr_e( 'Search', 'dekode' ); ?>"
			class="site-search__icon"
			xmlns="http://www.w3.org/2000/svg"
			viewBox="0 0 24 24"
		>
			<path d="M23.384,21.619,16.855,15.09a9.284,9.284,0,1,0-1.768,1.768l6.529,6.529a1.266,1.266,0,0,0,1.768,0A1.251,1.251,0,0,0,23.384,21.619ZM2.75,9.5a6.75,6.75,0,1,1,6.75,6.75A6.758,6.758,0,0,1,2.75,9.5Z" />
		</svg>
	</button>

	<div id="site-search" class="site-search" aria-hidden="true">
		<?php get_search_form(); ?>
	</div>

</div>
