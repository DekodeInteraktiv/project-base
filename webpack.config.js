/**
 * External dependencies
 */
const glob = require( 'fast-glob' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const fs = require( 'fs' );
const path = require( 'path' );
const BrowserSyncPlugin = require( 'browser-sync-webpack-plugin' );
require( 'dotenv' ).config();

/**
 * WordPress dependencies
 */
const DependencyExtractionWebpackPlugin = require( '@wordpress/dependency-extraction-webpack-plugin' );
const defaultConfig = require( './node_modules/@wordpress/scripts/config/webpack.config' );
const FixStyleWebpackPlugin = require( './node_modules/@wordpress/scripts/config/fix-style-webpack-plugin' );

const isProduction = process.env.NODE_ENV === 'production';
const mode = isProduction ? 'production' : 'development';

const browserSyncProxy = process.env.BROWSER_SYNC_PROXY ? process.env.BROWSER_SYNC_PROXY : process.env.WP_HOME;
const browserSyncPort = process.env.BROWSER_SYNC_PORT ? process.env.BROWSER_SYNC_PORT : 3002;
const browserSyncIsHttps = process.env.BROWSER_SYNC_HTTPS === 'true';
const browserSyncEnable = process.env.BROWSER_SYNC_ENABLE === 'true';

function getBuildPath( name, prefix = '' ) {
	return `${ name.split( '|' )[ 0 ] }/build/${ prefix }${ name.split( '|' )[ 1 ] }`;
}

async function getEntries() {
	const entries = {};
	let dirs = await glob( [ './packages/mu-plugins/*', './packages/plugins/*', './packages/themes/*' ], { onlyDirectories: true } );

	// Only include directories that contains a entry-files.json file.
	dirs = dirs.filter( ( dir ) => (
		fs.existsSync( path.resolve( dir, 'entry-files.json' ) )
	) );

	dirs.forEach( ( dir ) => {
		const entryFiles = require( `${ dir }/entry-files.json` ); // eslint-disable-line

		entryFiles.forEach( ( entry ) => {
			entries[ `${ dir }|${ entry }` ] = `${ dir }/src/${ entry }`;
		} );
	} );

	return entries;
}

/**
 * Config
 */
const config = {
	...defaultConfig,
	entry: async () => getEntries(),
	output: {
		path: process.cwd(),
		filename: ( { chunk } ) => {
			if ( chunk.name.includes( '.css' ) ) {
				return `${ getBuildPath( chunk.name.replace( '.css', '' ), 'style-' ) }.js`;
			}

			return getBuildPath( chunk.name );
		},
	},
	optimization: {
		concatenateModules: mode === 'production',
	},
	plugins: [
		new MiniCssExtractPlugin( {
			filename: ( { chunk } ) => getBuildPath( chunk.name ).replace( '.js', '.css' ),
		} ),
		new FixStyleWebpackPlugin(),
		new DependencyExtractionWebpackPlugin( { injectPolyfill: true } ),
	],
};

if ( browserSyncEnable ) {
	config.plugins.push(
		new BrowserSyncPlugin( {
			files: '**/*.php',
			proxy: browserSyncProxy,
			port: browserSyncPort,
			https: browserSyncIsHttps,
		} ),
	);
}

module.exports = config;
