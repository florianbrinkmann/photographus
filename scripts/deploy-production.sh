#!/bin/bash

# create directories on production server if not exist yet.
ssh -p22 $PRODUCTION_SERVER_USER@$PRODUCTION_SERVER "mkdir -p $PRODUCTION_PATH_HTML/wp-content/themes/_tmp-photographus"
ssh -p22 $PRODUCTION_SERVER_USER@$PRODUCTION_SERVER "mkdir -p $PRODUCTION_PATH_HTML/wp-content/themes/photographus"

# rsync data to production server.
rsync -ravq -e ssh --exclude-from='.rsync-exclude' --delete-excluded ./ $PRODUCTION_SERVER_USER@$PRODUCTION_SERVER:$PRODUCTION_PATH_HTML/wp-content/themes/_tmp-photographus

# rsync download ZIP to downloads folder on production server.
rsync -avq -e ssh ./photographus.zip $PRODUCTION_SERVER_USER@$PRODUCTION_SERVER:$PRODUCTION_DOWNLOADS_PATH

# copy and move files to the right place.
ssh -p22 $PRODUCTION_SERVER_USER@$PRODUCTION_SERVER "mv $PRODUCTION_PATH_HTML/wp-content/themes/photographus $PRODUCTION_PATH_HTML/wp-content/themes/_old-photographus && mv $PRODUCTION_PATH_HTML/wp-content/themes/_tmp-photographus $PRODUCTION_PATH_HTML/wp-content/themes/photographus"
ssh -p22 $PRODUCTION_SERVER_USER@$PRODUCTION_SERVER "rm -rf $PRODUCTION_PATH_HTML/wp-content/themes/_old-photographus"
ssh -p22 $PRODUCTION_SERVER_USER@$PRODUCTION_SERVER "mkdir -p $PRODUCTION_PATH_HTML/wp-content/themes-for-docblock-parser"
ssh -p22 $PRODUCTION_SERVER_USER@$PRODUCTION_SERVER "mkdir -p $PRODUCTION_PATH_HTML/wp-content/themes-for-docblock-parser-tmp"
ssh -p22 $PRODUCTION_SERVER_USER@$PRODUCTION_SERVER "mkdir -p $PRODUCTION_PATH_HTML/wp-content/themes-for-docblock-parser/photographus"
ssh -p22 $PRODUCTION_SERVER_USER@$PRODUCTION_SERVER "mv $PRODUCTION_PATH_HTML/wp-content/themes-for-docblock-parser/photographus $PRODUCTION_PATH_HTML/wp-content/themes-for-docblock-parser-tmp/_old-photographus && cp -Rf $PRODUCTION_PATH_HTML/wp-content/themes/photographus $PRODUCTION_PATH_HTML/wp-content/themes-for-docblock-parser/photographus"
ssh -p22 $PRODUCTION_SERVER_USER@$PRODUCTION_SERVER "rm -rf $PRODUCTION_PATH_HTML/wp-content/themes-for-docblock-parser-tmp/_old-photographus"
