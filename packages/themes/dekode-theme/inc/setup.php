<?php
/**
 * Setup theme
 *
 * @package dekode
 */

declare( strict_types=1 );
namespace Dekode\Setup;

/**
 * Hooks
 */
\add_action( 'after_setup_theme', __NAMESPACE__ . '\\setup' );
\add_action( 'widgets_init', __NAMESPACE__ . '\\register_footer_widget_area', 10 );

/**
 * Setup Theme Functionality
 */
function setup() {
	/*
	 * Load the theme's textdomain.
	 */
	\load_theme_textdomain( 'dekode', \get_template_directory() . '/languages' );

	/*
	 * Let WordPress manage the document title.
	 */
	\add_theme_support( 'title-tag' );

	/*
	 * Register menus.
	 */
	\register_nav_menus( [
		'main-menu' => \__( 'Primary', 'dekode' ),
	] );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	\add_theme_support( 'html5', [
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	] );

	// Adding support for core block visual styles.
	\add_theme_support( 'wp-block-styles' );

	// Add support for full and wide align blocks.
	\add_theme_support( 'align-wide' );

	// Add support for responsive embeds.
	\add_theme_support( 'responsive-embeds' );

	// Add post thumnails.
	\add_theme_support( 'post-thumbnails' );

	// Add support for custom logo.
	\add_theme_support( 'custom-logo' );
}

/**
 * Register default footer widget area.
 */
function register_footer_widget_area() {
	\register_sidebar( [
		'name'          => esc_html__( 'Footer area', 'dekode' ),
		'id'            => 'footer',
		'before_widget' => '<div class="footer-widget">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="footer-widget-title">',
		'after_title'   => '</h4>',
	] );
}
