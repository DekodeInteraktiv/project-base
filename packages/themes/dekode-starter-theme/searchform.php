<?php
/**
 * Search form.
 *
 * @package DekodeStarterTheme
 */

declare( strict_types=1 );

use function T2\Icons\icon;

?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php esc_html_e( 'Search for', 'dekode-starter-theme' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php esc_attr_e( 'Search', 'dekode-starter-theme' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	</label>
	<button type="submit" class="wp-block-button__link with-icon without-label">
		<span class="screen-reader-text"><?php esc_html_e( 'Search', 'dekode-starter-theme' ); ?></span>
		<?php icon( 'search' ); ?>
	</button>
</form>
