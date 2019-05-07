<?php
/**
 * Functions file. Includes the different files with the functions.
 *
 * @package photographus
 */

// Include file with add_action() calls.
require_once locate_template( 'inc/actions.php' );

// Include file with add_filter() calls.
require_once locate_template( 'inc/filters.php' );

// Include file with filtering functions.
require_once locate_template( 'inc/filter-functions.php' );

// Include file with functions for cache updates.
require_once locate_template( 'inc/cache-functions.php' );

// Include file with functions called from template files.
require_once locate_template( 'inc/template-tags.php' );

// Include template functions file.
require_once locate_template( 'inc/template-functions.php' );

// Include front page panel functions file.
require_once locate_template( 'inc/front-page-panel-functions.php' );

// Include file with theme upgrade functions.
require_once locate_template( 'inc/theme-updates.php' );

// Include file with customizer settings.
require_once locate_template( 'inc/customizer/register.php' );
