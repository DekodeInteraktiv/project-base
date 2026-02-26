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

- **PHP**: 8.4+ — use modern PHP features (typed properties, enums, readonly, match expressions, named arguments, fibers where appropriate)
- **WordPress**: 6.9 with Full Site Editing (FSE/block themes)
- **T2 framework**: `t2/t2` — a Dekode-developed WordPress framework providing blocks, utilities, and editor tooling. T2 blocks use the `t2/*` namespace (e.g., `t2/featured-query-post`). Import from `@t2/editor` in JS.
- **Node.js**: 20 via `.nvmrc`
- **Package manager**: npm 10+ (not yarn or pnpm)
- **Monorepo orchestration**: Turbo (`turbo.json` at root)

## PHP Conventions

- Follow the **Dekode coding standard** (`dekode/coding-standards`), which extends WordPress Coding Standards
- Run `composer lint` to check and `composer lint-fix` to auto-fix
- Use tabs for indentation (per `.editorconfig`)
- PHP files belong inside the relevant package under `packages/`; never modify files under `public/wp/` (WordPress core) or `vendor/`
- Use proper namespace declarations consistent with the package being edited

## JavaScript / TypeScript Conventions

- **ESLint**: extends `@wordpress/eslint-plugin/recommended`. Key rules:
  - `@wordpress/dependency-group` is an error — keep `import` statements grouped and ordered
  - `@wordpress/no-unsafe-wp-apis` is an error — do not use `__experimental` or `__unstable` APIs
  - `wp` global is disabled — import from `@wordpress/*` packages instead
  - `@t2/editor` is a valid module alias (configured in ESLint import resolver)
- **Prettier**: extends `@wordpress/prettier-config` with `printWidth: 120`
- Run `npm run lint:js` and `npm run lint:css` to check; `npm run format` to auto-format

## CSS / Styling Conventions

- **StyleLint**: extends `@wordpress/stylelint-config/scss`
- CSS is processed by **PostCSS** with: nesting, imports, mixins, custom-media, media-minmax, autoprefixer, cssnano, and postcss-url
- Use PostCSS nesting syntax (CSS native nesting) — not SCSS `&:` parent selector style unless using mixins
- Block-specific CSS lives alongside each block in `src/blocks/<namespace>/<block-name>.css`

## Block Theme Structure

Block themes follow FSE conventions:

```
packages/themes/<theme-name>/
  theme.json          # Design tokens, settings, styles
  t2.json             # T2-specific theme configuration
  style.css           # Theme header comment (minimal styles here)
  functions.php       # Theme setup, hooks
  templates/          # Full-page block templates (.html)
  parts/              # Reusable template parts (.html)
  src/                # Source assets (compiled by webpack)
    editor.css / editor.js   # Block editor styles/scripts
    view.css / view.js       # Frontend styles/scripts
    blocks/                  # Per-block CSS overrides
  includes/           # PHP includes (setup, block settings, etc.)
  languages/          # Translation files
```

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

- Primary language: **Norwegian Bokmål** (`nb_NO`)
- Use `__()`, `_e()`, `_n()`, `_x()` etc. for all user-facing strings in PHP
- Use `@wordpress/i18n` (`__`, `_n`, `_x`, `sprintf`) in JavaScript
- Run `npm run i18n:make-pot` to extract translation strings

## Important Constraints

- Do not modify anything inside `public/wp/` (WordPress core managed by Composer)
- Do not modify anything inside `vendor/` (Composer managed)
- Do not commit `.env` — use `.env.example` as the template
- All packages must be registered in their local `composer.json` and/or `package.json` before use
- Prefer `@wordpress/*` packages over raw DOM manipulation or jQuery
