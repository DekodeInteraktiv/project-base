<?php
/**
 * The header for our theme
 *
 * @package dekode
 */

declare( strict_types=1 );
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

<a class="skip-link screen-reader-text" href="#content"><?php esc_attr_e( 'Skip to main content', 'dekode' ); ?></a>

<div class="site">
	<header id="masthead" class="site-header">
		<div class="site-container">
			<?php
			get_template_part( 'template-parts/header/branding/branding' );
			get_template_part( 'template-parts/header/menu/menu' );
			get_template_part( 'template-parts/header/search/search' );
			?>
		</div>
	</header>

	<main id="content" class="site-content">
