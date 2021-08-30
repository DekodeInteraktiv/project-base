#!/bin/bash

cd ~/clone

npm run lint
NPMSTATUS=$?

composer lint
COMPOSERSTATUS=$?

## If either linter has a failure, throw a non-zero exit code for the whole lint procedure.
## Doing it this way ensures both linters can run, so you get a full status without needing multiple code pushes.
if [[ $NPMSTATUS -ne 0 || $COMPOSERSTATUS -ne 0 ]] ; then
	exit 1
fi
