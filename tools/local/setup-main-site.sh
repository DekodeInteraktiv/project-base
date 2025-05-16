#!/bin/bash

wp option update blogname "Project Base"
wp option update blogdescription ""
wp option update permalink_structure "/%postname%/"
wp option update timezone_string "Europe/Oslo"
wp option update date_format "j. F Y"
wp option update time_format "H:i"
wp language core install nb_NO
wp site switch-language nb_NO

wp theme enable block-theme --network
wp theme activate block-theme

wp plugin activate t2

wp rewrite flush
