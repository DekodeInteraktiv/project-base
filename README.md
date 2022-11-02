# Project name

If you are looking for instructions on how to use and install project-base, please refer to the file [INSTALLATION.md](./INSTALLATION.md)

Short summary of the main functionality and purpose of the project.

## Made by

 List the people who have been extensively involved in creating this website

## How to build

List any steps necessary to get this project up and running on a local machine. This could consist of, but is not limited to, these points:

- NPM/yarn installation (please include a version of node on which the install process is known to work)
- SASS compilation
- JS compilation
- Composer quirks
- Symlinking

## Deployment
Specify how this project is deployed. For most projects, this should be simply "Through codeship".

## Custom wp-cli commands
List and document any custom-made wp-cli commands on this site.

### wp-cli and Local by Flywheel

There can be issues trying to use wp-cli commands when using a [Local by Flywheel](https://localwp.com/) development environment. You can usually fix this with the following steps:

1. Add a file named `wp-cli.local.yml` to the root (`app`) directory, with the following content:

```
path: public/wp
require:
  - wp-cli.local.php
```

2. Add a file named `wp-cli.local.php` to the root (`app`) directory, with the following content:

```
<?php
define('DB_HOST', 'localhost:<SOCKET PATH>');
define('DB_USER', 'root');
define('DB_PASSWORD', 'root');

// Only display fatal run-time errors.
// See http://php.net/manual/en/errorfunc.constants.php.
error_reporting(E_ERROR);

// Disable WordPress debug mode.
// See https://codex.wordpress.org/WP_DEBUG.
define('WP_DEBUG', false);
```

In the above file, replace `<SOCKET PATH>` with the socket path from the corresponding project in Local, which can be found in the **Database** tab and usually looks something like the following (on Mac OS):

`/Users/<username>/Library/Application Support/Local/run/<unique string>/mysql/mysqld.sock`

## Third party integrations
List any third-party integrations which are included in the project. Each entry should include:

**Where in the project the integration is used**
What purpose the integration serves. Is it used when the user performs a special page query, or is it an import routine which runs at specific intervals?

**How the integration is configured**
Any constants be it PHP or Javascript that need to be set, and whether these constants are different on stage and production.

**Link to documentation**
Add a link to any relevant documentation, and specify which version of an API we use.

**Authorization**
Add some words about how the third-party server authenticates incoming requests and add any API-keys necessary.

**Any terminal commands that could be useful**
Any custom made wp-cli commands which communicate with the third party, should be documented here. Also a link to any commands included in plugins which comunicate with the third-party, should be included.

## Cronjobs
List all non-standard cronjobs on the site and say a few words about the purpose of each of them.

## Anonymization
Project data is anonymized using a config file, `anonymize.config.json`, where the developer is expected to declare any Personally Identifiable Information (PII) for the project in a data structure relating to database tables and entries.

This anonymization is performed using the [anonymize-mysqldump project](https://github.com/DekodeInteraktiv/anonymize-mysqldump), where you can also see the [structure of the config file](https://github.com/DekodeInteraktiv/anonymize-mysqldump#config-file).

The default configuration file provides support for a vanilla WordPress installation, with just data relating to comments and usermeta accounted for.
