<?php
/**
 * Dekode Starter Theme functions and definitions.
 *
 * @package DekodeStarterTheme
 **/

declare( strict_types=1 );

namespace DekodeStarterTheme;

/**
 * Load vital files.
 */
require_once __DIR__ . '/inc/theme-setup.php';
require_once __DIR__ . '/inc/assets.php';
require_once __DIR__ . '/inc/icons.php';
require_once __DIR__ . '/inc/blocks.php';
require_once __DIR__ . '/inc/templating.php';

/**
 * Load all block.php files found in the /src/blocks/* subfolders.
 */
foreach ( glob( __DIR__ . '/src/blocks/*', GLOB_ONLYDIR ) as $dir ) {
	if ( file_exists( "$dir/block.php" ) ) {
		require_once "$dir/block.php";
	}
}

Assets\setup();
Icons\setup();
Blocks\setup();
Setup\setup();
Templating\setup();
