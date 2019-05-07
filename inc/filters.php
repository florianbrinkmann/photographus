<?php
/**
 * All add_filter() calls.
 *
 * @version 1.1.0
 *
 * @package photographus
 */

// Removes the #more jump from more links.
add_filter( 'the_content_more_link', 'photographus_remove_more_link_scroll' );

// Filters the header classes.
add_filter( 'photographus_additional_header_classes', 'photographus_filter_header_classes' );

// Filters the body classes.
add_filter( 'body_class', 'photographus_filter_body_classes' );

// Filters the post classes.
add_filter( 'post_class', 'photographus_filter_post_classes' );

// Filters the theme update transient.
add_filter( 'pre_set_site_transient_update_themes', 'photographus_theme_update' );
