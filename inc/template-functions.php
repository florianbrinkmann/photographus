<?php
/**
 * Functions that are not called from the template files
 * and cannot be grouped together into another file.
 *
 * @version 1.0.1
 *
 * @package Photographus
 */

/**
 * Load translation from languages directory.
 */
function photographus_load_translation() {
	if ( ( ! defined( 'DOING_AJAX' ) && ! 'DOING_AJAX' ) || ! photographus_is_login_page() || ! photographus_is_wp_comments_post() ) {
		load_theme_textdomain( 'photographus', get_template_directory() . '/languages' );
	}
}

if ( ! function_exists( 'photographus_is_login_page' ) ) {
	/**
	 * Check if we are on the login page
	 *
	 * @return bool true if on login page, otherwise false.
	 */
	function photographus_is_login_page() {
		return in_array( $GLOBALS['pagenow'], [ 'wp-login.php', 'wp-register.php' ], true );
	}
}

if ( ! function_exists( 'photographus_is_wp_comments_post' ) ) {
	/**
	 * Check if we are on the wp-comments-post.php
	 *
	 * @return bool true if on wp-comments-post.php, otherwise false.
	 */
	function photographus_is_wp_comments_post() {
		return in_array( $GLOBALS['pagenow'], [ 'wp-comments-post.php' ], true );
	}
}

/**
 * Set the content width.
 */
function photographus_set_content_width() {
	// Set the content width to 751.
	$photographus_content_width = 751;

	/**
	 * Make the content width filterable.
	 *
	 * @param int $photographus_content_width Content width in pixels.
	 */
	$GLOBALS['content_width'] = apply_filters( 'photographus_content_width', $photographus_content_width ); // phpcs:ignore
}

/**
 * Adds theme support for feed links, custom head, html5, post formats, post thumbnails, title element and custom logo.
 */
function photographus_add_theme_support() {
	// Add theme support for custom header image (only use height so that WordPress does not
	// show a recommended height of 0).
	add_theme_support(
		'custom-header',
		[
			'height'      => 1000,
			'flex-height' => true,
			'width'       => 1500,
			'flex-width'  => true,
		]
	);

	// Add theme support for feed links (blog feed, comment feeds, …).
	add_theme_support( 'automatic-feed-links' );

	// Add theme support for the title tag.
	add_theme_support( 'title-tag' );

	// Add theme support for the post formats.
	add_theme_support(
		'post-formats',
		[
			'aside',
			'link',
			'gallery',
			'status',
			'quote',
			'image',
			'video',
			'audio',
			'chat',
		]
	);

	// Add theme support for HTML5 markup in Core elements.
	add_theme_support(
		'html5',
		[
			'comment-list',
			'comment-form',
			'search-form',
			'gallery',
			'caption',
		]
	);

	// Add theme support for post thumbnails.
	add_theme_support( 'post-thumbnails' );

	// Add theme support for the custom logo feature.
	add_theme_support( 'custom-logo' );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Create starter content for new sites.
	add_theme_support(
		'starter-content',
		[
			// Add attachments that are used by posts and pages.
			'attachments' => [
				'featured-image-about-page' => [
					'post_title' => 'Featured image for about page',
					'file'       => 'assets/images/starter-content/featured-image-about-page.jpg',
				],
			],

			// Create and modify posts and pages.
			'posts'       => [
				'home'  => [
					'post_content' => __(
						'Welcome to your site! This is your homepage, which is what most visitors will see when they come to your site for the first time.
					
					The »Photographus« theme lets you use different areas for the front page, so-called »panels«. With that, you can display different content types on the front page: You can choose from a grid of your latest gallery and image posts, a list of your latest posts or a single page/post.
					
					To edit the panels you see here, just click on the pen icon on the left.',
						'photographus'
					),
				],
				'about' => [
					'template'     => 'templates/large-portrait-featured-image.php',
					'thumbnail'    => '{{featured-image-about-page}}',
					'post_content' => __( 'Just introduce yourself! This page uses the template with a large portrait featured image. If you do not use a sidebar, the image is displayed next to the content on large viewports.', 'photographus' ),
				],
				'blog',
			],

			// Remove default core starter content widgets.
			'widgets'     => [
				'sidebar-1' => [
					'search',
					'text_about',
				],
			],

			// Set options.
			'options'     => [
				'show_on_front'  => 'page',
				'page_on_front'  => '{{home}}',
				'page_for_posts' => '{{blog}}',
				'header_image'   => get_theme_file_uri( 'assets/images/starter-content/featured-image-about-page.jpg' ),
			],

			// Fill nav menus.
			'nav_menus'   => [
				'primary' => [
					'name'  => __( 'Primary Menu', 'photographus' ),
					'items' => [
						'page_home',
						'page_about',
						'page_blog',
					],
				],
			],

			// Set values for theme mods.
			'theme_mods'  => [
				/**
				 * Set the values for the second front page panel.
				 */
				'photographus_panel_2_content_type' => 'page',
				'photographus_panel_2_page'         => '{{about}}',
			],
		]
	);

	// Disable custom font sizes and colors in Gutenberg.
	add_theme_support( 'disable-custom-font-sizes' );
	add_theme_support( 'disable-custom-colors' );

	add_theme_support( 'editor-styles' );

	add_theme_support(
		'editor-font-sizes',
		[
			[
				'name' => __( 'Normal', 'photographus' ),
				'size' => 16,
				'slug' => 'normal',
			],
		]
	);
}

/**
 * Adds editor styles for the backend editor.
 */
function photographus_add_editor_style() {
	// Add stylesheet and font.
	if ( is_rtl() ) {
		add_editor_style(
			[
				'assets/css/editor-style-rtl.css',
			]
		);
	} else {
		add_editor_style(
			[
				'assets/css/editor-style.css',
			]
		);
	}
}

/**
 * Register Menus.
 */
function photographus_register_menus() {
	register_nav_menus(
		[
			/* translators: Name of menu position in the header */
			'primary' => __( 'Primary Menu', 'photographus' ),
			/* translators: Name of menu position in the footer */
			'footer'  => __( 'Footer Menu', 'photographus' ),
		]
	);
}

/**
 * Register sidebars.
 */
function photographus_register_sidebars() {
	// Registering the main sidebar which is displayed next to the content on larger viewports.
	register_sidebar(
		[
			'name'          => __( 'Main Sidebar', 'photographus' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Widgets in this area will be displayed on all posts and pages by default.', 'photographus' ),
			'before_widget' => '<div id="%1$s" class="widget clearfix %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		]
	);

	// Registering the widget area for the footer.
	register_sidebar(
		[
			'name'          => __( 'Footer Sidebar', 'photographus' ),
			'id'            => 'sidebar-footer',
			'description'   => __( 'Widgets will be displayed in the footer.', 'photographus' ),
			'before_widget' => '<div id="%1$s" class="widget clearfix %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		]
	);
}

/**
 * Adds the scripts and styles to the header.
 */
function photographus_scripts_styles() {
	// Enqueue script so if a answer to a comment is written, the comment form appears
	// directly below this comment.
	// Only enqueue it if in single view with open comments and threaded comments enabled.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Enqueue the Photographus stylesheet.
	if ( is_rtl() ) {
		wp_enqueue_style( 'photographus-style', get_theme_file_uri( 'assets/css/photographus-rtl.css' ), [], filemtime( get_theme_file_path( 'assets/css/photographus-rtl.css' ) ) );
	} else {
		wp_enqueue_style( 'photographus-style', get_theme_file_uri( 'assets/css/photographus.css' ), [], filemtime( get_theme_file_path( 'assets/css/photographus.css' ) ) );
	}

	// Enqueue the Masonry script. This is a newer version than in core and additionally we do not need the
	// »imagesloaded« dependency which would be loaded if we would use the core masonry.
	wp_enqueue_script( 'photographus-masonry', get_theme_file_uri( 'assets/js/masonry.js' ), [], filemtime( get_theme_file_path( 'assets/js/masonry.js' ) ), true );

	// Enqueue the Photographus JavaScript functions.
	wp_enqueue_script( 'photographus-script', get_theme_file_uri( 'assets/js/functions.js' ), [ 'photographus-masonry' ], filemtime( get_theme_file_path( 'assets/js/functions.js' ) ), true );

	// Enqueue the Photographus JavaScript functions.
	wp_enqueue_script( 'photographus-customize-preview-script', get_theme_file_uri( 'assets/js/customize-preview.js' ), [ 'photographus-script' ], filemtime( get_theme_file_path( 'assets/js/customize-preview.js' ) ), true );

	// Remove box shadow from links in admin bar.
	if ( is_admin_bar_showing() ) {
		wp_add_inline_style( 'photographus-style', '#wpadminbar a {box-shadow: none}' );
	}

	// Adding dark mode styles for html inline, because the conditional .-dark-mode class
	// is on the body element.
	$dark_mode = get_theme_mod( 'photographus_dark_mode', false );
	if ( true === $dark_mode ) {
		wp_add_inline_style( 'photographus-style', 'html{ background: #222; color: #eee } ' );
	}
}

if ( ! function_exists( 'photographus_get_trackback_number_text' ) ) {
	/**
	 * Returns string for the number of trackbacks.
	 *
	 * @param array $comments_by_type array of type separated comments.
	 *
	 * @return string Trackback number text or empty string.
	 */
	function photographus_get_trackback_number_text( $comments_by_type ) {
		// Check if we have pings, otherwise return empty string.
		if ( $comments_by_type['pings'] ) {
			// Save the trackback count.
			$trackback_number = count( $comments_by_type['pings'] );

			// Build the trackback number text.
			$trackback_number_text = sprintf( /* translators: s=trackback count */
				__( 'Trackbacks: %s', 'photographus' ),
				sprintf(
					'<a href="%s#trackbacks-title">%s</a>',
					get_permalink(),
					number_format_i18n( $trackback_number )
				)
			);

			return $trackback_number_text;
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'photographus_get_comments_number_text' ) ) {
	/**
	 * Returns string for the number of comments.
	 *
	 * @param array $comments_by_type array of type separated comments.
	 *
	 * @return string Comments number text or empty string.
	 */
	function photographus_get_comments_number_text( $comments_by_type ) {
		// Check if we have comments, otherwise return empty string.
		if ( $comments_by_type['comment'] ) {
			// Save the comment count.
			$comment_number = count( $comments_by_type['comment'] );

			// Build the comments number text.
			$comments_number_text = sprintf( /* translators: s=comment count */
				__( 'Comments: %s', 'photographus' ),
				sprintf(
					'<a href="%s#comments-title">%s</a>',
					get_permalink(),
					number_format_i18n( $comment_number )
				)
			);

			return $comments_number_text;
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'photographus_get_tag_list' ) ) {
	/**
	 * Returns list of tags for a post.
	 *
	 * @return string Tag list or empty string.
	 */
	function photographus_get_tag_list() {
		// Get tag array.
		$tags = get_the_tags();

		// Check if we have a tag array, otherwise return empty string.
		if ( is_array( $tags ) ) {
			// Build the markup.
			$tags_markup = sprintf( /* translators: 1=tag label; 2=tag list */
				__( '%1$s: %2$s', 'photographus' ),
				// Display singular or plural label based on tag count.
				_n(
					'Tag',
					'Tags',
					count( $tags ),
					'photographus'
				), /* translators: term delimiter */
				get_the_tag_list( '', __( ', ', 'photographus' ) )
			);

			return $tags_markup;
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'photographus_get_categories_list' ) ) {
	/**
	 * Returns list of categories for a post.
	 *
	 * @return string Categories list or empty string.
	 */
	function photographus_get_categories_list() {
		// Get array of post categories.
		$categories = get_the_category();

		// Check if we have categories in the array. Otherwise return empty string.
		if ( ! empty( $categories ) ) {
			// Build the markup.
			$categories_markup = sprintf( /* translators: 1=category label; 2=category list */
				__( '%1$s: %2$s', 'photographus' ),
				// Display singular or plural label based on the category count.
				_n(
					'Category',
					'Categories',
					count( $categories ),
					'photographus'
				), /* translators: term delimiter */
				get_the_category_list( __( ', ', 'photographus' ) )
			);

			return $categories_markup;
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'photographus_get_the_date' ) ) {
	/**
	 * Returns get_the_date() with or without a link to the single view.
	 *
	 * @param bool $link If the date should link to the single view.
	 *
	 * @return string Date markup.
	 */
	function photographus_get_the_date( $link = true ) {
		if ( $link ) {
			$date_markup = sprintf(
				'<a href="%s">%s</a>',
				get_the_permalink(),
				get_the_date()
			);
		} else {
			$date_markup = get_the_date();
		}

		return $date_markup;
	}
}

if ( ! function_exists( 'photographus_the_sticky_label' ) ) {
	/**
	 * Display a »Featured« box for sticky posts.
	 *
	 * @return string Sticky label markup.
	 */
	function photographus_get_the_sticky_label() {
		// Just display label if we have a sticky post and
		// are not on the single view of the post.
		if ( is_sticky() && ! is_single() ) {
			/* translators: String for the label of sticky posts. Displayed above the title */
			$sticky_label_markup = sprintf(
				'<p class="sticky-post-featured-string"><span>%s</span></p>',
				__( 'Featured', 'photographus' )
			);
		} else {
			$sticky_label_markup = '';
		}

		return $sticky_label_markup;
	}
}

if ( ! function_exists( 'photographus_dequeue_gutenberg_block_styles' ) ) {
	/**
	 * Dequeue default Gutenberg block styles.
	 */
	function photographus_dequeue_gutenberg_block_styles() {
		wp_dequeue_style( 'wp-block-library' );
		wp_deregister_style( 'wp-block-library' );
	}
}

if ( ! function_exists( 'photographus_enqueue_block_editor_assets' ) ) {
	/**
	 * Add JS to block editor.
	 */
	function photographus_enqueue_block_editor_assets() {
		// phpcs:ignore
		wp_enqueue_script(
			'photographus-gutenberg-block-editor-script',
			get_theme_file_uri( '/assets/js/block-editor-script.js' ),
			[ 'wp-blocks', 'wp-edit-post' ],
			filemtime( get_theme_file_path( 'assets/js/block-editor-script.js' ) )
		);
	}
}
