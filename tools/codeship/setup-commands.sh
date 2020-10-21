#!/bin/bash

phpenv local 7.4
export COMPOSER_CACHE_DIR=${HOME}/cache
cd ~/clone
composer install
npm i
