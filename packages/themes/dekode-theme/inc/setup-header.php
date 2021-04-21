<?php
/**
 * The header funtions file
 *
 * @package dekode
 */

declare( strict_types=1 );
namespace Dekode\Setup\Header;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Silence is golden.' );
}

add_action( 'dekode_header', __NAMESPACE__ . '\\default_header', 10 );

/**
 * Add default header container.
 */
function default_header() {

	$args = apply_filters( 'dekode/default_header/args', [
		'container_classes' => [
			apply_filters( 'dekode/container_classes', 'site-container' ),
		],
		'templates'         => [
			'template-parts/header/branding/branding',
			'template-parts/header/menu/menu',
			'template-parts/header/search/search',
		],
	] );

	$args = wp_parse_args( $args, [
		'container_classes' => [],
		'templates'         => [],
	] );

	if ( ! empty( $args['templates'] ) ) {
		printf( '<div class="%s">', esc_attr( implode( ' ', array_filter( $args['container_classes'] ) ) ) );

		foreach ( (array) $args['templates'] as $template ) {
			// Allow to hookup some extra action before the template part.
			do_action( 'dekode/default_header/before_template_part/' . sanitize_title( $template ) );

			get_template_part( $template );

			// Allow to hookup some extra action after the template part.
			do_action( 'dekode/default_header/after_template_part/' . sanitize_title( $template ) );
		}

		echo '</div>';
	}
}
