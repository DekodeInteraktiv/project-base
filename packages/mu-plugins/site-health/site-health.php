<?php
/**
 * Plugin Name:  Site Health Quality Assurance
 * Description:  Utility plugin to ensure Site Health reports match expectations of Project Base.
 * Version:      1.0.0
 * Author:       Dekode Interaktiv
 * Text Domain:  dekode-muplugin-site-health
 *
 * @package Dekode/MU
 */

declare( strict_types=1 );

namespace Dekode\MUPlugin\SiteHealth;

require_once __DIR__ . '/includes/class-site-health.php';

new Site_Health();
