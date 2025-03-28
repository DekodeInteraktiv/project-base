/**
 * External dependencies
 */
const postcssImport = require('postcss-import');
const postcssMixins = require('postcss-mixins');
const postcssUrl = require('postcss-url');
const postcssCustomMedia = require('postcss-custom-media');
const postcssMediaMinMax = require('postcss-media-minmax');
const postcssNesting = require('postcss-nesting');
const postcssDiscardComments = require('postcss-discard-comments');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');

module.exports = (ctx) => {
	const config = {
		plugins: [
			/* postcssGlobalData({
				files: [require.resolve('@teft/viewport/src/media.css')],
			}), */
			postcssImport,
			postcssMixins,
			postcssUrl,
			postcssCustomMedia,
			postcssMediaMinMax,
			postcssNesting({
				noIsPseudoSelector: true,
			}),
			postcssDiscardComments,
			autoprefixer,
		],
	};

	if (ctx.env === 'production') {
		config.plugins.push(cssnano());
	}

	return config;
};
