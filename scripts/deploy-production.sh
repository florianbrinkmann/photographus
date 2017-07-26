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

# install WP-CLI
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar --silent && chmod +x wp-cli.phar

# run phpdoc-parser tasks and delete parser posts from older runs before that.
php wp-cli.phar post delete $(php wp-cli.phar post list --post_type='wp-parser-function' --format=ids --user=florian --url=florianbrinkmann.com --allow-root --ssh=$PRODUCTION_SERVER_USER@$PRODUCTION_SERVER$PRODUCTION_PATH_HTML/ --quiet) --user=florian --url=florianbrinkmann.com --allow-root --ssh=$PRODUCTION_SERVER_USER@$PRODUCTION_SERVER$PRODUCTION_PATH_HTML/ --quiet
php wp-cli.phar post delete $(php wp-cli.phar post list --post_type='wp-parser-method' --format=ids --user=florian --url=florianbrinkmann.com --allow-root --ssh=$PRODUCTION_SERVER_USER@$PRODUCTION_SERVER$PRODUCTION_PATH_HTML/ --quiet) --user=florian --url=florianbrinkmann.com --allow-root --ssh=$PRODUCTION_SERVER_USER@$PRODUCTION_SERVER$PRODUCTION_PATH_HTML/ --quiet
php wp-cli.phar post delete $(php wp-cli.phar post list --post_type='wp-parser-class' --format=ids --user=florian --url=florianbrinkmann.com --allow-root --ssh=$PRODUCTION_SERVER_USER@$PRODUCTION_SERVER$PRODUCTION_PATH_HTML/ --quiet) --user=florian --url=florianbrinkmann.com --allow-root --ssh=$PRODUCTION_SERVER_USER@$PRODUCTION_SERVER$PRODUCTION_PATH_HTML/ --quiet
php wp-cli.phar post delete $(php wp-cli.phar post list --post_type='wp-parser-hook' --format=ids --user=florian --url=florianbrinkmann.com --allow-root --ssh=$PRODUCTION_SERVER_USER@$PRODUCTION_SERVER$PRODUCTION_PATH_HTML/ --quiet) --user=florian --url=florianbrinkmann.com --allow-root --ssh=$PRODUCTION_SERVER_USER@$PRODUCTION_SERVER$PRODUCTION_PATH_HTML/ --quiet
php wp-cli.phar parser create wp-content/themes-for-docblock-parser/ --user=florian --url=florianbrinkmann.com --allow-root --ssh=$PRODUCTION_SERVER_USER@$PRODUCTION_SERVER$PRODUCTION_PATH_HTML/ --quiet
php wp-cli.phar post delete $(php wp-cli.phar post list --post_type='wp-parser-function' --format=ids --user=florian --url=florianbrinkmann.com/en --allow-root --ssh=$PRODUCTION_SERVER_USER@$PRODUCTION_SERVER$PRODUCTION_PATH_HTML/ --quiet) --user=florian --url=florianbrinkmann.com/en --allow-root --ssh=$PRODUCTION_SERVER_USER@$PRODUCTION_SERVER$PRODUCTION_PATH_HTML/ --quiet
php wp-cli.phar post delete $(php wp-cli.phar post list --post_type='wp-parser-method' --format=ids --user=florian --url=florianbrinkmann.com/en --allow-root --ssh=$PRODUCTION_SERVER_USER@$PRODUCTION_SERVER$PRODUCTION_PATH_HTML/ --quiet) --user=florian --url=florianbrinkmann.com/en --allow-root --ssh=$PRODUCTION_SERVER_USER@$PRODUCTION_SERVER$PRODUCTION_PATH_HTML/ --quiet
php wp-cli.phar post delete $(php wp-cli.phar post list --post_type='wp-parser-class' --format=ids --user=florian --url=florianbrinkmann.com/en --allow-root --ssh=$PRODUCTION_SERVER_USER@$PRODUCTION_SERVER$PRODUCTION_PATH_HTML/ --quiet) --user=florian --url=florianbrinkmann.com/en --allow-root --ssh=$PRODUCTION_SERVER_USER@$PRODUCTION_SERVER$PRODUCTION_PATH_HTML/ --quiet
php wp-cli.phar post delete $(php wp-cli.phar post list --post_type='wp-parser-hook' --format=ids --user=florian --url=florianbrinkmann.com/en --allow-root --ssh=$PRODUCTION_SERVER_USER@$PRODUCTION_SERVER$PRODUCTION_PATH_HTML/ --quiet) --user=florian --url=florianbrinkmann.com/en --allow-root --ssh=$PRODUCTION_SERVER_USER@$PRODUCTION_SERVER$PRODUCTION_PATH_HTML/ --quiet
php wp-cli.phar parser create wp-content/themes-for-docblock-parser/ --user=florian --url=florianbrinkmann.com/en --allow-root --ssh=$PRODUCTION_SERVER_USER@$PRODUCTION_SERVER$PRODUCTION_PATH_HTML/ --quiet
