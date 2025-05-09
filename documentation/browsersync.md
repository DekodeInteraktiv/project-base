# Browsersync

To enable browsersync, in the new turbo setup, the following scripts are needed in the project root `package.json` file:

```json
{
	"scripts": {
		...
		"browsersync:start": "node browsersync.config.js",
		...
		"start": "npm-run-all --parallel turbo:start browsersync:start",
		...
	}
}
```

Add the browsersync configuration in the `browsersync.config.js` file, on the project root:

```js
/**
 * External dependencies
 */
require('dotenv').config();

const browserSync = require('browser-sync').create();

browserSync.init({
	files: ['packages/**/*.css', 'packages/**/*.js', 'packages/**/theme.json'],
	https: 'true' === process.env.BROWSER_SYNC_HTTPS,
	open: false,
	port: process.env.BROWSER_SYNC_PORT ?? 3002,
	proxy: process.env.BROWSER_SYNC_PROXY ?? process.env.WP_HOME,
});
```

Run `npm run start` to start the browsersync server.