/**
 * External dependencies
 */
require('dotenv').config();

const browserSync = require('browser-sync').create();

browserSync.init({
	files: ['packages/**/*.css', 'packages/**/*.js', 'packages/**/theme.json'],
	https: 'true' === process.env.BROWSER_SYNC_HTTPS,
	open: false,
	port: process.env.BROWSER_SYNC_PORT ?? 3002,
	proxy: process.env.BROWSER_SYNC_PROXY ?? process.env.WP_HOME,
});
