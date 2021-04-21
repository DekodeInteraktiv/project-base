<?php
/**
 * Displays sites main menu
 *
 * @package dekode
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Silence is golden.' );
}

// Return early if no menu exists.
if ( ! has_nav_menu( 'main-menu' ) ) {
	return;
}

?>
<button
	type="button"
	class="site-header--nav-toggle"
	aria-controls="site-header--nav"
	aria-expanded="false">
	<svg
		aria-label="<?php esc_attr_e( 'Menu', 'dekode' ); ?>"
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 0 24 24"
	>
		<rect x="0.5" y="2.5" width="23" height="3" rx="1" ry="1" />
		<rect x="0.5" y="10.5" width="23" height="3" rx="1" ry="1" />
		<rect x="0.5" y="18.5" width="23" height="3" rx="1" ry="1" />
	</svg>
	<span class="label"><?php echo esc_html__( 'Menu', 'dekode' ); ?></span>
</button>

<nav id="site-header--nav" class="site-header--nav" aria-label="<?php echo esc_attr__( 'Top Menu', 'dekode' ); ?>">
	<?php
	wp_nav_menu( [
		'theme_location' => 'main-menu',
		'menu_class'     => 'main-menu',
		'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
	] );
	?>
</nav>
