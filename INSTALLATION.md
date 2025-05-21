# Dekode Project Base - Installation Guide

## Requirements

- **PHP**: Version 8.2 or higher
- **Composer**: [Install Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)
- **Node.js**: Version 18.0

## Project Structure

- **Packages**: Contains all project-specific code such as plugins, themes, mu-plugins, and custom libraries. These are built into the `public` directory using symlinks via Composer.
- **Public**: Contains files used by WordPress, automatically generated from packages by Composer. This folder should not be modified manually.
- **Tools**: Contains build and setup scripts used by Codeship and Local.
- **Config**: Contains environment variable setup files.

## Local Setup

### Initial Setup

1. **Create a Git Repository**:
   - If setting up a new project, create a git repository using [Project Base](https://github.com/DekodeInteraktiv/project-base) as the template.

2. **Create a New Site in Local**:
   - Enable multisite (subdirectory) if applicable to the project.

### Setup Methods

#### Method 1: Replicating Server Structure

1. Navigate to the app folder:
   ```bash
   cd app
   ```

2. Remove the default public folder:
   ```bash
   rm -rf public
   ```

3. Clone the project into the current folder:
   ```bash
   git clone git@github.com:DekodeInteraktiv/{YOUR_PROJECT} .
   ```

4. Copy the environment example file:
   ```bash
   cp .env.example .env
   ```

5. Update environment variables in the `.env` file:
   - `DB_NAME`, `DB_USER`, `DB_PASSWORD`, `DB_HOST`
   - `WP_ENVIRONMENT_TYPE` (local, development, staging, production)
   - `WP_HOME` (e.g., `http://example.com`)
   - `WP_SITEURL` (e.g., `http://example.com/wp`)
   - `AUTH_KEY`, `SECURE_AUTH_KEY`, `LOGGED_IN_KEY`, `NONCE_KEY`, `AUTH_SALT`, `SECURE_AUTH_SALT`, `LOGGED_IN_SALT`, `NONCE_SALT`

6. Generate security keys (optional, requires wp-cli):
   ```bash
   wp package install aaemnnosttv/wp-cli-dotenv-command
   wp dotenv salts regenerate
   ```
   Or use the [Roots WordPress Salt Generator](https://roots.io/salts.html).

7. Install Composer dependencies:
   ```bash
   composer install
   ```

8. Install npm dependencies:
   ```bash
   npm ci
   ```

9. Run local setup scripts (ensure `MYSQLI_DEFAULT_SOCKET` is set if needed):
   ```bash
   cd app/tools/local
   ./setup-main-site.sh
   ./activate-plugins.sh
   bash multisite.sh  # Only for multisite setup
   ```

10. Update URLs for multisite to use HTTPS:
    ```bash
    wp search-replace --url=http://{PROJECT}.site 'http://{PROJECT}.site' 'https://{PROJECT}.site' --recurse-objects --network --skip-columns=guid
    ```

#### Method 2: Using Local Structure (Symlink Method)

1. Navigate to the site app folder and clone the project:
   ```bash
   cd path/to/app
   git clone git@github.com:DekodeInteraktiv/{YOUR_PROJECT} .
   ```

2. Remove the default `wp-content` folder:
   ```bash
   rm -rf public/wp-content
   ```

3. Create a symlink for `wp-content`:
   ```bash
   ln -s ../{project folder}/public/content public/wp-content
   ```

4. Run local setup scripts:
   ```bash
   cd tools/local
   ./setup-main-site.sh
   ./activate-plugins.sh
   bash multisite.sh  # Only for multisite setup
   ```

## Using wp-cli with Local

To use wp-cli with Local, you can use the built-in site shell. For default system console access, add a `wp-cli.local.yml` file to the root (`app`) directory:
```yml
path: public/wp
require:
  - wp-cli-local.php
```

Set the `MYSQLI_DEFAULT_SOCKET` in the `.env` file using the path from the Local Database tab.

#### Method 3: Setup with wp-env

1. Start the Environment

```bash
npm wp-env start
```

This command will spin up a local WordPress environment using Docker.

2. Scripts

- **start**: ```bash npm run wp-env start ``` Starts the wp-env environment.
- **stop**: ```bash npm run wp-env stop ``` Stops the wp-env environment.
- **clean**: ```bash npm run wp-env clean ``` Removes all WordPress data and resets the environment.
- **destroy**: ```bash npm run wp-env destroy ``` Destroys the wp-env environment.
- **logs**: ```bash npm run wp-env logs ``` Displays logs from the wp-env environment.
- **shell**: ```bash npm run wp-env run cli bash ``` Opens a shell in the wp-env environment.
- **cli**: ```bash npm run wp-env run cli <command> ``` Runs a command in the wp-env environment.

3. Overriding `.wp-env.json` with a Local Setup

To customize your local environment without changing the main `.wp-env.json`, create a `.wp-env.override.json` file in your project root. For example:

```json
{
	"core": "WordPress/WordPress#6.5",
	"plugins": [ "./my-custom-plugin" ],
	"mappings": {
		"wp-content/uploads": "./uploads"
	}
}
```

This file will override or extend the default configuration.

4. Dependencies

- **Docker**: Required to run the environment containers.

## Installation and Build

Run the following commands in the root directory to build the project:
```bash
composer install
npm ci && npm run build
```
(See `packages/themes/block-theme` for more details)

## Extending Builds

Project Base uses [wp-scripts](https://github.com/WordPress/gutenberg/tree/trunk/packages/scripts) out of the box for building front-end/view and editor assets. wp-script will scan all `block.json` files in the `src/` folder to find available entries. This means that if you have a `src` folder with a `view.js` and a `editor.js`, you also need to add a `block.json` file at the same location. Have a look in the `block-theme` theme for example or read up on [wp-script auto discovery for Webpack entry points](https://github.com/WordPress/gutenberg/blob/trunk/packages/scripts/utils/config.js#L198). For a more advanced setup, you can always customize builds by adding your own `webpack.config.js`.

### A quick overview of wp-scripts auto discovery:
1. Supply entry points manully to the CLI, e.g. `wp-scripts build src/view src/editor src/admin src/some-other-entry`. This will bypass 2 and 3.
2. Scan `src` folder for all `block.json` files. (Our default setup). This will support both theme/plugin assets (view/editor) and possible blocks inside the `src/` folder.
3. Fallback to `src/index.*` file. This will only look for a `src/index.js` file.

## Adding a new package (plugin, mu-plugin or theme)

1. **Create a folder**: Add a folder in `./packages` (e.g., `./packages/plugins`).

2. **Add a `composer.json` File** (for themes, plugins, mu-plugins, and PHP dependencies):
   ```json
   {
     "name": "project/package-name",
     "description": "Short description of the package.",
     "type": "wordpress-plugin/wordpress-muplugin/wordpress-theme/other",
     "version": "1.0.0"
   }
   ```

   *Note: Use [block-base](https://github.com/DekodeInteraktiv/block-base) for Gutenberg blocks.*

   *Note: A version should always be supplied. This ensures that package versions do not change between branches, leading to unneccesary merge conflicts.*

3. **Add a `package.json` File** (for frontend dependencies or custom React components):
   ```json
   {
     "name": "package-name",
     "private": true,
     "version": "1.0.0",
     "description": "Package description",
     "author": "Dekode",
     "main": "index.js",
     "scripts": {
       "build": "echo \"Error: no build specified\" && exit 1",
       "start": "echo \"Error: no start specified\" && exit 1",
       "test": "echo \"Error: no test specified\" && exit 1",
       "clean": "rm -rf node_modules build dist"
     }
   }
   ```

4. **Update Root Configuration**:
   - For Composer packages, add an entry under `"require"` in `composer.json`:
     ```json
     "project/package-name": "@dev"
     ```
   - For npm packages, add an entry under `"devDependencies"` in `package.json`:
     ```json
     "package-name": "file:packages/folder/package-name"
     ```

5. **Install the Package**:
   - For Composer:
     ```bash
     composer update
     ```
   - For npm:
     ```bash
     npm install
     ```
   Re-run `npm run build` or `npm run start` if needed.

## Additional Documentation

- **PostCSS**: [PostCSS Documentation](https://github.com/postcss/postcss/tree/main/docs)
- **WebPack**: [WebPack Concepts](https://webpack.js.org/concepts/)
- **WP Scripts**: [WP Scripts Guide](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/)
