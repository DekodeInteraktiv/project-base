<?php
/**
 * Plugin Name:  Logging
 * Description:  Utility function for logging arbitrary variables to the error log.
 * Version:      1.0.0
 * Author:       Dekode Interaktiv
 *
 * @package Dekode/MU
 */

declare( strict_types=1 );

if ( ! function_exists( 'write_log' ) ) {

	/**
	 * Utility function for logging arbitrary variables to the error log.
	 *
	 * Set the constant WP_DEBUG to true and the constant WP_DEBUG_LOG to true to log to wp-content/debug.log.
	 * You can view the log in realtime in your terminal by executing `tail -f debug.log` and Ctrl+C to stop.
	 *
	 * @param mixed $log Whatever to log.
	 */
	function write_log( $log ) { // phpcs:ignore NeutronStandard.Functions.TypeHint.NoArgumentType
		if ( true === WP_DEBUG ) {
			if ( is_null( $log ) ) {
				error_log( 'NULL' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			} elseif ( is_scalar( $log ) ) {
				if ( is_bool( $log ) ) {
					error_log( $log ? 'true' : 'false' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
				} else {
					error_log( (string) $log ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
				}
			} else {
				error_log( print_r( $log, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log, WordPress.PHP.DevelopmentFunctions.error_log_print_r
			}
		}
	}
}
