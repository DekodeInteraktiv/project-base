#!/bin/bash

cd ~/clone

cp ~/clone/tools/codeship/post-deploy.sh post-deploy.sh

rm -rf tools

rsync -avz --delete --exclude='post-deploy.sh' --exclude='public/content/uploads' --exclude='public/robots.txt' --exclude='public/.htaccess' --exclude='.env' -e "ssh" ~/clone/ stagen_xxxx@bolt61.servebolt.com:/kunder/dekode_3423/stagen_xxxx/dekode-deploy
ssh stagen_xxxx@bolt61.servebolt.com 'bash -s' < ~/clone/post-deploy.sh
