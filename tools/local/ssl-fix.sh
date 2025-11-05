#!/usr/bin/env bash

# Local Lightning SSL cafile fix
# Version: 1.0

# Color variables
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

SCRIPT_DIR=$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)
SITE_ROOT=$(dirname "$(dirname "$SCRIPT_DIR")")
CONF_ROOT=$(dirname "$(dirname "$(dirname "$SCRIPT_DIR")")")/conf
WP_CA_BUNDLE="$SITE_ROOT/public/wp/wp-includes/certificates/ca-bundle.crt"

# Check that we are running this script from the correct folder
if [ ! -d "$CONF_ROOT" ]; then
	printf "${YELLOW}You are probably not using Local Lightning. If you are, then please check that you are running this script from the correct location which should be something like this: \"./tools/local\"${NC}\n"
	exit
fi

declare -a php_ini_paths=()

# Collect php.ini.hbs candidates
if [ -f "$CONF_ROOT/php/php.ini.hbs" ]; then
	php_ini_paths+=("$CONF_ROOT/php/php.ini.hbs")
fi

for version_dir in "$CONF_ROOT"/php-*; do
	if [ -d "$version_dir" ] && [ -f "$version_dir/php.ini.hbs" ]; then
		php_ini_paths+=("$version_dir/php.ini.hbs")
	fi
done

if [ ${#php_ini_paths[@]} -eq 0 ]; then
	printf "${YELLOW}No php.ini.hbs files found. Nothing to update.${NC}\n"
	exit
fi

echo "Updating Local Lightning PHP config to use the bundled WordPress CA certificate..."
echo "==="

if [ ! -f "$WP_CA_BUNDLE" ]; then
	printf "${YELLOW}Warning: Expected CA bundle not found at: $WP_CA_BUNDLE${NC}\n"
fi

changes_done=0

for ini_path in "${php_ini_paths[@]}"; do
	printf "Updating PHP config: %s" "$ini_path"
	sleep .2

	if ! grep -Eq 'openssl\.cafile' "$ini_path"; then
		printf " - ${YELLOW}No openssl.cafile entry found${NC}\n"
		continue
	fi

	if grep -Fq "openssl.cafile=\"$WP_CA_BUNDLE\"" "$ini_path" || grep -Fq "openssl.cafile = \"$WP_CA_BUNDLE\"" "$ini_path"; then
		printf " - ${GREEN}Already updated${NC}\n"
		continue
	fi

	if grep -Eq 'openssl\.cafile.*\{\{wpCaBundlePath\}\}' "$ini_path"; then
		tmp_file=$(mktemp)
		if awk -v target="$WP_CA_BUNDLE" '
			BEGIN { changed = 0 }
			{
				if ($0 ~ /openssl\.cafile/ && $0 ~ /\{\{wpCaBundlePath\}\}/) {
					gsub(/\{\{wpCaBundlePath\}\}/, target);
					changed = 1;
				}
				print;
			}
			END {
				if (changed) exit 0;
				exit 1;
			}
		' "$ini_path" > "$tmp_file"; then
			if mv "$tmp_file" "$ini_path"; then
				printf " - ${GREEN}Done${NC}\n"
				changes_done=1
			else
				rm -f "$tmp_file"
				printf " - ${RED}Failed to write changes${NC}\n"
			fi
		else
			rm -f "$tmp_file"
			printf " - ${RED}Failed to update${NC}\n"
		fi
	else
		printf " - ${YELLOW}Placeholder not found${NC}\n"
	fi
done

echo "==="
printf "${GREEN}All done!${NC}\n"

if [ "$changes_done" -eq 1 ]; then
	printf "${GREEN}Please restart the container (site) to apply the changes!${NC}\n"
fi
