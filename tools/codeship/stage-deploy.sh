#!/bin/bash

cd ~/clone

cp ~/clone/tools/codeship/post-deploy.sh post-deploy.sh

rm -rf tools

rsync -avz --delete --exclude='post-deploy.sh' --exclude='public/content/uploads' --exclude='public/robots.txt' --exclude='public/.htaccess' --exclude='.env' --exclude='.well-known' -e "ssh" ~/clone/ customer_name@x.dev05.dekodes.no:/customer_name/destination
ssh customer_name@x.dev05.dekodes.no 'bash -s' < ~/clone/post-deploy.sh
