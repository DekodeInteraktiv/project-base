#!/bin/bash

phpenv local 8.0
export COMPOSER_CACHE_DIR=${HOME}/cache
cd ~/clone
composer install
npm i
