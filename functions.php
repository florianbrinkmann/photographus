<?php
/**
 * Functions file
 *
 * @package Photographia
 */

if ( ! function_exists( 'photographia_load_translation' ) ) {
	/**
	 * Load translation from languages directory
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
	 */
	function photographia_register_menus() {
		register_nav_menus( [
			/* translators: Name of menu position in the header */
			'primary' => __( 'Primary Menu', 'schlicht' ),
			/* translators: Name of menu position in the footer */
			'footer'  => __( 'Footer Menu', 'schlicht' ),
		] );
	}
}

add_action( 'init', 'photographia_register_menus' );

if ( ! function_exists( 'photographia_register_sidebars' ) ) {
	/**
	 * Register sidebars
	 */
	function photographia_register_sidebars() {
		/**
		 * Registering the main sidebar which is displayed next to the content on larger viewports
		 */
		register_sidebar( [
			'name'          => __( 'Main Sidebar', 'photographia' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Widgets in this area will be displayed on all posts and pages by default.', 'photographia' ),
			'before_widget' => '<div id="%1$s" class="widget clearfix %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		] );

		/**
		 * Registering the widget area for the footer
		 */
		register_sidebar( [
			'name'          => __( 'Footer Sidebar', 'photographia' ),
			'id'            => 'sidebar-footer',
			'description'   => __( 'Widgets will be displayed in the footer.', 'photographia' ),
			'before_widget' => '<div id="%1$s" class="widget clearfix %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		] );
	}
}

add_action( 'widgets_init', 'photographia_register_sidebars' );

if ( ! function_exists( 'photographia_scripts_styles' ) ) {
	/**
	 * Adds the scripts and styles to the header
	 */
	function photographia_scripts_styles() {
		/**
		 * Enqueue script so if a answer to a comment is written, the comment form appears
		 * directly below this comment.
		 * Only enqueue it if in single view with open comments and threaded comments enabled.
		 */
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		/**
		 * Enqueue the Photographia stylesheet.
		 */
		wp_enqueue_style( 'photographia-style', get_theme_file_uri( 'assets/css/photographia.css' ), [], null );
	}
}

add_action( 'wp_enqueue_scripts', 'photographia_scripts_styles' );

/**
 * Add classes to the header, if needed
 *
 * @param string $classes empty default string.
 *
 * @return string
 */
function photographia_filter_header_classes( $classes ) {
	/**
	 * Add -wide-layout class if option for vertical header is not checked
	 */
	$alt_header_layout = get_theme_mod( 'photographia_header_layout', false );
	if ( true !== $alt_header_layout ) {
		$classes .= ' -wide-layout';
	}

	return $classes;
}

add_filter( 'photographia_additional_header_classes', 'photographia_filter_header_classes' );

/**
 * Add classes to the body, if needed
 *
 * @param string $classes empty default string.
 *
 * @return string
 */
function photographia_filter_body_classes( $classes ) {
	/**
	 * Add -no-sidebar class if we have no sidebar
	 */
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] .= ' -no-sidebar';
	}

	return $classes;
}

add_filter( 'body_class', 'photographia_filter_body_classes' );

/**
 * Include template tags file.
 */
require_once locate_template( 'inc/template-tags.php' );

/**
 * Include file with customizer settings.
 */
require_once locate_template( 'inc/customizer.php' );
