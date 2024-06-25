/* eslint-disable import/no-extraneous-dependencies, @wordpress/dependency-group */

/**
 * External dependencies
 */
const MiniCSSExtractPlugin = require('mini-css-extract-plugin');
const path = require('path');
const BrowserSyncPlugin = require('browser-sync-v3-webpack-plugin');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');
const webpack = require('webpack');
require('dotenv').config();

/**
 * WordPress dependencies
 */
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const defaultConfig = require('./node_modules/@wordpress/scripts/config/webpack.config');
const { getWebpackEntryPoints } = require('@wordpress/scripts/utils/config');

/**
 * Patches config to use resolve-url-loader for relative paths in SCSS files
 * It will be possible to use './images/png.png' inside the SCSS,
 *
 * All paths will be treated as relative to file, not project root.
 */
for (const rule of defaultConfig.module.rules) {
	if ('any-filename-to-test.scss'.match(rule.test)) {
		const sassLoaderConfig = rule.use.pop();

		// We mutate default config to change behaviour.
		rule.use = [
			...rule.use,
			// resolve-url-loader should be executed right after sass-loader.
			{
				loader: require.resolve('resolve-url-loader'),
				options: {
					sourceMap: sassLoaderConfig.options.sourceMap,
					removeCR: true,
				},
			},
			{
				...sassLoaderConfig,
				options: {
					...sassLoaderConfig.options,
					sourceMap: true, // It's required to have sourceMap for resolve-url-loader, resolve-url-loader will keep or drop maps on it's step.
				},
			},
		];
	}
}

const config = {
	...defaultConfig,
	entry: getWebpackEntryPoints(),
	optimization: {
		...defaultConfig.optimization,
		removeEmptyChunks: true,

		splitChunks: {
			cacheGroups: {
				internalStyle: {
					type: 'css/mini-extract',
					test: /[\\/]+?\.(sc|sa|c)ss$/,
					chunks: 'all',
					enforce: true,
				},
				default: false,
			},
		},
	},
	resolve: {
		...defaultConfig.resolve,
		alias: {
			...defaultConfig.resolve.alias,
			components: path.resolve(__dirname, 'packages', 'components'),
		},
	},

	// Hides rarely used information for more compact appearance of console.
	stats: {
		children: false,
		all: false,
		entrypoints: true,
		warnings: true,
		errors: true,
		hash: false,
		timings: true,
		errorDetails: true,
		builtAt: true,
	},

	plugins: [
		new MiniCSSExtractPlugin({ filename: '[name].css' }),

		/**
		 * It removes empty JS files, when we use CSS/SCSS as main entrypoint of asset.
		 *
		 * It's possible to remove also `.asset.php` by writing custom version
		 */
		new RemoveEmptyScriptsPlugin(),

		new DependencyExtractionWebpackPlugin({ injectPolyfill: true }),

		(process.argv || []).includes('--progress') &&
			new webpack.ProgressPlugin(),
	].filter(Boolean),
};

if ('true' === process.env.BROWSER_SYNC_ENABLE) {
	const browserSyncConfig = {
		...defaultConfig,
		name: 'BrowserSync',
		plugins: [
			new BrowserSyncPlugin(
				{
					files: ['packages/**/*.css', 'packages/**/*.js'],
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

	config.push(browserSyncConfig);
}

/**
 * We can use Multi-Config mode to build packages in 'sandboxed' mode and parallel.
 *
 * https://webpack.js.org/configuration/configuration-types/#exporting-multiple-configurations
 */
module.exports = config;
