#!/bin/bash

wp option update blogname "Project Base"
wp option update blogdescription ""
wp option update permalink_structure "/%postname%/"
wp option update timezone_string "Europe/Oslo"

wp theme enable dekode-theme --activate

wp site switch-language nb_NO

wp rewrite flush
