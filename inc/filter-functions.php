<?php
/**
 * Theme functions that filter output.
 *
 * @version 1.1.0
 *
 * @package photographus
 */

/**
 * Removes the page jump after clicking on a read more link.
 *
 * @param string $link Post permalink.
 *
 * @return string URL without more hash.
 */
function photographus_remove_more_link_scroll( $link ) {
	$link = preg_replace( '/#more-[0-9]+/', '', $link );

	return $link;
}

/**
 * Add classes to the header, if needed.
 *
 * @param string $classes empty default string.
 *
 * @return string Class string.
 */
function photographus_filter_header_classes( $classes ) {
	// Add -wide-layout class if option for vertical header is not checked and we do not need the compact layout for
	// the front page with header image.
	$alt_header_layout = get_theme_mod( 'photographus_header_layout', false );
	if ( false === $alt_header_layout && ( false === photographus_is_front_page_with_panels() || ! has_header_image() ) ) {
		$classes .= ' -wide-layout';
	}

	// Add -with-header-image class if we are on the front page and have a header image or header video.
	if ( true === photographus_is_front_page_with_panels() && ( has_header_image() || has_header_video() ) ) {
		$classes .= ' -with-header-image';
	}

	return $classes;
}

/**
 * Add classes to the body, if needed.
 *
 * @param array $classes empty default string.
 *
 * @return array Array of body classes.
 */
function photographus_filter_body_classes( $classes ) {
	// Get front page panel number.
	$front_page_panels = photographus_front_page_panel_count();

	// Get post type template.
	$post_type_template = photographus_get_post_type_template();

	// Check if this is a page template which should hide the sidebar
	// (contains »no-sidebar«).
	// Returns false if no-sidebar cannot be found.
	$no_sidebar_pos = strpos( $post_type_template, 'no-sidebar' );

	// Add -no-sidebar class in the following cases:
	// - if we have no sidebar.
	// - if we are on the static front page with panels.
	// - if we are on a single view with a no-sidebar template.
	// - if we are on a single view of an image attachment.
	if (
		! is_active_sidebar( 'sidebar-1' )
		|| ( is_front_page() && is_page() && 0 !== $front_page_panels )
		|| ( is_singular() && false !== $no_sidebar_pos )
		|| true === wp_attachment_is_image( get_the_ID() )
	) {
		$classes[] .= '-no-sidebar';
	} else {
		$classes[] .= '-with-sidebar';
	}

	// Add .-dark-mode class if dark mode option is enabled.
	$dark_mode = get_theme_mod( 'photographus_dark_mode', false );
	if ( true === $dark_mode ) {
		$classes[] .= '-dark-mode';
	}

	return $classes;
}

/**
 * Add classes to post_class().
 *
 * @param array $classes array with post classes.
 *
 * @return array Array of post classes.
 */
function photographus_filter_post_classes( $classes ) {
	// Get the post type template class.
	// Empty string if no template is used.
	$post_type_template_class = photographus_get_post_type_template_class();

	// Add post template class if post has a template.
	if ( '' !== $post_type_template_class ) {
		$classes[] .= $post_type_template_class;
	}

	return $classes;
}
