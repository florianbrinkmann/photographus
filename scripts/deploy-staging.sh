#!/bin/bash

ssh -p22 $STAGING_SERVER_USER@$STAGING_SERVER "mkdir -p $STAGING_PATH_STABLE/wp-content/themes/photographus"
ssh -p22 $STAGING_SERVER_USER@$STAGING_SERVER "mkdir -p $STAGING_PATH_TRUNK/wp-content/themes/photographus"
rsync -ravq -e ssh --exclude-from='.rsync-exclude' --delete-excluded ./ $STAGING_SERVER_USER@$STAGING_SERVER:$STAGING_PATH_TRUNK/wp-content/themes/photographus
rsync -ravq -e ssh --exclude-from='.rsync-exclude' --delete-excluded ./ $STAGING_SERVER_USER@$STAGING_SERVER:$STAGING_PATH_STABLE/wp-content/themes/photographus
rsync -ravq -e ssh --exclude-from='.rsync-exclude' --delete-excluded ./ $STAGING_SERVER_USER@$STAGING_SERVER:$STAGING_PATH_HTML/wp-content/themes/photographus
ssh -p22 $STAGING_SERVER_USER@$STAGING_SERVER "mkdir -p $STAGING_PATH_HTML/wp-content/themes-for-docblock-parser"
ssh -p22 $STAGING_SERVER_USER@$STAGING_SERVER "mkdir -p $STAGING_PATH_HTML/wp-content/themes-for-docblock-parser-tmp"
ssh -p22 $STAGING_SERVER_USER@$STAGING_SERVER "mkdir -p $STAGING_PATH_HTML/wp-content/themes-for-docblock-parser/photographus"
ssh -p22 $STAGING_SERVER_USER@$STAGING_SERVER "mv $STAGING_PATH_HTML/wp-content/themes-for-docblock-parser/photographus $STAGING_PATH_HTML/wp-content/themes-for-docblock-parser-tmp/_old-photographus && cp -Rf $STAGING_PATH_HTML/wp-content/themes/photographus $STAGING_PATH_HTML/wp-content/themes-for-docblock-parser/photographus"
ssh -p22 $STAGING_SERVER_USER@$STAGING_SERVER "rm -rf $STAGING_PATH_HTML/wp-content/themes-for-docblock-parser-tmp/_old-photographus"
