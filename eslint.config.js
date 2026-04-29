/**
 * External dependencies
 */
const wordpress = require('@wordpress/eslint-plugin');

module.exports = [
	// Global ignores (replaces .eslintignore)
	{
		ignores: ['public/**', 'node_modules/**', 'dist/**', '**/build/**', 'vendor/**', '**/wp/**'],
	},
	// WordPress recommended flat config
	...wordpress.configs.recommended,
	// Project-specific overrides
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
	// Root config files use transitive deps — disable import rules for them
	{
		files: ['*.config.js', '.prettierrc.js', '.stylelintrc.js'],
		rules: {
			'import/no-extraneous-dependencies': 'off',
			'@wordpress/dependency-group': 'off',
		},
	},
];
