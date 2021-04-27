/**
 * External dependencies
 */
const plugins = require( '@teft/postcss-preset' );
const cssnano = require( 'cssnano' );

module.exports = ( ctx ) => {
	const config = {
		plugins,
	};

	if ( ctx.env === 'production' ) {
		config.plugins.push( cssnano() );
	}

	return config;
};
