module.exports = {
	extends: [ 'plugin:@wordpress/eslint-plugin/recommended' ],
	globals: {
		wp: 'off',
	},
	rules: {
		'jsdoc/require-param': 'off',
		'@wordpress/no-global-event-listener': 'off',
		'@wordpress/dependency-group': 'error',
		'@wordpress/no-unsafe-wp-apis': 'error',
	},
};
