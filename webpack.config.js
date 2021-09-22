/* eslint-disable import/no-extraneous-dependencies, @wordpress/dependency-group */

/**
 * External dependencies
 */
const glob = require( 'fast-glob' );
const MiniCSSExtractPlugin = require( 'mini-css-extract-plugin' );
const fs = require( 'fs' );
const path = require( 'path' );
const BrowserSyncPlugin = require( 'browser-sync-webpack-plugin' );
const IgnoreEmitPlugin = require( 'ignore-emit-webpack-plugin' );
require( 'dotenv' ).config();

/**
 * WordPress dependencies
 */
const DependencyExtractionWebpackPlugin = require( '@wordpress/dependency-extraction-webpack-plugin' );
const defaultConfig = require( './node_modules/@wordpress/scripts/config/webpack.config' );

function getBuildPath( name ) {
	return `${ name.split( '|' )[ 0 ] }/build/${ name.split( '|' )[ 1 ] }`;
}

async function getEntries() {
	const entries = {};

	let dirs = await glob(
		[
			'./packages/mu-plugins/*',
			'./packages/plugins/*',
			'./packages/themes/*',
		],
		{ onlyDirectories: true }
	);

	// Only include directories that contains a entry-files.json file.
	dirs = dirs.filter( ( dir ) =>
		fs.existsSync( path.resolve( dir, 'entry-files.json' ) )
	);

	dirs.forEach( ( dir ) => {
		const entryFiles = require( `${ dir }/entry-files.json` ); // eslint-disable-line

		entryFiles.forEach( ( entry ) => {
			/**
			 * MiniCSSExtractPlugin handles css files. Rename them and ignore in
			 * IgnoreEmitPlugin to allow as direct entry files.
			 */
			entries[
				`${ dir }|${ entry.replace( '.css', '.css-entry-file' ) }`
			] = `${ dir }/src/${ entry }`;
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
		filename: ( { chunk } ) => getBuildPath( chunk.name ),
	},
	optimization: {
		...defaultConfig.optimization,
		splitChunks: {
			cacheGroups: {
				style: {
					type: 'css/mini-extract',
					test: /[\\/]style(\.module)?\.(sc|sa|c)ss$/,
					chunks: 'all',
					enforce: true,
					name( _, chunks ) {
						return getBuildPath(
							chunks[ 0 ].name.replace(
								'.css-entry-file',
								'.css'
							)
						);
					},
				},
				default: false,
			},
		},
	},
	resolve: {
		...defaultConfig.resolve,
		alias: {
			...defaultConfig.resolve.alias,
			components: path.resolve( __dirname, 'packages', 'components' ),
		},
	},
	plugins: [
		new MiniCSSExtractPlugin( { filename: '[name]' } ),
		new DependencyExtractionWebpackPlugin( { injectPolyfill: true } ),
		new IgnoreEmitPlugin( /\.css-entry-file$/ ),
	],
};

/**
 * Browsersync
 */
if ( 'true' === process.env.BROWSER_SYNC_ENABLE ) {
	config.plugins.push(
		new BrowserSyncPlugin( {
			files: '**/*.php',
			proxy: process.env.BROWSER_SYNC_PROXY ?? process.env.WP_HOME,
			port: process.env.BROWSER_SYNC_PORT ?? 3002,
			https: 'true' === process.env.BROWSER_SYNC_HTTPS,
		} )
	);
}

module.exports = config;
