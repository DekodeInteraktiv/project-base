/**
 * WordPress dependencies
 */
const defaultConfig = require('@wordpress/scripts/config/eslint.config.cjs');

module.exports = [
	...defaultConfig,
	{
		ignores: ['**/build/**', '**/node_modules/**', '**/vendor/**', '**/public/**', '**/dist/**', '**/wp/**'],
	},
	{
		languageOptions: {
			globals: {
				wp: 'off',
			},
		},
		settings: {
			'import/core-modules': ['@t2/editor'],
			'import/resolver': {
				node: {
					extensions: ['.js', '.jsx', '.ts', '.tsx'],
				},
			},
		},
		rules: {
			'jsdoc/require-param': 'off',
			'@wordpress/no-global-event-listener': 'off',
			'@wordpress/dependency-group': 'error',
			'@wordpress/no-unsafe-wp-apis': 'error',
			'react-hooks/rules-of-hooks': 'off',
		},
	},
	{
		files: ['**/*.bench.{js,ts}'],
		rules: {
			'import/no-extraneous-dependencies': ['error', { packageDir: __dirname, peerDependencies: true }],
		},
	},
];
