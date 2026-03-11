# GitHub Copilot Instructions

This is a Dekode WordPress agency project using a Bedrock-inspired monorepo structure. Follow these conventions when generating code.

## Project Structure

```
packages/
  plugins/       # WordPress plugins (npm workspace)
  themes/        # WordPress themes (npm workspace)
  mu-plugins/    # Must-use plugins (npm workspace)
  translations/  # Translation files
public/
  wp/            # WordPress core (managed by Composer, do not edit)
  content/       # wp-content equivalent (plugins, themes, mu-plugins, languages)
  wp-config.php
tools/           # Local development utilities
```

New plugins go in `packages/plugins/<plugin-name>/`, themes in `packages/themes/<theme-name>/`, and mu-plugins in `packages/mu-plugins/<plugin-name>/`. Composer symlinks these into `public/content/` automatically.

## Tech Stack

- **PHP**: 8.4+ - use modern PHP features (typed properties, enums, readonly, match expressions, named arguments, fibers where appropriate)
- **WordPress**: 6.9 with Full Site Editing (FSE/block themes)
- **T2 framework**: `t2/t2` - a WordPress framework providing blocks, utilities, and editor tooling. T2 blocks use the `t2/*` namespace (e.g., `t2/featured-query-post`). Import from `@t2/editor` in JS.
- **Node.js**: 20 via `.nvmrc`
- **Package manager**: npm 10+ (not yarn or pnpm)
- **Monorepo orchestration**: Turbo (`turbo.json` at root)

## PHP Conventions

- Follow the **Dekode coding standard** (`dekode/coding-standards`), which extends WordPress Coding Standards
- Run `composer lint` to check and `composer lint-fix` to auto-fix
- Use tabs for indentation (per `.editorconfig`)
- PHP files belong inside the relevant package under `packages/`; never modify files under `public/wp/` (WordPress core) or `vendor/`
- Every PHP file must begin with `declare( strict_types=1 );`
- Theme PHP files must include an ABSPATH guard after the opening tag: `defined( 'ABSPATH' ) || exit;`
- Use proper namespace declarations consistent with the package being edited (see Namespace Conventions below)
- Always prefix global function calls with a backslash (`\`) to avoid namespace issues, e.g., `\is_wp_error()`, `\get_transient()`, `\wp_remote_post()`

**Environment variables** - always read via the `\env()` helper (defined in `public/wp-config.php`; loads from `.env` via Symfony DotEnv), never `$_ENV`, `getenv()`, or `$_SERVER` directly:
```php
$api_key = \env( 'MY_API_KEY' );
```

**External HTTP calls** - always use `wp_remote_request()` (or `wp_remote_get()` / `wp_remote_post()`), never `curl` or `file_get_contents()`. Always check for errors and validate the response code:
```php
$response = \wp_remote_post( $url, [ 'body' => \wp_json_encode( $data ) ] );

if ( \is_wp_error( $response ) ) {
    return [ 'success' => false, 'error' => $response->get_error_message() ];
}

$code = \wp_remote_retrieve_response_code( $response );
if ( ! \in_array( $code, [ 200, 201 ], true ) ) {
    return [ 'success' => false, 'error' => __( 'Unexpected response.', 'text-domain' ) ];
}
```

**Transient caching** - use for expensive or external data. Always check with `false !==`:
```php
$cached = \get_transient( 'my_cache_key' );
if ( false !== $cached ) {
    return $cached;
}
// generate $value ...
\set_transient( 'my_cache_key', $value, HOUR_IN_SECONDS );
return $value;
```

**REST API responses** - always return a structured array with a `success` boolean:
```php
return new \WP_REST_Response( [ 'success' => true, 'data' => $result ], 200 );
return new \WP_REST_Response( [ 'success' => false, 'error' => __( 'Something went wrong.', 'text-domain' ) ], 500 );
```

## Namespace Conventions

Namespaces follow a consistent feature-based hierarchy across all packages:

| Package type | Namespace pattern | Example |
|---|---|---|
| Block plugin | `{Project}\BlockLibrary\Blocks\{BlockName}` | `ProjectBase\BlockLibrary\Blocks\HeroBanner` |
| Block plugin feature | `{Project}\BlockLibrary\{Feature}` | `ProjectBase\BlockLibrary\DonationForms` |
| Integration plugin | `{Project}\Integrations\{Feature}` | `ProjectBase\Integrations\Member` |
| Theme | `{Project}\Theme\{Feature}` | `ProjectBase\Theme\PostTypes\Event` |
| MU-plugin | `{Project}\{PluginName}` | `ProjectBase\WinOrg` |

- Always use `declare( strict_types=1 );` before the namespace declaration
- Sub-namespaces reflect feature/domain grouping, not file structure
- Helper functions live in the same namespace as their feature

## JavaScript / TypeScript Conventions

- **ESLint**: extends `@wordpress/eslint-plugin/recommended`. Key rules:
  - `@wordpress/dependency-group` is an error - keep `import` statements grouped and ordered
  - `@wordpress/no-unsafe-wp-apis` is an error - do not use `__experimental` or `__unstable` APIs
  - `wp` global is disabled - import from `@wordpress/*` packages instead
  - `@t2/editor` is a valid module alias (configured in ESLint import resolver)
- **Prettier**: extends `@wordpress/prettier-config` with `printWidth: 120`
- Run `npm run lint:js` and `npm run lint:css` to check; `npm run format` to auto-format

## CSS / Styling Conventions

- **StyleLint**: extends `@wordpress/stylelint-config/scss`
- CSS is processed by **PostCSS** with: global-data, imports, mixins, postcss-url, custom-media, media-minmax, nesting, discard-comments, autoprefixer, and cssnano (production only)
- Use PostCSS nesting syntax (CSS native nesting) - not SCSS `&:` parent selector style unless using mixins
- Block-specific CSS lives alongside each block in `src/blocks/<namespace>/<block-name>.css`

## Block Theme Structure

Block themes follow FSE conventions:

```
packages/themes/<theme-name>/
  theme.json          # Design tokens, settings, styles (version 3)
  t2.json             # T2-specific feature flags and block configuration
  style.css           # Theme header comment (minimal styles here)
  functions.php       # Theme setup - auto-loads includes/ and build/*/block.php via glob
  templates/          # Full-page block templates (.html)
  parts/              # Reusable template parts (.html)
  src/                # Source assets (compiled by webpack)
    editor.css / editor.js   # Block editor styles/scripts
    view.css / view.js       # Frontend styles/scripts
    blocks/                  # Per-block CSS overrides
  includes/           # PHP includes (auto-loaded via glob in functions.php)
  languages/          # Translation files
```

**`functions.php` pattern** - always auto-load includes and blocks via glob; never require files individually:
```php
defined( 'ABSPATH' ) || exit;

\array_map( fn( $f ) => require_once $f, \glob( __DIR__ . '/includes/*.php' ) );
\array_map( fn( $f ) => require_once $f, \glob( __DIR__ . '/build/*/block.php' ) );
```

**`includes/` organisation** - split by concern, one file per feature:
- `setup-theme.php` - menus, theme supports, image sizes
- `post-type-{slug}.php` - one file per custom post type
- `taxonomies.php` - taxonomy registrations
- `blocks.php` - block-related setup and allowed blocks
- `t2-*.php` - T2 framework integration hooks

## T2 Framework & Webpack

T2 packages (`@t2/*`) are loaded as window globals and must be declared as **externals** in webpack - they are never bundled. The root `webpack.config.js` configures this via `DependencyExtractionWebpackPlugin`:

```js
requestToHandle: ( request ) => request.startsWith( '@t2/' ) ? `t2-${ request.substring( 4 ) }` : undefined,
requestToExternal: ( request ) => request.startsWith( '@t2/' ) ? [ 't2', request.substring( 4 ) ] : undefined,
```

- Always import from `@t2/editor` in JS - never reference `window.t2` directly
- Do not add `@t2/*` packages to `package.json` dependencies - they are provided by the T2 plugin at runtime
- `t2.json` in the theme controls which T2 blocks and extensions are active for that project

## Build & Development Commands

| Command | Description |
|---|---|
| `npm run build` | Build all packages via Turbo |
| `npm run start` | Start all package watchers via Turbo |
| `npm run start-sync` | Start watchers + BrowserSync |
| `npm run lint` | Run all linters in parallel |
| `npm run format` | Auto-format with Prettier |
| `npm run create-block` | Scaffold a new Gutenberg block plugin |
| `composer lint` | PHP CodeSniffer check |
| `composer lint-fix` | PHP CodeSniffer auto-fix |
| `composer build` | Install PHP dependencies |
| `npm run wp-env start` | Start local WordPress environment (Docker) |

## Internationalization

- **Write all strings in English** — wrap them in the appropriate i18n function and leave translation to the `.pot`/`.po` workflow; never write Norwegian (or any other language) directly in source code
- The primary translation target is **Norwegian Bokmål** (`nb_NO`)
- Use `__()`, `_e()`, `_n()`, `_x()` etc. for all user-facing strings in PHP
- Use `@wordpress/i18n` (`__`, `_n`, `_x`, `sprintf`) in JavaScript
- Run `npm run i18n:make-pot` to extract translation strings

## Testing

These projects have **no test infrastructure** - no PHPUnit, no Jest, no test files. Do not scaffold test files, suggest test-driven development, or reference testing commands. If tests are needed, raise it with the team first.

## Agent Workflow

After making any code changes, always run both linters and fix every reported error before considering the task done:

```bash
npm run lint        # JS + CSS (ESLint, Stylelint)
composer lint       # PHP (PHPCS / Dekode coding standard)
```

Use `npm run format` and `composer lint-fix` to auto-fix where possible, then re-run to confirm clean output. Do not leave the task in a state where either linter reports errors.

## Dependency Versioning

Always use the `~` (tilde) constraint when adding or updating dependencies in any `composer.json` or `package.json` in the project, including the monorepo root:

```
~1.3.2   →  >=1.3.2 <1.4.0  (patch updates only)
```

This applies to both Composer and npm. It allows `composer update` / `npm update` to pull in bug fixes and security patches without risking breaking minor-version changes.

- **Never use `^` (caret)** - it allows minor version bumps which can introduce breaking changes
- **Never use `*` or `latest`** - unpinned versions make builds non-reproducible
- When a dependency must be upgraded to a new minor or major version, update the constraint explicitly and review the changelog

## Important Constraints

- Do not modify anything inside `public/wp/` (WordPress core managed by Composer)
- Do not modify anything inside `vendor/` (Composer managed)
- Do not commit `.env` - use `.env.example` as the template
- All packages must be registered in their local `composer.json` and/or `package.json` before use
- Prefer `@wordpress/*` packages over raw DOM manipulation or jQuery
