/**
 * External dependencies
 */
const { join } = require('path');

/**
 * Internal dependencies
 */
const { getRoot, getDirs } = require('./tools/utils');

const ROOT = getRoot();
const THEMES_DIR_PATH = join(ROOT, 'packages/themes');

const themes = getDirs(THEMES_DIR_PATH).reduce((memo, blockDir) => {
	const hasThemeJsonDir = getDirs(join(THEMES_DIR_PATH, blockDir)).includes(
		'theme-json',
	);

	// Skip themes without a theme-json directory.
	if (!hasThemeJsonDir) {
		return memo;
	}

	memo = [
		...memo,
		{
			src: `./packages/themes/${blockDir}/theme-json`,
			dest: `./packages/themes/${blockDir}/theme.json`,
			pretty: false,
			version: 2,
			plugins: [],
		},
	];

	return memo;
}, []);

module.exports = themes;
