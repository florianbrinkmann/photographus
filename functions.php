<?php
/**
 * Functions file
 *
 * @package Photographia
 */

if ( ! function_exists( 'photographia_load_translation' ) ) {
	/**
	 * Load translation from languages directory
	 *
	 * @codeCoverageIgnore
	 */
	function photographia_load_translation() {
		if ( ( ! defined( 'DOING_AJAX' ) && ! 'DOING_AJAX' ) || ! photographia_is_login_page() || ! photographia_is_wp_comments_post() ) {
			load_theme_textdomain( 'photographia', get_template_directory() . '/languages' );
		}
	}
}

add_action( 'after_setup_theme', 'photographia_load_translation' );

if ( ! function_exists( 'photographia_is_login_page' ) ) {
	/**
	 * Check if we are on the login page
	 *
	 * @return bool
	 */
	function photographia_is_login_page() {
		return in_array( $GLOBALS['pagenow'], [ 'wp-login.php', 'wp-register.php' ], true );
	}
}

if ( ! function_exists( 'photographia_is_wp_comments_post' ) ) {
	/**
	 * Check if we are on the wp-comments-post.php
	 *
	 * @return bool
	 */
	function photographia_is_wp_comments_post() {
		return in_array( $GLOBALS['pagenow'], [ 'wp-comments-post.php' ], true );
	}
}

if ( ! function_exists( 'photographia_add_theme_support' ) ) {
	/**
	 * Adds theme support for feed links, custom head, html5, post formats, post thumbnails, title element and custom logo
	 *
	 * @codeCoverageIgnore
	 */
	function photographia_add_theme_support() {
		add_theme_support( 'custom-header' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-formats', [
			'aside',
			'link',
			'gallery',
			'status',
			'quote',
			'image',
			'video',
			'audio',
			'chat',
		] );
		add_theme_support( 'html5', [
			'comment-list',
			'comment-form',
			'search-form',
			'gallery',
			'caption',
		] );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'custom-logo' );
	}
}

add_action( 'after_setup_theme', 'photographia_add_theme_support' );

if ( ! function_exists( 'photographia_register_menus' ) ) {
	/**
	 * Register Menus
	 *
	 * @codeCoverageIgnore
	 */
	function photographia_register_menus() {
		register_nav_menus(
			[
				/* translators: Name of menu position in the header */
				'primary' => __( 'Primary Menu', 'schlicht' ),
				/* translators: Name of menu position in the footer */
				'footer'  => __( 'Footer Menu', 'schlicht' ),
			]
		);
	}
}

add_action( 'init', 'photographia_register_menus' );

require_once locate_template( 'inc/template-tags.php' ); // @codeCoverageIgnore
