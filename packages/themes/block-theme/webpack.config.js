/* eslint-disable import/no-extraneous-dependencies */

/**
 * External dependencies
 */
const { sync: globSync } = require('fast-glob');
const path = require('path');

/**
 * WordPress dependencies
 */
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { getWebpackEntryPoints } = require('@wordpress/scripts/utils/config');

function getPackageEntryPoints() {
	// Use default entry points from wp-scripts.
	const entries = getWebpackEntryPoints();

	// Append entry points for each block stylesheet.
	globSync('./src/blocks/*/*.css', { onlyFiles: true }).forEach((file) => {
		const parentDir = path.parse(file).dir.split(path.sep).pop();
		const fileName = path.parse(file).name;
		entries[`blocks/${parentDir}/${fileName}`] = file;
	});

	return entries;
}

module.exports = {
	...defaultConfig,
	entry: getPackageEntryPoints(),
	plugins: [
		...defaultConfig.plugins.filter(
			(plugin) =>
				plugin.constructor.name !== 'DependencyExtractionWebpackPlugin',
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
	],
};
