# Website maintenance instructions

## Site architecture

This repository is the source for https://marta-kryven.github.io.

- It is currently a static GitHub Pages site.
- The publishing branch is `master`.
- The site is published from the repository root.
- There is no build step and no package manager.
- HTML pages share `style.css` plus some page-specific styles.
- Pushes to `master` publish the site.

## Maintenance principles

- Preserve the current architecture unless asked to change it.
- Ask for approval before introducing frameworks or a build pipeline.
- Make the smallest clean change that accomplishes the requested task.
- Prefer shared style
- Keep navigation labels and links consistent across all pages.
- Preserve existing public URLs whenever possible.
- Ask before delete files that appear unused. They may be externally linked.
- Do not modify archived experiment directories (E1, E2, E3,
  MST_webcode, experimentTemplate, TE, IntelligenceData) unless explicitly
  requested.
- Do not modify or remove old PDFs unless explicitly requested.

## Git workflow

For normal website changes:

1. Check `git status`.
2. Pull current `master` using a fast-forward-only pull.
3. Make the requested changes.
4. Inspect the diff for unintended changes.
5. Check local href/src references affected by the change.
6. Commit with a concise descriptive commit message.
7. Push to `origin master`.

Never force-push, rewrite Git history, or run destructive Git commands.

For structural changes, file deletion, URL changes, or anything that could
break existing links, explain the proposed change before doing it.
