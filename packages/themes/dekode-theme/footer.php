<?php
/**
 * The footer for our theme
 *
 * @package dekode
 */

declare( strict_types=1 );
?>
	</main><!-- .site-content -->

	<footer class="site-footer">
		<div class="site-container site-footer-widgets">
			<?php
			if ( is_active_sidebar( 'footer' ) ) {
				dynamic_sidebar( 'footer' );
			}
			?>
		</div>
	</footer>
</div><!-- .site -->

<?php wp_footer(); ?>
</body>
</html>
