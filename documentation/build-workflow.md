# Build workflow
The current Project Base setup is using the [npm workspaces](https://docs.npmjs.com/cli/v7/using-npm/workspaces) concept together with [Turbo](https://turbo.build/repo/docs) to improve performance and cover some edge cases.

This new architecture replaces the previous setup of using the non-standard `entry-files.json` configuration on each package.

## How to start
The Project Base `package.json` root configuration is already prepared to build the packages assets of themes, plugins or mu-plugins, as long as each package have their own `package.json` file containing at least the following configuration:

```json
{
	"name": "@dekode/my-package-name",
	"private": true,
	"version": "1.0.0",
	"scripts": {
		"build": "wp-scripts build --webpack-copy-php",
		"start": "wp-scripts start --webpack-copy-php",
		"clean": "rm -rf node_modules build dist .turbo",
	}
}
```
Note: This will be part of the scaffolding when using any of the [create block routines](create-block.md).

For project development, run `npm run start` from the project root, as before. This task goes through all the packages that have the start script and run them in paralel.

## Different build architectures
The official `@wordpress/scripts` package supports several entry points configuration to build the packages' assets:

* Automatic block.json detection and the source code directory
* Manual list of entry points
* Fallback entry point

### Automatic block.json detection
The source code directory (the default is ./src) and its subdirectories are scanned for the existence of block.json files. If one or more are found, the JavaScript files listed in metadata are treated as entry points and will be output into corresponding folders in the build directory. The script fields in block.json should pass relative paths to block.json in the same folder.

### Manual list of entry points
The simplest way to list JavaScript entry points is to pass them as arguments for the command. This is useful when the package have a fixed list of entry points and don't want to use block.json files (e.g. plugins that are not registering blocks, or the themes assets).

```json
"scripts": {
	...
	"build": "wp-scripts build src/index.js src/another.js",
	...
}
```

### Fallback entry point
The fallback entry point is src/index.js (other supported extensions: .jsx, .ts, and .tsx) in case there is no block.json file found or entry points declared. In that scenario, the output generated will be written to build/index.js. This is useful for simple packages that don't need to declare multiple entry points and are not registering any block.

## Advanced webpack configuration

To learn more about how to extend the default configuration, please refer to the [advanced webpack documentation](advanced-webpack.md).


## Browsersync

To learn more about how to enable browsersync, please refer to the [browsersync documentation](browsersync.md).

