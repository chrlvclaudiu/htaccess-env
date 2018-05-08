#!/bin/bash

# USE OUR ENVIRONMENT-BASED HTACCESS FILES
CURRENT_BRANCH=$(git rev-parse --abbrev-ref HEAD)
MAIN_HTACCESS_PATH="docroot/.htaccess"
ENV_BASED_HTACCESS="$CURRENT_BRANCH.htaccess"

if [ -f $ENVIRONMENT-BASED ]; then
	echo "$ENV_BASED_HTACCESS exists, using it to replace $MAIN_HTACCESS_PATH..."
	if [ ! -f $MAIN_HTACCESS_PATH ]; then
		echo "$MAIN_HTACCESS_PATH does not exist. Skipping..."
    else
    	#cat $ENV_BASED_HTACCESS > $MAIN_HTACCESS_PATH
    	echo "Successfully replaced $MAIN_HTACCESS_PATH contents with $ENV_BASED_HTACCESS."
	fi
fi