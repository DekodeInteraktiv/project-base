#!/bin/bash

cd ~/clone
rm -rf tools

#rsync -avz --delete --exclude='post-deploy.sh' --exclude='public/content/uploads' --exclude='public/robots.txt' --exclude='public/.htaccess' --exclude='.env' --exclude='.well-known' -e "ssh" ~/clone/ customer_name@x.prod.dekodes.no:/customer_name/destination
