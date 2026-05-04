#!/usr/bin/env bash
set -euo pipefail

# Creates a worktree under ./worktrees/, copies .env, installs dependencies.
# The worktrees/ directory is gitignored.
# Usage: tools/local/new-worktree.sh <branch-name> [base-branch]

BRANCH="${1:-}"
BASE="${2:-main}"

if [ -z "$BRANCH" ]; then
  echo "usage: $0 <branch-name> [base-branch]" >&2
  exit 1
fi

REPO_ROOT="$(git rev-parse --show-toplevel)"
SLUG="$(printf '%s' "$BRANCH" | tr '/' '-' | tr '[:upper:]' '[:lower:]')"
WORKTREES_DIR="${REPO_ROOT}/worktrees"
TARGET="${WORKTREES_DIR}/${SLUG}"

if [ -e "$TARGET" ]; then
  echo "error: $TARGET already exists" >&2
  exit 1
fi

mkdir -p "$WORKTREES_DIR"

git -C "$REPO_ROOT" fetch origin --quiet || true

if git -C "$REPO_ROOT" show-ref --verify --quiet "refs/heads/$BRANCH"; then
  git -C "$REPO_ROOT" worktree add "$TARGET" "$BRANCH"
elif git -C "$REPO_ROOT" show-ref --verify --quiet "refs/remotes/origin/$BRANCH"; then
  git -C "$REPO_ROOT" worktree add --track -b "$BRANCH" "$TARGET" "origin/$BRANCH"
else
  git -C "$REPO_ROOT" worktree add -b "$BRANCH" "$TARGET" "$BASE"
fi

if [ -f "$REPO_ROOT/.env" ]; then
  cp "$REPO_ROOT/.env" "$TARGET/.env"
  echo "copied .env"
else
  echo "warning: $REPO_ROOT/.env not found — copy manually before running scripts"
fi

cd "$TARGET"
echo "running composer install..."
composer install
echo "running npm ci..."
npm ci

echo
echo "worktree ready: $TARGET"
echo "open in your editor, e.g.: code \"$TARGET\""
