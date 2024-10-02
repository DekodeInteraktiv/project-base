/**
 * External dependencies
 */
const { sync: globSync } = require('fast-glob');
const path = require('path');

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

module.exports = {
	...rootConfig,
	entry: getPackageEntryPoints(),
};
