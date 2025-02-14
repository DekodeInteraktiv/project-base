# Advanced webpack configuration

## External dependencies
In case the package has external dependencies (like relying on the T2 Editor component), then it is required to add an additional webpack.config.js file in the **package** root directory. This file should export a function that receives the default configuration and returns the modified configuration.

For example, to rely on the Project Base webpack root configuration, the file should look like this:

```js
/**
 * External dependencies
 */
const fs = require('fs');

/**
 * WordPress dependencies
 */
let scriptConfig = require('@wordpress/scripts/config/webpack.config');

/**
 * Internal dependencies
 */
if (fs.existsSync('../../../webpack.config.js')) {
	// Extend project root config if exists.
	scriptConfig = require('../../../webpack.config');
}

module.exports = {
	...scriptConfig,
};
```

Or, to extend the default configuration, the file should look like this:

```js
/* eslint-disable import/no-extraneous-dependencies */

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
```
This latest example is part of the scaffolding of the [create block routines](create-block.md).
