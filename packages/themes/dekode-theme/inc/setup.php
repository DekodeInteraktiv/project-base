<?php
/**
 * Setup theme
 *
 * @package dekode
 */

declare( strict_types=1 );
namespace Dekode\Setup;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Silence is golden.' );
}

add_action( 'after_setup_theme', __NAMESPACE__ . '\\setup' );
add_action( 'dekode_before_entry_content', __NAMESPACE__ . '\\add_entry_content_title', 10 );

/**
 * Setup Theme Functionality
 */
function setup() {
	/*
	 * Load the theme's textdomain.
	 */
	load_theme_textdomain( 'dekode', get_template_directory() . '/languages' );

	/*
	 * Let WordPress manage the document title.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Register menus.
	 */
	register_nav_menus( [
		'main-menu' => __( 'Primary', 'dekode' ),
	] );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', [
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	] );

	// Adding support for core block visual styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for full and wide align blocks.
	add_theme_support( 'align-wide' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	// Add post thumnails.
	add_theme_support( 'post-thumbnails' );

	// Add suppor for custom logo.
	add_theme_support( 'custom-logo' );
}

/**
 * Append Entry Content Title
 */
function add_entry_content_title() {
	?>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header>
	<?php
}
