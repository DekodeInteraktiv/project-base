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
require_once __DIR__ . '/inc/blocks.php';
require_once __DIR__ . '/inc/templating.php';

Assets\setup();
Blocks\setup();
Setup\setup();
Templating\setup();
