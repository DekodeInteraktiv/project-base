#!/usr/bin/env bash

# Local Lightning Multisite fix
# Version: 1.0

# Color variables
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Check that we are running this script from the correct folder
if [ ! -d "../../../conf" ]; then
	printf "${YELLOW}You are probably not using Local Lightning. If you are, then please check that you are running this script from the correct location which should be someething like this: \"./tools/local\"${NC}\n"
	exit
fi

# Array of paths to web server config files
declare -a paths=(
	"../../../conf/nginx/includes/wordpress-multi.conf.hbs"
);

echo "Migrating Local Lightning web server config to be compliant with Dekode's Multisite Setup..."
echo "==="

# Changes flag default state
changes_done=0

for i in "${paths[@]}"
do :

	# Check if file exists
	if [ ! -f $i ]; then
		continue
	fi

	# Read contents of file into variable
	content=$(cat $i)

	printf "Updating config file with new path: $i"
	sleep .2

	# Check if file is already migrated
	if [[ $content = *"/wp\$2"* ]]; then
		printf " - ${GREEN}File already migrated${NC}\n"
		continue
	fi

	# Flag changes as done
	changes_done=1

	pattern=" \$2 last;"
	new_string=" /wp\$2 last;"
	new_content=${content//$pattern/$new_string}

	# Write new content
	echo "$new_content" > $i
	printf " - ${GREEN}Done${NC}\n"

done

echo "==="
printf "${GREEN}All done!${NC}\n"

# Display restart message only if we have done changes
if [ $changes_done == "1" ]; then
	printf "${GREEN}Please restart the container (site) to apply the changes!${NC}\n"
fi
