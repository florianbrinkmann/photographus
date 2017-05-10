<?php
/**
 * Functions file. Includes the different files with the functions.
 *
 * @package Photographus
 */

/**
 * Include file with functions that get hooked to actions.
 */
require_once locate_template( 'inc/action-functions.php' );

/**
 * Include file with functions which filter output.
 */
require_once locate_template( 'inc/filter-functions.php' );

/**
 * Include file with functions for cache updates.
 */
require_once locate_template( 'inc/cache-functions.php' );

/**
 * Include template tags file.
 */
require_once locate_template( 'inc/template-tags.php' );

/**
 * Include upgrade theme file.
 */
require_once locate_template( 'inc/theme-updates.php' );

/**
 * Include file with customizer settings.
 */
require_once locate_template( 'inc/customizer/register.php' );
