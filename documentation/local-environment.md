# Local development environment

Project Base comes bundled with the [`bedrock-wp-env` node package](https://github.com/Clorith/bedrock-wp-env) for local development environments.

The pre-requisite for this is that you need Docker installed and running.

## Good to know

The `becrock-wp-env` setup functions much like a drop-in replacement for [`wp-env`](https://github.com/WordPress/gutenberg/tree/trunk/packages/env) created by the WordPress project, but with support for Bedrock-style project structures.

All commands that `wp-env` supports, should also be available here, and are found under `npm run env`.

The environment is based on the Apache2 server setup, which may differ from where a site will eventually be deployed, keep this in mind if special permalinks need ot be added, as nginx for example does not support `.htaccess` formats like Apache does.

## Configuration

Start off by ensuring you have a valid `.env` file in your project root. The file should ideally have the following default settings, as copied from the `.env.example` file (the DB credentials are important, or you will likely end a loop of the environment trying to configure the site over and over):

- `DB_HOST=mysql`
- `DB_NAME=wordpress`
- `DB_USER=root`
- `DB_PASSWORD=password`
- `WP_HOME=http://localhost:8888`

These are the only lines that matter, note that if you have modified the `.wp-env.json` file to use a custom port or similar, the `WP_HOME` should reflect this.

## Starting your environment

`npm run env start` will start the environment, by creating the appropriate docker images for you.

Wait a little while as the site is generated, and you will shortly be told when things are ready for use, note that you can have multiple sites using the network, but if you use the same port number, only one can run at a time.

## Stopping your environment

`npm run env stop` will stop the environment, allowing you to quickly pick it back up again later if needed.

## Destroying the environment

`npm run env destroy` will remove the environment, and all the containers and images it created.

## WP-CLI

Maybe one of the most convenient parts is the ability to run commands directly against the WordPress CLI interface.

The wp-cli environment is ran as a separate docker container, so it will not interfere with the site running on the side, but has access to the same filesystem and environment variables.

`npm run env run cli '<command>'` will run the command against the WordPress CLI (yes, it says "run" twich).

Note that `<command>` can be any command that is valid for the WordPress CLI, and should always start with the command `wp`.

### Notable examples

The following are some examples of commands and features you are likely to encounter on a regular basis.

#### Import a database

`npm run env run cli 'wp db import <path relative to project root>'`

This is useful for importing database backups from a site, and should be fairly quick to execute, depending on the size of the database of course.

#### Search & Replace

`npm run env run cli 'wp search-replace <search> <replace> --skip-plugins --skip-themes'`

Useful, for example after receiving a database export, will replace all occurrences of `<search>` with `<replace>` in the database, without loading any plugins or themes (for improved speed).

#### Clear transients

`npm run env run cli 'wp transient delete --all'`

Deletes all transients on a site, useful for cleaning up after a database import, and to ensure no temporary data relating to an environment is still around.

#### List scheduled events

`npm run env run cil 'wp cron event list'`

Lists all scheduled events, when they should run, and how often.

#### Run a scheduled event

`npm run env run cli 'wp cron event run <event name>'`

Runs a specific scheduled event immediately.

#### Start an interactive shell

`npm run env run cli 'wp shell'`

Starts an interactive shell against the WordPress instance, which will have access to the same filesystem and environment variables as the site.
