/**
 * WordPress dependencies
 */
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const scriptConfig = require('@wordpress/scripts/config/webpack.config');

module.exports = {
	...scriptConfig,
	plugins: [
		...scriptConfig.plugins.filter(
			(plugin) => !['DependencyExtractionWebpackPlugin'].includes(plugin.constructor.name)
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
