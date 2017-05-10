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

		/**
		 * Create starter content for new sites.
		 */
		add_theme_support( 'starter-content', [
			/**
			 * Add attachments that are used by posts and pages.
			 */
			'attachments' => [
				'featured-image-about-page' => [
					'post_title' => 'Featured image for about page',
					'file'       => 'assets/images/starter-content/featured-image-about-page.jpg',
				],
			],

			/**
			 * Create and modify posts and pages.
			 */
			'posts'       => [
				'home'  => [
					'post_content' => __( 'Welcome to your site! This is your homepage, which is what most visitors will see when they come to your site for the first time.
					
					The »Photographia« theme lets you use different areas for the front page, so-called »panels«. With that, you can display different content types on the front page: You can choose from a grid of your latest gallery and image posts, a list of your latest posts or a single page/post.
					
					To edit the panels you see here, just click on the pen icon on the left.', 'photographia' ),
				],
				'about' => [
					'template'     => 'templates/large-portrait-featured-image.php',
					'thumbnail'    => '{{featured-image-about-page}}',
					'post_content' => __( 'Just introduce yourself! This page uses the template with a large portrait featured image. If you do not use a sidebar, the image is displayed next to the content on large viewports.', 'photographia' ),
				],
				'blog',
			],

			/**
			 * Remove default core starter content widgets
			 */
			'widgets'     => [
				'sidebar-1' => [
					'search',
					'text_about',
				]
			],

			/**
			 * Set options
			 */
			'options'     => [
				'show_on_front'  => 'page',
				'page_on_front'  => '{{home}}',
				'page_for_posts' => '{{blog}}',
				'header_image'   => get_theme_file_uri( 'assets/images/starter-content/featured-image-about-page.jpg' ),
			],

			/**
			 * Fill nav menus
			 */
			'nav_menus'   => [
				'primary' => [
					'name'  => __( 'Primary Menu', 'photographia' ),
					'items' => [
						'page_home',
						'page_about',
						'page_blog',
					],
				],
			],

			/**
			 * Set values for theme mods
			 */
			'theme_mods'  => [
				/**
				 * Set the values for the first front page panel.
				 */
				'photographia_panel_1_content_type'               => 'latest-posts',
				'photographia_panel_1_latest_posts_short_version' => true,

				/**
				 * Set the values for the second front page panel.
				 */
				'photographia_panel_2_content_type'               => 'page',
				'photographia_panel_2_page'                       => '{{snowy-landscape}}',

				/**
				 * Set the values for the third front page panel.
				 */
				'photographia_panel_2_content_type'               => 'page',
				'photographia_panel_2_page'                       => '{{about}}',
			],
		] );
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
		 * Enqueue the PT Serif font from Google fonts.
		 */
		wp_enqueue_style( 'photographia-font', 'https://fonts.googleapis.com/css?family=PT+Serif:400,400i,700,700i', [], null );

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

		/**
		 * Dark mode styles.
		 */
		$dark_mode = get_theme_mod( 'photographia_dark_mode', false );
		if ( true === $dark_mode ) {
			wp_add_inline_style( 'photographia-style', 'html{ background: #222; color: #eee } 
		.sticky-post-featured-string > span { background: #eee; color: #222; }
		.entry-title::before, .frontpage-section::before { background: #eee; }
		a:focus { background: #eee; box-shadow: inset 0 -1px 0 #eee, 4px 0 0 #eee, -4px 0 0 #eee; color: #222; }' );
		}
	}
}

add_action( 'wp_enqueue_scripts', 'photographia_scripts_styles' );
