#!/bin/sh

tags_file="feeds/tags"
branches_file="feeds/branches"
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cd $DIR
tags="$(vendor/bin/rocketeer get:remote:tag --parallel | grep 'webdeploy@' | cut -d '(' -f2,3 | tr -dc '[:alnum:][:punct:]\n\r' | sed 's/)\[39m/ /')"
branches="$(vendor/bin/rocketeer get:remote:branch --parallel | grep 'webdeploy@' | cut -d '(' -f2,3 | tr -dc '[:alnum:][:punct:]\n\r' | sed 's/)\[39m/ /')"

printf '%s' "$tags"  > "$tags_file"
printf '%s' "$branches"  > "$branches_file"

#/bin/sh copy_feeds.sh

echo "Completed!"
