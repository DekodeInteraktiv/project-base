<?php
/**
 * WordPress stubs for benchmarks.
 *
 * WordPress is not loaded while benchmarking, so the hook registrations that
 * run when a package file is included need to exist as no-ops. Add further
 * stubs here when a benchmark needs them.
 *
 * @package Dekode
 */

declare( strict_types = 1 );

defined( 'ABSPATH' ) || define( 'ABSPATH', dirname( __DIR__, 2 ) . '/public/wp/' );

if ( ! \function_exists( 'add_filter' ) ) {
	/**
	 * No-op add_filter().
	 *
	 * @param string   $hook_name     Hook name.
	 * @param callable $callback      Callback.
	 * @param int      $priority      Priority.
	 * @param int      $accepted_args Accepted arguments.
	 * @return true
	 */
	function add_filter( string $hook_name, callable $callback, int $priority = 10, int $accepted_args = 1 ): true { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter -- Mirrors the WordPress signature.
		return true;
	}
}

if ( ! \function_exists( 'add_action' ) ) {
	/**
	 * No-op add_action().
	 *
	 * @param string   $hook_name     Hook name.
	 * @param callable $callback      Callback.
	 * @param int      $priority      Priority.
	 * @param int      $accepted_args Accepted arguments.
	 * @return true
	 */
	function add_action( string $hook_name, callable $callback, int $priority = 10, int $accepted_args = 1 ): true {
		return \add_filter( $hook_name, $callback, $priority, $accepted_args );
	}
}
