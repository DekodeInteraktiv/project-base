<?php
/**
 * The footer for our theme
 *
 * @package dekode
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Silence is golden.' );
}

do_action( 'dekode_after_content' );
?>
	</main><!-- .site-content -->

	<?php do_action( 'dekode_before_footer' ); ?>

	<footer class="site-footer">
		<?php
		/**
		 * Functions hooked into dekode_footer action
		 *
		 * @hooked Dekode\Setup\Footer\default_footer - 10
		 */
		do_action( 'dekode_footer' );
		?>
	</footer>

	<?php do_action( 'dekode_after_footer' ); ?>

</div><!-- .site -->

<?php wp_footer(); ?>
</body>
</html>
