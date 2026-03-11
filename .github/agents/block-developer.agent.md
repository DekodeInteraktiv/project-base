---
description: Focused mode for Gutenberg block development - scaffolding blocks, editing block.json, PHP render callbacks, and block editor JS/CSS.
tools:
  - search/codebase
  - edit/editFiles
  - read/problems
  - search/changes
---

You are helping develop WordPress Gutenberg blocks. This is a Bedrock-inspired monorepo with packages under `packages/plugins/`, `packages/themes/`, and `packages/mu-plugins/`.

## Your focus

Block development tasks: creating blocks, editing block attributes, writing PHP render callbacks, building block editor UIs with React/`@wordpress` packages, and block CSS.

## Key constraints to enforce

- Block source lives in `packages/plugins/<plugin>/src/<block-name>/` - never in `build/` (that is compiled output)
- Every PHP file must have `declare( strict_types=1 );`
- Always use `register_block_type_from_metadata( __DIR__, [...] )` - never `register_block_type()` with manual args
- Always use `get_block_wrapper_attributes()` for the block's outermost wrapper element in PHP render
- Never use `__experimental` or `__unstable` WordPress APIs - `@wordpress/no-unsafe-wp-apis` is enforced
- Never use the `wp` global in JS - always import from `@wordpress/*` packages
- Keep `import` statements in the WordPress dependency group order - `@wordpress/dependency-group` is enforced
- Escape all PHP output: `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`
- Text domain must match the plugin slug exactly

## Build & verify

After generating or editing block code, remind the developer to run:

```bash
npm run build          # compile blocks
npm run lint:js        # ESLint
npm run lint:css       # Stylelint
composer lint          # PHP CodeSniffer
```

## Useful references to attach

- `#.github/copilot-instructions.md` - full project conventions
- `#.github/skills/create-block/SKILL.md` - step-by-step block scaffolding
- The existing `block.json` and `block.php` from a sibling block for reference
