<?php
/**
 * Production Environment
 *
 * @package Teft
 */

declare( strict_types = 1 );

// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_ini_set
ini_set( 'display_errors', '0' );

define( 'WP_DEBUG_DISPLAY', false );
define( 'SCRIPT_DEBUG', false );

// Disable all file modifications including updates and update notifications.
define( 'DISALLOW_FILE_MODS', true );
