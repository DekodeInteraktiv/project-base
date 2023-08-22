/**
 * External dependencies
 */
const atImport = require('postcss-import');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const customMedia = require('postcss-custom-media');
const minmax = require('postcss-media-minmax');
const mixins = require('postcss-mixins');
const postcssNesting = require('postcss-nesting');
const postcssFlexbugsFixes = require('postcss-flexbugs-fixes');
const postcssGlobalData = require('@csstools/postcss-global-data');

module.exports = (ctx) => {
	const config = {
		plugins: [
			postcssGlobalData({
				files: [
					'./packages/themes/dekode-starter-theme/src/config/media.css',
					'./packages/themes/dekode-starter-theme/src/config/mixins.css',
				],
			}),
			atImport(),
			mixins,
			postcssFlexbugsFixes,
			postcssNesting({
				noIsPseudoSelector: true,
			}),
			customMedia(),
			minmax(),
			autoprefixer,
		],
	};

	if (ctx.env === 'production') {
		config.plugins.push(cssnano());
	}

	return config;
};
