/**
 * External dependencies
 */
const { existsSync } = require('fs');
const { join, resolve } = require('path');
const CopyWebpackPlugin = require('copy-webpack-plugin');

/**
 * WordPress dependencies
 */
const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');

/**
 * Internal dependencies
 */
const { getDirs } = require('./utils');

const EXTERNAL_SCRIPTS = {};

const ROOT = resolve(process.cwd());
const PLUGIN_DIR_PATH = join(
	ROOT,
	'packages/mu-plugins/project-base-block-library'
);
const LIBRARY_DIR_PATH = join(PLUGIN_DIR_PATH, 'inc');
const entryPoints = ['index', 'script', 'view'];
const extensions = ['ts', 'js', 'tsx'];

const blocks = getDirs(LIBRARY_DIR_PATH).reduce((memo, blockDir) => {
	// Skip utils directory.
	if (blockDir === 'utils') {
		return memo;
	}

	for (const entryPoint of entryPoints) {
		for (const ext of extensions) {
			const filePath = join(
				LIBRARY_DIR_PATH,
				blockDir,
				`${entryPoint}.${ext}`
			);

			if (existsSync(filePath)) {
				memo[`${blockDir}/${entryPoint}`] = filePath;
				break;
			}
		}
	}

	return memo;
}, {});

module.exports = () => {
	return {
		...defaultConfig,
		name: 'block-library',
		entry: blocks,
		output: {
			filename: '[name].js',
			path: join(PLUGIN_DIR_PATH, 'build'),
		},
		plugins: [
			...defaultConfig.plugins.filter(
				(plugin) =>
					![
						'CopyWebpackPlugin',
						'DependencyExtractionWebpackPlugin',
					].includes(plugin.constructor.name)
			),
			new CopyWebpackPlugin({
				patterns: [
					{
						from: '**/{block.json,*.php,*.svg}',
						context: LIBRARY_DIR_PATH,
						noErrorOnMissing: true,
					},
				],
			}),
			new DependencyExtractionWebpackPlugin({
				injectPolyfill: true,
				requestToHandle(request) {
					if (request === '@t2/editor') {
						return 't2-editor';
					}

					if (request in EXTERNAL_SCRIPTS) {
						return request;
					}
				},
				requestToExternal(request) {
					if (request === '@t2/editor') {
						return ['t2', 'editor'];
					}

					if (request in EXTERNAL_SCRIPTS) {
						return EXTERNAL_SCRIPTS[request];
					}
				},
			}),
		],
	};
};
