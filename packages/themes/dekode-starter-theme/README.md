# Dekode Starter Theme

## Project set up

The project uses [@wordpress/env](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/) to spin up a local environment to work on theme development.

After cloning the repo:

```
npm install
```

To start local Docker environment:

```
npm run env:start
npm run start
```

These commands starts the docker environment and starts `wp-scripts` to watch for file changes in the theme files.

Wordpress should then be running on `localhost:8888`. Admin user credentials is admin/password.

To stop the docker environment:

```
npm run env stop
```

## T2

The project downloads a release version of T2 by default. If you want to use a local development version of T2 that is possible by creating a `.wp-env.override.json` file in the root of this repo with the following content:

```
{
    "mappings": {
        "wp-content/plugins/t2": "/Path/To/T2/Repo"
    },
    "plugins": []
}
```

Replace `/Path/To/T2/Repo` with the absolute path to your T2 repo.

## Variables Naming Convention

-   the global design tokens are defined at `/src/config/tokens.css` (call it tokens, because variables is too generic and refers to any CSS variables, when tokens targets the design tokens)
-   the global variables will use with a consistent prefix `--theme`. Ex: `--theme--text-color--primary`
-   the variables names are preferably using a format like `--{prefix}--{scope}--{variation}`: Ex: `--theme--block-spacing--small`

[Naming convention and project additional guides - WIP](https://dekode.atlassian.net/wiki/spaces/ST/pages/1868038164/Literal+and+semantic+tokens)

## Block Styles

The theme is set up to only load styles for blocks used in a page by using the `wp_enqueue_block_style()`-function.

## [Theme Icons](THEME-ICONS.md)

There are basically two alternative ways to use a custom icon set in your
theme or project:

1. Create a custom icon font from svg icons.
2. Use svg icons with css mask.

Read more about these alternatives and how to use them
in this step-by-step guide: [Theme Icons](THEME-ICONS.md)
