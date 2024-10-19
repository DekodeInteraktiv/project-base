/**
 * External dependencies
 */
const { sync: globSync } = require('fast-glob');
const path = require('path');
const fs = require('fs');

/**
 * WordPress dependencies
 */
const { getWebpackEntryPoints } = require('@wordpress/scripts/utils/config');
let scriptConfig = require('@wordpress/scripts/config/webpack.config');

/**
 * Internal dependencies
 */
if (fs.existsSync('../../../webpack.config.js')) {
	// Extend project root config if exists.
	scriptConfig = require('../../../webpack.config');
}

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

module.exports = {
	...scriptConfig,
	entry: getPackageEntryPoints(),
};
