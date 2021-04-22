<?php
/**
 * Displays header site branding
 *
 * @package dekode
 */

declare( strict_types=1 );

$blog_info = get_bloginfo( 'name' );
?>
<div class="site-header--branding">
	<?php
	if ( has_custom_logo() ) {
		the_custom_logo();
	} elseif ( ! empty( $blog_info ) ) {
		printf( '<%1$s class="site-title"><a href="%2$s">%3$s</a></%1$s>',
			is_front_page() && is_home() ? 'h1' : 'p',
			esc_url( home_url( '/' ) ),
			esc_html( get_bloginfo( 'name', 'display' ) )
		);
	}
	?>
</div>
