module.exports = {
	extends: ['plugin:@wordpress/eslint-plugin/recommended'],
	globals: {
		wp: 'off',
	},
	env: {
		browser: true,
	},
	settings: {
		'import/core-modules': ['@t2/editor'],
	},
	rules: {
		'jsdoc/require-param': 'off',
		'@wordpress/no-global-event-listener': 'off',
		'@wordpress/dependency-group': 'error',
		'@wordpress/no-unsafe-wp-apis': 'error',
		'react-hooks/rules-of-hooks': 'off',
	},
};
