/**
 * External dependencies
 */
const BrowserSyncPlugin = require('browser-sync-v3-webpack-plugin');

/**
 * WordPress dependencies
 */
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const scriptConfig = require('@wordpress/scripts/config/webpack.config');

module.exports = {
	...scriptConfig,
	plugins: [
		...scriptConfig.plugins.filter(
			(plugin) =>
				![
					'DependencyExtractionWebpackPlugin',
					'BrowserSyncPlugin',
				].includes(plugin.constructor.name),
		),
		new DependencyExtractionWebpackPlugin({
			injectPolyfill: true,
			requestToHandle(request) {
				if (request.startsWith('@t2/')) {
					return `t2-${request.substring(4)}`;
				}

				return undefined;
			},
			requestToExternal(request) {
				if (request.startsWith('@t2/')) {
					return ['t2', request.substring(4)];
				}

				return undefined;
			},
		}),
		new BrowserSyncPlugin(
			{
				files: ['packages/**/*.css', 'packages/**/*.js'],
				proxy: process.env.BROWSER_SYNC_PROXY ?? process.env.WP_HOME,
				port: process.env.BROWSER_SYNC_PORT ?? 3002,
				https: 'true' === process.env.BROWSER_SYNC_HTTPS,
			},
			{
				reload: false,
			},
		),
	],
};
