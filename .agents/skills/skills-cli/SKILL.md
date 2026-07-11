---
name: skills-cli
description: Use this skill when the user wants to install, list, or manage agent skills from a GitHub repository using the `npx skills` CLI tool (e.g. "install skill X from owner/repo", "list skills in a repo", "add this skill to claude-code/cursor/zed").
---

# `npx skills` CLI — Installing Agent Skills from a Repository

The `skills` CLI (run via `npx skills`) fetches reusable agent skills from a
GitHub repository (`<owner/repo>`) and installs them into one or more coding
agents (e.g. `claude-code`, `cursor`, `zed`, `windsurf`, etc.) on the local
machine or inside the current project.

## When to use this skill

- The user asks to install a skill from a GitHub repo (e.g. "install the
  `pr-review` skill from `someorg/skills-repo`").
- The user wants to see what skills are available in a given repo before
  installing.
- The user wants a skill installed for a specific agent, or for all agents
  they use.

## Required information before running any command

Before running any `npx skills` command, make sure you have:

1. **`<owner/repo>`** — the GitHub repository that hosts the skill(s), e.g.
   `anthropics/skills` or `some-org/some-repo`. If the user hasn't provided
   this, ask for it — do not guess a repository.
2. **`<skill-name>`** — the specific skill to install (only needed for
   `--skill`). If unknown, run the `--list` command first to discover
   available skill names.
3. **Target agent(s)** — which coding agent(s) to install into (`-a
   claude-code`, `-a cursor`, `-a zed`, etc.), or whether to install for
   `--all` agents. If not specified, ask the user, since this determines
   where the skill files end up (e.g. `.claude/skills/`, `.cursor/skills/`,
   `.agents/skills/` depending on the agent's convention).

## Commands

```sh
# List all skills available in a repository (use this to discover skill names)
npx skills add <owner/repo> --list

# Install a specific skill from a repository (installs to the default/detected agent)
npx skills add <owner/repo> --skill <skill-name>

# Install a specific skill into one or more named agents
npx skills add <owner/repo> --skill <skill-name> -a claude-code -a cursor

# Install a specific skill into every agent the tool supports/detects
npx skills add <owner/repo> --skill <skill-name> --all
```

## Recommended workflow

1. If the user only gave a repo (no skill name), run `npx skills add
   <owner/repo> --list` first and show the results so the user can pick.
2. Confirm the target agent(s) with the user if not specified (don't assume
   `--all` unless asked, since it may install into agents/tools the user
   isn't using in this project).
3. Run the install command via the terminal tool, from the project root.
4. After installing, check what files were added/changed (e.g. via `git
   status` or `find_path`) and summarize which skill was installed and where
   its files now live, so the user can verify it in their editor/agent.
5. If installation fails (e.g. repo not found, skill name typo, network
   issue), report the exact error from the command output rather than
   retrying blindly.

## Notes

- This CLI is unrelated to Zed's built-in project-local skills convention
  (`.agents/skills/<name>/SKILL.md`) or global skills
  (`~/.agents/skills/<name>/SKILL.md`) — some target agents may use that same
  convention, but others (Claude Code, Cursor, etc.) have their own
  directories/formats. Check where files land after installing before
  assuming a location.
- Never fabricate an `<owner/repo>` or `<skill-name>` — these are
  placeholders in the generic usage docs, not real values. Always get the
  real repo/skill from the user before running a command.
