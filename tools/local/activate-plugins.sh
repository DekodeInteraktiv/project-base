#!/bin/bash

plugins=(
	# list plugins.
)

for i in "${plugins[@]}"
do
	wp plugin activate $i --quiet;
done
