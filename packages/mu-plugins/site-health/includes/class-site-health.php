<?php
/**
 * Class file for the SiteHealth plugin module.
 *
 * @package Dekode\MUPlugin\SiteHealth
 */

declare( strict_types=1 );

namespace Dekode\MUPlugin\SiteHealth;

/**
 * Class SiteHealth
 *
 * @package Dekode\MUPlugin\SiteHealth
 */
class Site_Health {

	/**
	 * SiteHealth constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'site_status_tests', [ $this, 'remove_undesired_tests' ] );
		add_filter( 'site_status_test_result', [ $this, 'filter_debug_log_results' ] );
	}

	/**
	 * Checks if the current environment type is set to 'development' or 'local'.
	 *
	 * Copied from `WP_Site_Health::is_development_environment` to ensure availability in this plugin.
	 *
	 * @return bool True if it is a development environment, false if not.
	 */
	public function is_development_environment() : bool {
		return in_array( wp_get_environment_type(), [ 'development', 'local' ], true );
	}

	/**
	 * Filter the debug log result.
	 *
	 * If this is a development environment, give a more direct explanation of what the
	 * status of logging is on this site.
	 *
	 * @param array $result A Site Health test result.
	 *
	 * @return array
	 */
	public function filter_debug_log_results( array $result ) : array {
		// If this is a development environment, debug mode is expected to be on, so return the default output.
		if ( 'is_in_debug_mode' === $result['test'] && $this->is_development_environment() ) {
			$result = [
				'label'       => __( 'This site is providing debug information' ),
				'status'      => 'good',
				'badge'       => [
					'label' => __( 'Security' ),
					'color' => 'blue',
				],
				'description' => sprintf(
					'<p>%s</p>',
					__( 'This website is currently running in a development environment, and is expected to be able to output information relating to errors, warnings, and other information plugins or themes may expose in such a situation.' )
				),
				'actions'     => sprintf(
					'<p><a href="%s" target="_blank" rel="noopener noreferrer">%s <span class="screen-reader-text">%s</span><span aria-hidden="true" class="dashicons dashicons-external"></span></a></p>',
					/* translators: Documentation explaining debugging in WordPress. */
					esc_url( __( 'https://wordpress.org/support/article/debugging-in-wordpress/' ) ),
					__( 'Read about debugging in WordPress.' ),
					/* translators: accessibility text */
					__( '(opens in a new tab)' )
				),
				'test'        => 'is_in_debug_mode',
			];
		}

		return $result;
	}

	/**
	 * Filter the available tests on this site.
	 *
	 * Removes tests that are prone to give an incorrect result when considering
	 * the deployment and package environment used.
	 *
	 * @param array $tests An array of available tests.
	 *
	 * @return array
	 */
	public function remove_undesired_tests( array $tests ) : array {
		/*
		 * Since the project is managed via Composer, no default themes are installed, and theme versions are
		 * project specific, so would be unlikely to have matches on WordPress.org to compare against.
		 */
		unset( $tests['direct']['theme_version'] );

		/*
		 * Plugins are installed via Composer, it is not possible to update, install, or remove them via the
		 * WordPress back-end, and should they become inactive this is intentional, and they will instead be
		 * removed using Composer if they are no longer required.
		 */
		unset( $tests['direct']['plugin_version'] );

		/*
		 * Projects managed by Composer are usually maintained via version control, as such
		 * automated updates of any kind will be disabled.
		 */
		unset( $tests['direct']['plugin_theme_auto_updates'] );
		unset( $tests['async']['background_updates'] );

		return $tests;
	}

}
