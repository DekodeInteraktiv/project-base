// Import the default config file and expose it in the project root.
// Useful for editor integrations.
module.exports = require('@wordpress/prettier-config');
module.exports.overrides = [
	{
		files: ['*.css'],
		options: {
			printWidth: 1000,
			singleQuote: false,
		},
	},
	{
		files: ['*.js'],
		options: {
			printWidth: 80,
			trailingComma: 'es5',
		},
	},
];
