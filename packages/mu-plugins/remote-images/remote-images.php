<?php
/**
 * Plugin Name: Remote Images
 * Description: Use remote images in local development environment (simply define a constant DEKODE_PREPEND_IMAGE_URL with the URL you want to prepend to all attachments). Note that if the attachment exists locally the attachment URL will not be rewritten.
 * Version:     1.0.0
 * Author:      Dekode
 *
 * @package Dekode/MU
 */

declare( strict_types = 1 );

namespace Dekode\MUPlugins;

/**
 * Constants.
 */
if ( ! \defined( 'PREPEND_IMAGE_URL' ) ) {
	\define( 'PREPEND_IMAGE_URL', DEKODE_PREPEND_IMAGE_URL );
}
if ( ! \defined( 'PREPEND_CONTENT_FOLDER' ) ) {
	\define( 'PREPEND_CONTENT_FOLDER', ( defined( 'DEKODE_PREPEND_CONTENT_FOLDER' ) ? DEKODE_PREPEND_CONTENT_FOLDER : null ) );
}
if ( ! \defined( 'DEKODE_LOCAL_FILE_LOOKUP' ) ) {
	\define( 'LOCAL_FILE_LOOKUP', ( defined( 'DEKODE_LOCAL_FILE_LOOKUP' ) ? DEKODE_LOCAL_FILE_LOOKUP : true ) );
}
if ( ! \defined( 'SUBDOMAIN_SUPPORT' ) ) {
	\define( 'SUBDOMAIN_SUPPORT', ( defined( 'DEKODE_SUBDOMAIN_SUPPORT' ) ? DEKODE_SUBDOMAIN_SUPPORT : null ) );
}

/**
 * Hooks.
 */
\add_filter( 'wp_get_attachment_image_src', __NAMESPACE__ . '\\get_attachment_image_src', 10, 2 ); // Get correct attachment URL when running wp_get_attachment_image_src.
\add_filter( 'wp_calculate_image_srcset', __NAMESPACE__ . '\\calculate_image_srcset', 10, 5 ); // Get correct attachment URL when running wp_calculate_image_srcset.
\add_filter( 'wp_get_attachment_url', __NAMESPACE__ . '\\get_attachment_url', 10, 2 ); // Replace domain name in URL when running wp_get_attachment_url.

// Subdomain support.
if ( SUBDOMAIN_SUPPORT ) {
	sub_domain_support();
}

/**
 * Get correct attachment URL when running wp_calculate_image_srcset
 *
 * @param array  $sources Source data.
 * @param array  $size_array Image sizes.
 * @param string $image_src Image source.
 * @param array  $image_meta Image meta.
 * @param int    $attachment_id Image id.
 *
 * @return array.
 */
function calculate_image_srcset(
	array $sources,
	array $size_array,
	string $image_src,
	array $image_meta,
	int $attachment_id
) : array {
	if ( \is_array( $sources ) ) {
		foreach ( $sources as $key => $value ) {
			if ( isset( $value['url'] ) ) {
				$sources[ $key ]['url'] = get_attachment_url( $value['url'], $attachment_id );
			}
		}
	}

	return $sources;
}

/**
 * Get correct attachment URL when running wp_get_attachment_image_src.
 *
 * @param array|false $image Image object.
 * @param int         $attachment_id Image id.
 *
 * @return array|false.
 */
function get_attachment_image_src( $image, int $attachment_id ) : mixed { // phpcs:ignore NeutronStandard.Functions.TypeHint.NoArgumentType
	if ( \is_array( $image ) && isset( $image[0] ) ) {
		$image[0] = get_attachment_url( $image[0], $attachment_id );
	}

	return $image;
}

/**
 * Replace domain name in URL.
 *
 * @param string $url The attachment url.
 * @param int    $attachment_id The attachment id.
 *
 * @return string.
 */
function get_attachment_url( string $url, int $attachment_id ) : string {
	// Do nothing if file exists locally.
	if ( LOCAL_FILE_LOOKUP && $attachment_id && \file_exists( \get_attached_file( $attachment_id, true ) ) ) {
		return $url;
	}

	// Replace URL with regex.
	$replacement = \sprintf( '%s$4', \rtrim( PREPEND_IMAGE_URL, '/' ) );
	$url         = \preg_replace( '/^(http(s|):\/\/)([^\/]+)(\/.*)$/i', $replacement, $url );

	// Make sure that content folder has correct name.
	if ( PREPEND_CONTENT_FOLDER ) {
		$replacement = \sprintf( '$1/%s/$3', \trim( PREPEND_CONTENT_FOLDER, '/' ) );
		$url         = \preg_replace( '/^(.+)\/(wp-|)content\/(.+)$/i', $replacement, $url );
	}

	return $url;
}

/**
 * Extract sub-domain from a hostname.
 *
 * @param string $hostname Hostname.
 *
 * @return bool|string
 */
function extract_sub_domains_from_hostname( string $hostname ) : mixed {
	// Split the domain into array.
	$host = \explode( '.', $hostname );

	// Only domain and TLD, no subdomain.
	if ( 2 <= \count( $host ) ) {
		return false;
	}

	// Get subdomains and concatenate.
	$sub_domains        = \array_slice( $host, 0, \count( $host ) - 2 );
	$sub_domains_string = \implode( '.', $sub_domains );

	return $sub_domains_string ?: false;
}

/**
 * Get current hostname.
 *
 * @return string|false
 */
function get_hostname() : mixed {
	return isset( $_SERVER['HTTP_HOST'] ) ? \esc_url_raw( $_SERVER['HTTP_HOST'] ) : false; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
}

/**
 * Add sub-domain(s) to an URL.
 *
 * @param string $url URL.
 * @param string $sub_domains Subdomains.
 */
function add_sub_domain( string $url, string $sub_domains ) : string {
	$replace_string = '//';
	return \str_replace( $replace_string, $replace_string . \trim( \trim( $sub_domains ), '.' ) . '.', $url );
}

/**
 * Append sub-domain to URL if present.
 */
function sub_domain_support() : void {
	$hostname    = get_hostname();
	$sub_domains = extract_sub_domains_from_hostname( $hostname );

	if ( $hostname && $sub_domains ) {
		\define( 'PREPEND_IMAGE_URL', add_sub_domain( PREPEND_IMAGE_URL, $sub_domains ) );
	}
}
