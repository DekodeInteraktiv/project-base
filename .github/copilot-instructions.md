# GitHub Copilot Instructions

Bedrock-inspired WordPress monorepo. Follow these conventions when generating code.

## Project Structure

```
documentation/   # Documentation of custom functionality
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

New plugins go in `packages/plugins/<plugin-name>/`, themes in `packages/themes/<theme-name>/`, and mu-plugins in `packages/mu-plugins/<plugin-name>/`. They must be required by Composer which symlinks these into `public/content/` automatically.

## Tech Stack

- **PHP**: 8.4+ - use modern PHP features (typed properties, enums, readonly, match expressions, named arguments, fibers where appropriate)
- **WordPress**: 6.9 with Full Site Editing (FSE/block themes)
- **T2 framework**: `t2/t2` - WordPress framework for blocks, utilities, and editor tooling
- **Node.js**: 20 via `.nvmrc`
- **Package manager**: npm 10+ (not yarn or pnpm)
- **Monorepo orchestration**: Turbo (`turbo.json` at root)
- **PHP coding standard**: `dekode/coding-standards` (extends WordPress Coding Standards)
- **JS/CSS linting**: ESLint extends `@wordpress/eslint-plugin/recommended`; StyleLint extends `@wordpress/stylelint-config/scss`; Prettier extends `@wordpress/prettier-config` with `printWidth: 120`

## Namespace Conventions

| Package type | Namespace pattern | Example |
|---|---|---|
| Block plugin | `{Project}\BlockLibrary\Blocks\{BlockName}` | `ProjectBase\BlockLibrary\Blocks\HeroBanner` |
| Block plugin feature | `{Project}\BlockLibrary\{Feature}` | `ProjectBase\BlockLibrary\DonationForms` |
| Integration plugin | `{Project}\Integrations\{Feature}` | `ProjectBase\Integrations\Member` |
| Theme | `{Project}\Theme\{Feature}` | `ProjectBase\Theme\PostTypes\Event` |
| MU-plugin | `{Project}\{PluginName}` | `ProjectBase\WinOrg` |

- Sub-namespaces reflect feature/domain grouping, not file structure
- Helper functions live in the same namespace as their feature

## Block Theme Structure

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

**`functions.php` pattern** - always auto-load via glob; never require files individually:
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

`@t2/*` packages are declared as webpack **externals** — never bundled. The root `webpack.config.js` handles this via `DependencyExtractionWebpackPlugin`:

```js
requestToHandle: ( request ) => request.startsWith( '@t2/' ) ? `t2-${ request.substring( 4 ) }` : undefined,
requestToExternal: ( request ) => request.startsWith( '@t2/' ) ? [ 't2', request.substring( 4 ) ] : undefined,
```

- Always import from `@t2/editor` in JS — never reference `window.t2` directly
- Do not add `@t2/*` to `package.json` dependencies — they are provided at runtime
- `t2.json` in the theme controls which T2 blocks and extensions are active

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

- **Write all strings in English** — never write Norwegian (or any other language) directly in source code; leave translation to the `.pot`/`.po` workflow.
- Primary translation target: **Norwegian Bokmål** (`nb_NO`).
- Run `npm run i18n:make-pot` to extract translation strings.

## Testing

No test infrastructure exists. Do not scaffold tests.

## Agent Workflow

After code changes, run linters and fix all errors before finishing:

```bash
npm run format && npm run lint
composer lint-fix && composer lint
```

## Dependency Versioning

- **Composer**: always use `~` (tilde) — never `^`
- **npm**: always use `^` (caret)
- **Never use `*` or `latest`**
- To upgrade to a new major version, update the constraint explicitly and review the changelog

## Important Constraints

- Do not modify anything inside `public/wp/` or `vendor/` (managed by Composer).
- Do not commit secrets (`.env`, credentials). Use `.env.example` as the template.
- All packages must be registered in their local `composer.json` and/or `package.json` before use.
