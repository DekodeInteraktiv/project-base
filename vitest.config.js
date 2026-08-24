/**
 * External dependencies
 */
const { defineConfig } = require('vitest/config');

module.exports = defineConfig({
	test: {
		benchmark: {
			include: ['**/*.bench.{js,ts}'],
			exclude: ['**/node_modules/**', '**/vendor/**', '**/build/**', '**/dist/**', '**/public/**'],
		},
	},
});
