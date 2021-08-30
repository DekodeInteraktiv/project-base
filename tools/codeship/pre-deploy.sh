#!/bin/bash

cd ~/clone

composer build-for-deploy
if [[ $? -ne 0 ]] ; then
	echo "Composer build failure"
	exit 1
fi

npm run build
if [[ $? -ne 0 ]] ; then
	echo "NPM build failure"
	exit 1
fi

files=(
	.git
	.github
	.editorconfig
	.env.example
	.eslintignore
	.eslintrc.json
	.gitignore
	.php-version
	.stylelintignore
	.stylelintrc.json
	.npmpackagejsonlintrc.json
	.dependabot
	webpack.config.js
	composer.json
	composer.lock
	LICENSE.md
	package-lock.json
	package.json
	phpcs.xml.dist
	README.md
	log/
	tmp/
	bin/
	public/wp/wp-content
	public/wp/wp-config-sample.php
)

for i in "${files[@]}"
do
	rm -rf "~/clone/" $i;
	echo "~/clone/"$i "deleted";
done

# Remove unwanted files and subfolders
find . -name '.git*' | xargs rm -rf;
find . -name '.editorconfig' | xargs rm -rf;
find . -name 'composer.*' | xargs rm -rf;
find . -name 'phpcs.xml.dist' | xargs rm -rf;
find . -name 'README.md' | xargs rm -rf;
find . -name 'LICENSE.md' | xargs rm -rf;
find . -name 'node_modules' | xargs rm -rf;
