/**
 * External dependencies
 */
/* const postcssGlobalData = require('@csstools/postcss-global-data'); */
const postcssImport = require('postcss-import');
const postcssUrl = require('postcss-url');
const postcssCustomMedia = require('postcss-custom-media');
const postcssMediaMinMax = require('postcss-media-minmax');
const postcssNesting = require('postcss-nesting');
const postcssDiscardComments = require('postcss-discard-comments');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');

module.exports = {
	plugins: [
		/* postcssGlobalData({
			files: [require.resolve('@teft/viewport/src/media.css')],
		}), */
		postcssImport,
		postcssUrl,
		postcssCustomMedia,
		postcssMediaMinMax,
		postcssNesting,
		postcssDiscardComments,
		autoprefixer,
		cssnano,
	],
};
