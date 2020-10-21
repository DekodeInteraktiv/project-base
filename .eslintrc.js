module.exports = {
	extends: [
		'plugin:@dekode/base',
		'plugin:@dekode/react',
	],
	plugins: [
		'@dekode',
	],
	settings: {
		react: {
			version: '16.9.0',
		},
	},
	rules: {
		'react/react-in-jsx-scope': 'off',
		'react/jsx-pascal-case': 'off',
		'react/jsx-props-no-spreading': 'off',
		'no-undefined': 'off',
		'import/no-unresolved': [ 'error', {
			ignore: [ '@wordpress' ],
		} ],
		'jsx-a11y/media-has-caption': 'off',
	},
};
