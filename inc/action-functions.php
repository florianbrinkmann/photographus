<?php
/**
 * Theme functions that get hooked to actions.
 *
 * @version 1.0.0
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

if ( ! function_exists( 'photographia_add_theme_support' ) ) {
	/**
	 * Adds theme support for feed links, custom head, html5, post formats, post thumbnails, title element and custom logo
	 */
	function photographia_add_theme_support() {
		add_theme_support( 'custom-header', [
			'height' => 1000,
		] );
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
		add_theme_support( 'customize-selective-refresh-widgets' );
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

		/**
		 * Enqueue the Masonry script. This is a newer version than in core and additionally we do not need the
		 * »imagesloaded« dependency which would be loaded if we would use the core masonry.
		 */
		wp_enqueue_script( 'photographia-masonry', get_theme_file_uri( 'assets/js/masonry.js' ), [], null, true );

		/**
		 * Enqueue the Photographia JavaScript functions.
		 */
		wp_enqueue_script( 'photographia-script', get_theme_file_uri( 'assets/js/functions.js' ), [ 'photographia-masonry' ], null, true );

		/**
		 * Enqueue the Photographia JavaScript functions.
		 */
		wp_enqueue_script( 'photographia-customize-preview-script', get_theme_file_uri( 'assets/js/customize-preview.js' ), [ 'photographia-script' ], null, true );

		/**
		 * Remove box shadow from links in admin bar.
		 */
		if ( is_admin_bar_showing() ) {
			wp_add_inline_style( 'photographia-style', '#wpadminbar a {box-shadow: none}' );
		}
	}
}

add_action( 'wp_enqueue_scripts', 'photographia_scripts_styles' );
