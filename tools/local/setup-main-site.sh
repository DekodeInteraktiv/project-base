#!/bin/bash

wp option update blogname "Project Base"
wp option update blogdescription ""
wp option update permalink_structure "/%postname%/"
wp option update timezone_string "Europe/Oslo"

wp theme enable block-theme --activate

wp language core install nb_NO
wp site switch-language nb_NO

wp rewrite flush
