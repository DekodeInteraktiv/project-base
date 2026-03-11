---
description: Example agent — replace this with a real description of what the agent is for.
tools:
  - search/codebase
  - edit/editFiles
  - read/problems
  - search/changes
---

You are helping with [describe the task domain here]. This is a Bedrock-inspired monorepo with packages under `packages/plugins/`, `packages/themes/`, and `packages/mu-plugins/`.

## Your focus

[Describe what kinds of tasks this agent handles and what it should stay focused on.]

## Key constraints to enforce

- [Add project or task-specific rules the agent must follow]
- Every PHP file must have `declare( strict_types=1 );`
- Escape all PHP output: `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`

## Build & verify

After making changes, remind the developer to run:

```bash
npm run build
npm run lint:js
npm run lint:css
composer lint
```

## Useful references to attach

- `#.github/copilot-instructions.md` — full project conventions
- `#.github/skills/<relevant-skill>/SKILL.md` — relevant skill guide
