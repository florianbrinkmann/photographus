<?php
/**
 * Theme functions that filter output.
 *
 * @version 1.0.0
 *
 * @package Photographus
 */

/**
 * Add classes to the header, if needed
 *
 * @param string $classes empty default string.
 *
 * @return string
 */
function photographus_filter_header_classes( $classes ) {
	/**
	 * Add -wide-layout class if option for vertical header is not checked and we do not need the compact layout for
	 * the front page with header image.
	 */
	$alt_header_layout = get_theme_mod( 'photographus_header_layout', false );
	if ( false === $alt_header_layout && ( false === photographus_is_front_page_with_panels() || ! has_header_image() ) ) {
		$classes .= ' -wide-layout';
	}

	/**
	 * Add -with-header-image class if we are on the front page and have a header image or header video.
	 */
	if ( true === photographus_is_front_page_with_panels() && ( has_header_image() || has_header_video() ) ) {
		$classes .= ' -with-header-image';
	}

	return $classes;
}

add_filter( 'photographus_additional_header_classes', 'photographus_filter_header_classes' );

/**
 * Add classes to the body, if needed
 *
 * @param string $classes empty default string.
 *
 * @return string
 */
function photographus_filter_body_classes( $classes ) {
	/**
	 * Get front page panel number.
	 */
	$front_page_panels = photographus_front_page_panel_count();

	/**
	 * Get post type template.
	 */
	$post_type_template = photographus_get_post_type_template();

	/**
	 * Check if this is a page template which should hide the sidebar
	 * (contains »no-sidebar«).
	 * Returns false if no-sidebar cannot be found.
	 */
	$no_sidebar_pos = strpos( $post_type_template, 'no-sidebar' );

	/**
	 * Add -no-sidebar class if we have no sidebar or are on a page
	 * with the front page template.
	 */
	if ( ! is_active_sidebar( 'sidebar-1' ) || ( is_front_page() && is_page() && 0 !== $front_page_panels ) || $no_sidebar_pos !== false ) {
		$classes[] .= '-no-sidebar';
	} else {
		$classes[] .= '-with-sidebar';
	}

	return $classes;
}

add_filter( 'body_class', 'photographus_filter_body_classes' );

/**
 * Add classes to post_class()
 *
 * @param array $classes array with post classes.
 *
 * @return array
 */
function photographus_filter_post_classes( $classes ) {
	/**
	 * Get the post type template class.
	 * Empty string if no template is used.
	 */
	$post_type_template_class = photographus_get_post_type_template_class();

	/**
	 * Add post template class if post has a template
	 */
	if ( '' !== $post_type_template_class ) {
		$classes[] .= $post_type_template_class;
	}

	return $classes;
}

add_filter( 'post_class', 'photographus_filter_post_classes' );
