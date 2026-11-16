# AI agents
The project keeps one set of agent configuration that Claude Code and GitHub Copilot both read. There are no per-tool copies to keep in sync.

## What lives where

| Path | Purpose |
|---|---|
| `AGENTS.md` | The project instructions: structure, stack, conventions and commands. Every agent reads it. |
| `CLAUDE.md` | One line, `@AGENTS.md`, which makes Claude Code load `AGENTS.md`. Do not add anything else to it. |
| `.claude/settings.json` | Shared Claude Code settings: pre-approved lint and build commands, blocked secret files, no AI attribution on commits and pull requests. |
| `.claude/skills/<name>/SKILL.md` | Project skills: step-by-step guides for tasks specific to this project. |
| `.claude/rules/<topic>.md` | Rules that only apply to some files. |
| `.claude/agents/<name>.md` | Project subagents. |

Only the first three exist in a new project. Create the others when the project needs them.

Organisation-wide standards, skills, agents and MCP servers are not in this repository. Each developer installs them once with:

```bash
npx --prefer-online dekodeinteraktiv/agent-setup
```

Anything in the project takes precedence over those defaults.

## Keeping AGENTS.md correct
Agents treat `AGENTS.md` as fact, so a wrong line there produces wrong code. Update it in the same pull request as the change it describes: a new package, a renamed command, a WordPress or PHP version bump.

Keep it to what an agent cannot work out from the code. Long task guides belong in a skill, and rules for one file type belong in a rule.

## Adding a skill
Create `.claude/skills/<skill-name>/SKILL.md`, where the directory name matches `name`:

```markdown
---
name: seed-database
description: Import the anonymised staging database into the local environment. Use when setting up the project or resetting local data.
---

Numbered steps, with the real paths and commands of this project.
```

The description decides when an agent picks the skill up, so say what it does and when to use it. With the organisation setup installed you can ask the agent to "add a skill" and it will follow this format.

## Adding a rule
Create `.claude/rules/<topic>.md`. A rule without frontmatter loads in every session; one with `paths` loads only when the agent works on a matching file:

```markdown
---
paths:
  - "packages/**/*.php"
---

- All PHP files begin with `declare( strict_types = 1 );`
```

## Adding a subagent
Create `.claude/agents/<name>.md` with `name` and `description` in the frontmatter and the instructions below it. A project subagent with the same name as an organisation one replaces it in this project.

## Personal settings
Keep personal preferences out of the shared files. These are gitignored:

- `.claude/settings.local.json` for your own permissions and settings
- `CLAUDE.local.md` for your own instructions
- `.mcp.json` for MCP servers you run for this project

## GitHub Copilot
Copilot reads `AGENTS.md` and `.claude/skills/` on every surface. Copilot CLI and VS Code also read `.claude/rules/`, `.claude/agents/` and hooks from `.claude/settings.json`.

Copilot's cloud agent and Copilot code review are the exception: they read rules, custom agents and hooks only from `.github/instructions/`, `.github/agents/` and `.github/hooks/`. Add files there only if the project relies on those two, and never define the same hook in both places, because Copilot CLI and VS Code would run it twice.

Do not add `.github/copilot-instructions.md`. Copilot merges it with `AGENTS.md`, so the agent would receive the instructions twice.

## Secrets
`.claude/settings.json` stops Claude Code from reading the dotenv files (except `.env.example`) and `auth.json`. This covers Claude's file tools and common shell commands such as `cat`; it is not a sandbox, so a script the agent runs can still open those files.

For Copilot, the equivalent is content exclusion in the GitHub repository or organisation settings. A `.copilotignore` file has no effect.

## Deployment
`.claude/` and `.mcp.json` are excluded in `tools/github/rsync-file-filter.rules`, and the Markdown files are covered by the `*.md` rule. The deploy copies everything the filter does not name, so add any new agent file or directory outside `.claude/` to that filter.
