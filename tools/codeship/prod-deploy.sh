#!/bin/bash

cd ~/clone
rm -rf tools

#rsync -avz --delete --exclude='post-deploy.sh' --exclude='public/content/uploads' --exclude='public/robots.txt' --exclude='public/.htaccess' --exclude='.env' -e "ssh" ~/clone/ prod_xxxx@bolt61.servebolt.com:/kunder/dekode_3423/prod_xxxx/dekode-deploy
