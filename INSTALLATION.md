# Dekode Project Base - Installation

## Requirements

* PHP >= 7.2
* Composer - [Install](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)
* Node >= 16.0

## TLDR - what's new?
Themes, plugins, mu-plugins etc. is now structured under packages and built into the wordpress structure by composer. All build scripts are run from root and automatically looks through `./packages`. Packages need an `entry-files.json` file to define what files should be build when running npm build/start and a `composer.json` that defines what it is and gives the package a name. Codeship testing/build commands are now located in the repository under `./tools`. Any time a new package is added it needs to be referenced in the project-base root composer.json using @dev.

## Structure
* *Packages* - Contains all of the code developed for the project such as plugins, themes, mu-plugins and any custom libs etc. This gets built into public by composer using symlinks.
* *Public* - The files used by WordPress. Contents are generated automatically from packages using composer. This folder shouldn't need to be touched at all.
* *Tools* - Build and setup scripts used by Codeship and Local
* *Config* - Environment variable setup

## Local setup
1. If setting up a new project, create a git repo using [Project base](https://github.com/DekodeInteraktiv/project-base) as the template. Otherwise skip this step.
2. Create a new site in Local by Flywheel, make sure that you enable multisite (subdir) if relevant for the project.
3. Follow one of the methods below (not both!)

### Method 1 - Setup replicating server structure
4. `cd app` go inside the app folder
5. `rm -rf public` remove the default public folder
6. `git clone git@github.com:DekodeInteraktiv/{YOUR_PROJECT} .` clone the project into the current folder (the . is important)
7. `cp .env.example .env` copy the environment example file (do not rename it, as it will show as git differences)
8. Update the environment variables in the .env file

    * `DB_NAME` - Database name
    * `DB_USER` - Database user
    * `DB_PASSWORD` - Database password
    * `DB_HOST` - Database host
    * `WP_ENVIRONMENT_TYPE` - Set to environment (`local`, `development`, `staging`, `production`)
    * `WP_HOME` - Full URL to WordPress home (http://example.com)
    * `WP_SITEURL` - Full URL to WordPress including subdirectory (http://example.com/wp)
    * `AUTH_KEY`, `SECURE_AUTH_KEY`, `LOGGED_IN_KEY`, `NONCE_KEY`, `AUTH_SALT`, `SECURE_AUTH_SALT`, `LOGGED_IN_SALT`, `NONCE_SALT`

9. Automatically generate the security keys

    If you want to automatically generate the security keys (assuming you have wp-cli installed locally) you can use the very handy [wp-cli-dotenv-command](https://github.com/aaemnnosttv/wp-cli-dotenv-command):

    - wp package install aaemnnosttv/wp-cli-dotenv-command
    - wp dotenv salts regenerate

    Or, you can cut and paste from the [Roots WordPress Salt Generator](https://roots.io/salts.html).

10. `composer install`
11. `npm install`
12. Run the app/tools/local scripts (please note that these might work only if you setup the `MYSQLI_DEFAULT_SOCKET` environment variable, and only after you actually have the plugins and theme installed at step 10).

	* `cd app/tools/local`
    * `./setup-main-site.sh`
    * `./activate-plugins.sh`
    * `bash multisite.sh` (run this only if you are installing a multisite)

13. If you install a multisite, the URLs are not using https by default, and that can be fixed by running the command `wp search-replace --url=http://{PROJECT}.site 'http://{PROJECT}.site' 'https://{PROJECT}.site' --recurse-objects --network --skip-columns=guid`

### Method 2 -Setup using Local structure (symlink method)

4. `cd` to the site app folder and clone the existing project (or the one you setup on step 1) from github.
5. `cd` into public and remove wp-content `rm -rf wp-content`
6. create a symlink in public from `wp-content` to `../{project folder}/public/content`
7. Run the scripts in `./tools/local/`

## Installation and build
Run `composer install` and `npm install && npm run build` in root to build the project.

### Extending the builds
Project-base uses wp-scripts to build front end assets using the `npm run build` or `npm run start` commands. wp-scripts in turn uses webpack and postcss. You can extend those by editing the postcss.config.js and webpack.config.js files.

## Adding a new package (ex. plugin/mu-plugin/theme)
1. Add a folder to the relevant category in `./packages`. (create one if none exists). So for a plugin, create a folder in the `./packages/plugins` folder.

2. If your package should be installed using composer (for themes, plugins, mu-plugins and php deps) Add a composer.json, it needs a minimum of the following data:
```json
{
	"name": "project/package-name",
	"description": "Short description of the package.",
	"type": "wordpress-plugin/wordpress-muplugin/wordpress-theme/other",
}
```
*Note: if your plugin is a gutenberg block, you can use [block-base](https://github.com/DekodeInteraktiv/block-base)*

3. If your package should be installed using npm (for frontend deps, like custom react components) Add a package.json, it needs a minimum of the following data:

```json
{
  "name": "package-name",
  "private": true,
  "version": "1.0.0",
  "description": "Package description",
  "author": "Dekode",
  "main": "index.js",
  "scripts": {
    "test": "echo \"Error: no test specified\" && exit 1"
  }
}
```

4. If your package has front end assets such as scripts or css add a `entry-files.json` with the following structure naming the src files that should be build. (the src files should be located in a folder called `src`)
```json
[ "index.js", "style.css", "editor.css" ]
```

5. Go back to the project root and update the composer.json or package.json depending on package type. For composer add a entry under "require" like such `"project/package-name": "@dev"`. For package.json add an entry under "devDependencies" like such `"package-name": "file:packages/folder/package-name"`.

6. Install the package using `composer update` or `npm install` depending on type. you might need to re-run `npm run build` or `npm run start` if you have installed a new package containing files that need building.

## Documentation
* PostCSS [https://github.com/postcss/postcss/tree/main/docs](https://github.com/postcss/postcss/tree/main/docs)
* WebPack [https://webpack.js.org/concepts/](https://webpack.js.org/concepts/)
* WP Scripts [https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/)
