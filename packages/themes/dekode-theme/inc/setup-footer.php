<?php
/**
 * The footer funtions file
 *
 * @package dekode
 */

declare( strict_types=1 );
namespace Dekode\Setup\Footer;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Silence is golden.' );
}

add_action( 'widgets_init', __NAMESPACE__ . '\\register_footer_widget_areas', 10 );
add_action( 'dekode_footer', __NAMESPACE__ . '\\default_footer', 10 );

/**
 * Register default footer widget area.
 */
function register_footer_widget_areas() {

	// Number of footer areas.
	$areas = intval( apply_filters( 'dekode/default_footer/areas', 3 ) );

	for ( $i = 1; $i <= $areas; $i++ ) {

		$args = apply_filters( 'dekode/default_footer/sidebar_args', [
			// Translators: %s Footer area number.
			'name'          => sprintf( __( 'Footer area %s', 'dekode' ), $i ),
			'id'            => 'footer-' . $i,
			'before_widget' => '<div class="footer-widget footer-widget-' . esc_attr( $i ) . '">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="footer-widget--title">',
			'after_title'   => '</h4>',
		], $i );

		register_sidebar( $args );
	}
}

/**
 * Add default footer container.
 */
function default_footer() {

	$args = apply_filters( 'dekode/default_footer/args', [
		'container_classes' => [
			apply_filters( 'dekode/container_classes', 'site-container' ),
			'footer-widgets',
		],
	] );

	$args = wp_parse_args( $args, [
		'container_classes' => [],
	] );

	printf( '<div class="%s">', esc_attr( implode( ' ', array_filter( $args['container_classes'] ) ) ) );

	// Number of footer areas.
	$areas = intval( apply_filters( 'dekode/default_footer/areas', 3 ) );

	for ( $i = 1; $i <= $areas; $i++ ) {
		if ( is_active_sidebar( 'footer-' . $i ) ) {
			dynamic_sidebar( 'footer-' . $i );
		}
	}

	echo '</div>';
}
