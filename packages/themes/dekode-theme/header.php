<?php
/**
 * The header for our theme
 *
 * @package dekode
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Silence is golden.' );
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="site hfeed">

	<?php do_action( 'dekode_before_header' ); ?>

	<header id="masthead" class="site-header">
		<?php
		/**
		 * Functions hooked into dekode_header action
		 *
		 * @hooked Dekode\Setup\Header\default_header - 10
		 */
		do_action( 'dekode_header' );
		?>
	</header>

	<?php do_action( 'dekode_after_header' ); ?>

	<main id="content" class="site-content">
		<?php do_action( 'dekode_before_content' ); ?>
