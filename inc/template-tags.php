<?php
/**
 * Template tags, used in template files
 *
 * @version 1.0.0
 *
 * @package Photographia
 */

if ( ! function_exists( 'photographia_get_custom_logo' ) ) {
	/**
	 * Wrap inside function_exists() to preserve back compat with WordPress versions older than 4.5
	 *
	 * @return string
	 */
	function photographia_get_custom_logo() {
		if ( function_exists( 'get_custom_logo' ) ) {
			if ( has_custom_logo() ) {
				return get_custom_logo();
			}
		}
	}
}

if ( ! function_exists( 'photographia_the_title' ) ) {
	/**
	 * Displays the title of a post wrapped with a heading and optionally with a link to the post.
	 *
	 * @param string $heading Type of heading.
	 * @param bool   $link    If the title should be linked to the single view or not.
	 *
	 * @return void
	 */
	function photographia_the_title( $heading, $link = true ) {
		if ( $link ) {
			the_title( sprintf(
				'<%1$s class="entry-title"><a href="%2$s" rel="bookmark">',
				$heading, esc_url( get_permalink() )
			), sprintf( '</a></%s>', $heading ) );
		} else {
			the_title( sprintf(
				'<%1$s class="entry-title">',
				$heading, esc_url( get_permalink() )
			), sprintf( '</%s>', $heading ) );
		}
	}
}


if ( ! function_exists( 'photographia_the_sticky_label' ) ) {
	/**
	 * Display a »Featured« box for sticky posts.
	 *
	 * @return void
	 */
	function photographia_the_sticky_label() {
		if ( is_sticky() ) {
			/* translators: String for the label of sticky posts. Displayed above the title */
			printf(
				'<p class="sticky-post-featured-string"><span>%s</span></p>',
				__( 'Featured', 'photographia' )
			);
		}
	}
}
