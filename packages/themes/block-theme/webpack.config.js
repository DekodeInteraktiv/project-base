/**
 * External dependencies
 */
const { sync: globSync } = require('fast-glob');
const path = require('path');
const BrowserSyncPlugin = require('browser-sync-v3-webpack-plugin');
require('dotenv').config();

/**
 * WordPress dependencies
 */
const { getWebpackEntryPoints } = require('@wordpress/scripts/utils/config');

/**
 * Internal dependencies
 */
const rootConfig = require('../../../webpack.config');

function getPackageEntryPoints() {
	// Use default entry points from wp-scripts.
	const entries = getWebpackEntryPoints('script')();

	// Append entry points for each block stylesheet.
	globSync('./src/blocks/*/*.css', { onlyFiles: true }).forEach((file) => {
		const parentDir = path.parse(file).dir.split(path.sep).pop();
		const fileName = path.parse(file).name;
		entries[`blocks/${parentDir}/${fileName}`] = file;
	});

	return entries;
}

const config = {
	...rootConfig,
	entry: getPackageEntryPoints(),
};

if ('true' === process.env.BROWSER_SYNC_ENABLE) {
	module.exports = {
		...config,
		plugins: [
			...config.plugins.filter(
				(plugin) => plugin.constructor.name !== 'BrowserSyncPlugin',
			),
			new BrowserSyncPlugin(
				{
					files: ['**/*.css', '**/*.js'],
					proxy:
						process.env.BROWSER_SYNC_PROXY ?? process.env.WP_HOME,
					port: process.env.BROWSER_SYNC_PORT ?? 3002,
					https: 'true' === process.env.BROWSER_SYNC_HTTPS,
				},
				{
					reload: false,
				},
			),
		].filter(Boolean),
	};
}

module.exports = config;
