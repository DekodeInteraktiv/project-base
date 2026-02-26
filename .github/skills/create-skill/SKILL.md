---
name: create-skill
description: Create a new Copilot skill file for a reusable task in this project.
---

# Skill: Create Skill

Create a new Copilot skill file for a reusable task in this project. If the user has not already provided a skill name and description, ask follow-up questions to get the necessary details before creating the file.

## What is a skill?

A skill is a reusable prompt file at `.github/skills/<skill-name>/SKILL.md`. When attached to a Copilot Chat conversation (via `#` file reference or drag-and-drop), it provides Copilot with focused instructions for a specific, repeatable task — such as scaffolding a block, creating a plugin, or writing a test.

Skills are task-specific and opinionated. They encode the project's conventions so Copilot produces correct output without needing to re-explain the stack each time.

## Skill directory naming

- Use lowercase kebab-case: `create-block`, `new-plugin`, `add-rest-endpoint`
- Name after the action, not the output: prefer `create-block` over `gutenberg-block`
- Be specific: `create-block` is better than `create-component`

## SKILL.md format

Every skill file must follow this structure:

```markdown
# Skill: <Title>

<One or two sentences: what this skill does and when to use it.>

## Inputs

List what the user must provide before Copilot starts:
- **Name**: e.g. the block name, plugin slug, etc.
- **Location**: which package or directory to work in
- Any other required context

## Steps

Numbered, specific instructions Copilot follows to complete the task. Each step
should be unambiguous and reference actual project paths, commands, or conventions.

1. Step one
2. Step two
3. ...

## Conventions

Call out any project-specific rules that apply to this task:
- Coding standards, naming conventions, file layout
- Commands to run after generation (lint, build, etc.)
- Things to avoid

## Output

Describe what the finished result looks like: files created, commands to run,
what "done" means.
```

## Rules for writing a good skill

- **Be concrete, not generic.** Reference actual paths (`packages/plugins/`), actual commands (`npm run lint:js`), actual standards (`@wordpress/eslint-plugin`). A skill that could apply to any project is not useful.
- **Encode decisions.** If there is a right way to do something in this project, state it. The skill should eliminate ambiguity, not leave it to Copilot to guess.
- **Keep scope tight.** One skill = one task. If a skill is trying to do two things, split it.
- **Mirror the copilot-instructions.md.** Do not contradict `.github/copilot-instructions.md`. Skills extend it for specific tasks; they do not replace it.
- **Include a lint/build step.** Almost every skill that generates code should end with the relevant lint and build commands so the output is immediately verifiable.

## Steps

When asked to create a new skill:

1. Determine the task name and choose a kebab-case directory name under `.github/skills/`.
2. Identify the inputs the user must supply (names, locations, options).
3. Write out the numbered steps Copilot should follow, referencing real project paths and commands from `copilot-instructions.md`.
4. Add a Conventions section with any rules specific to this type of task.
5. Describe the expected output so it is clear when the skill is complete.
6. Create the file at `.github/skills/<skill-name>/SKILL.md`.
