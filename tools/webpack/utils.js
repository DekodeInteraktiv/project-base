/**
 * External dependencies
 */
const fs = require('fs');
const path = require('path');

/**
 * Returns true if the given base file name for a file within directory is
 * itself a directory.
 *
 * @param {string} file Entry file.
 * @param {string} dir  Entry dir.
 */
function isDirectory(file, dir) {
	return fs.lstatSync(path.resolve(dir, file)).isDirectory();
}

/**
 * Get dirs within a directory.
 *
 * @param {string} dir Directory to get dirs from.
 */
function getDirs(dir) {
	return fs.readdirSync(dir).filter((file) => isDirectory(file, dir));
}

module.exports = {
	getDirs,
};
