<?php
/**
 * Registers the customizer settings and controls.
 *
 * @version 1.0.1
 *
 * @package Photographus
 */

/**
 * Customizer settings
 *
 * @param WP_Customize_Manager $wp_customize The Customizer object.
 */
function photographus_customize_register( $wp_customize ) {
	$wp_customize->remove_control( 'header_textcolor' );

	$wp_customize->add_section( 'photographus_options', [
		'title' => __( 'Theme options', 'photographus' ),
	] );

	// Add setting for alternative header layout.
	$wp_customize->add_setting( 'photographus_header_layout', [
		'default'           => false,
		'sanitize_callback' => 'photographus_sanitize_checkbox',
	] );

	// Add control for alternative header layout.
	$wp_customize->add_control( 'photographus_header_layout', [
		'type'    => 'checkbox',
		'section' => 'photographus_options',
		'label'   => __( 'Alternative header layout', 'photographus' ),
	] );

	// Add setting for dark mode.
	$wp_customize->add_setting( 'photographus_dark_mode', [
		'default'           => false,
		'sanitize_callback' => 'photographus_sanitize_checkbox',
	] );

	// Add control for dark mode.
	$wp_customize->add_control( 'photographus_dark_mode', [
		'type'    => 'checkbox',
		'section' => 'photographus_options',
		'label'   => __( 'Dark mode', 'photographus' ),
	] );

	// Add setting for hiding the content of the static front page if panels are used.
	$wp_customize->add_setting( 'photographus_hide_static_front_page_content', [
		'default'           => false,
		'sanitize_callback' => 'photographus_sanitize_checkbox',
	] );

	// Add control for hiding the content of the static front page if panels are used.
	$wp_customize->add_control( 'photographus_hide_static_front_page_content', [
		'type'            => 'checkbox',
		'section'         => 'photographus_options',
		'label'           => __( 'Hide the content of the static front page if panels are used.', 'photographus' ),
		'active_callback' => 'photographus_is_static_front_page',
	] );

	// Set active callback for the header image control.
	// @link https://gist.github.com/pagelab/10406104
	$wp_customize->get_control( 'header_image' )->active_callback = 'photographus_is_static_front_page';


	// Front page panels. Inspired by https://core.trac.wordpress.org/browser/tags/4.7.3/src/wp-content/themes/twentyseventeen/inc/customizer.php#L88

	/**
	 * Filter number of front page sections in Photographus.
	 *
	 * @param int $num_sections Number of front page sections.
	 */
	$num_sections = apply_filters( 'photographus_front_page_sections', 4 );


	/**
	 * Filter number of posts which are displayed in the post panel dropdown.
	 *
	 * @param int $post_number Number of posts.
	 */
	$post_number = apply_filters( 'photographus_front_page_posts_number', 500 );

	// Get the last posts for the dropdown menu for post panels.
	$post_panel_posts = new WP_Query( [
		'post_type'      => 'post',
		'posts_per_page' => $post_number,
		'no_found_rows'  => true,
		'post_status'    => 'publish',
		'has_password'   => false,
	] );

	// Build the choices array for the post panel.
	$post_panel_choices = [];
	if ( $post_panel_posts->have_posts() ) {
		while ( $post_panel_posts->have_posts() ) {
			$post_panel_posts->the_post();

			$post_panel_choices[ get_the_ID() ] = get_the_title();
		}
	}

	// Build the choices array for the post grid panel category.
	// @link https://blog.josemcastaneda.com/2015/05/13/customizer-dropdown-category-selection/
	$cats = [];
	foreach ( get_categories() as $categories => $category ) {
		$cats[ $category->term_id ] = $category->name;
	}

	// Add a value to the array so the user can choose a neutral value when he does not
	// want to show a post.
	$first_select_value = [ 0 => __( '— Select —', 'photographus' ) ];
	$cats               = $first_select_value + $cats;

	// Create a setting and control for each of the sections available in the theme.
	for ( $i = 1; $i < ( 1 + $num_sections ); $i ++ ) {
		// Create setting for saving the content type choice.
		$wp_customize->add_setting( "photographus_panel_{$i}_content_type", [
			'default'           => 0,
			'sanitize_callback' => 'photographus_sanitize_select',
			'transport'         => 'postMessage',
		] );

		// Create control for content choice.
		$wp_customize->add_control( "photographus_panel_{$i}_content_type", [
			/* translators: d = number of panel in customizer */
			'label'           => sprintf( __( "Panel %d", 'photographus' ), $i ),
			'type'            => 'select',
			'section'         => 'photographus_options',
			'choices'         => [
				0              => __( '— Select —', 'photographus' ),
				'page'         => __( 'Page', 'photographus' ),
				'post'         => __( 'Post', 'photographus' ),
				'latest-posts' => __( 'Latest Posts', 'photographus' ),
				'post-grid'    => __( 'Post Grid', 'photographus' ),
			],
			'active_callback' => 'photographus_is_static_front_page',
		] );

		$wp_customize->selective_refresh->add_partial( "photographus_panel_{$i}_content_type_partial", [
			'selector'            => "#frontpage-section-$i",
			'settings'            => [
				"photographus_panel_{$i}_content_type",
			],
			'render_callback'     => 'photographus_the_front_page_panels',
			'container_inclusive' => true,
		] );

		// Create setting for page.
		$wp_customize->add_setting( "photographus_panel_{$i}_page", [
			'default'           => false,
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		] );

		// Create control for page.
		$wp_customize->add_control( "photographus_panel_{$i}_page", [
			'label'           => __( 'Select page', 'photographus' ),
			'section'         => 'photographus_options',
			'type'            => 'dropdown-pages',
			'allow_addition'  => true,
			'active_callback' => 'photographus_is_page_panel',
			'input_attrs'     => [
				'data-panel-number' => $i,
			],
		] );

		$wp_customize->selective_refresh->add_partial( "photographus_panel_{$i}_page_partial", [
			'selector'            => "#frontpage-section-$i",
			'settings'            => [
				"photographus_panel_{$i}_page",
			],
			'render_callback'     => 'photographus_the_page_panel',
			'container_inclusive' => true,
		] );

		// Check if we have posts to show, before creating the post controls and settings.
		if ( ! empty( $post_panel_choices ) ) {
			// Add a value to the array so the user can choose a neutral value when he does not
			// want to show a post.
			$first_select_value = [ 0 => __( '— Select —', 'photographus' ) ];
			$post_panel_choices = $first_select_value + $post_panel_choices;

			// Create setting for post.
			$wp_customize->add_setting( "photographus_panel_{$i}_post", [
				'default'           => 0,
				'sanitize_callback' => 'absint',
				'transport'         => 'postMessage',
			] );

			// Create control for post.
			$wp_customize->add_control( "photographus_panel_{$i}_post", [
				'label'           => __( 'Select post', 'photographus' ),
				'section'         => 'photographus_options',
				'type'            => 'select',
				'choices'         => $post_panel_choices,
				'active_callback' => 'photographus_is_post_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );

			$wp_customize->selective_refresh->add_partial( "photographus_panel_{$i}_post_partial", [
				'selector'            => "#frontpage-section-$i",
				'settings'            => [
					"photographus_panel_{$i}_post",
				],
				'render_callback'     => 'photographus_the_post_panel',
				'container_inclusive' => true,
			] );

			// Create setting for latest posts section title.
			$wp_customize->add_setting( "photographus_panel_{$i}_latest_posts_title", [
				'default'           => __( 'Latest Posts', 'photographus' ),
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			] );

			// Create control for latest posts section title.
			$wp_customize->add_control( "photographus_panel_{$i}_latest_posts_title", [
				'label'           => __( 'Section title', 'photographus' ),
				'section'         => 'photographus_options',
				'type'            => 'text',
				'active_callback' => 'photographus_is_latest_posts_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );

			// Create setting for latest posts.
			$wp_customize->add_setting( "photographus_panel_{$i}_latest_posts_number", [
				'default'           => 5,
				'sanitize_callback' => 'absint',
				'transport'         => 'postMessage',
			] );

			// Create control for latest posts.
			$wp_customize->add_control( "photographus_panel_{$i}_latest_posts_number", [
				'label'           => __( 'Number of posts', 'photographus' ),
				'section'         => 'photographus_options',
				'type'            => 'number',
				'active_callback' => 'photographus_is_latest_posts_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );

			// Create setting to only show title and header meta of latest posts.
			$wp_customize->add_setting( "photographus_panel_{$i}_latest_posts_short_version", [
				'default'           => false,
				'sanitize_callback' => 'photographus_sanitize_checkbox',
				'transport'         => 'postMessage',
			] );

			// Create control to only show title and header meta of latest posts.
			$wp_customize->add_control( "photographus_panel_{$i}_latest_posts_short_version", [
				'label'           => __( 'Only show title and header meta of posts.', 'photographus' ),
				'section'         => 'photographus_options',
				'type'            => 'checkbox',
				'active_callback' => 'photographus_is_latest_posts_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );

			$wp_customize->selective_refresh->add_partial( "photographus_panel_{$i}_latest_posts_partial", [
				'selector'            => "#frontpage-section-$i",
				'settings'            => [
					"photographus_panel_{$i}_latest_posts_title",
					"photographus_panel_{$i}_latest_posts_number",
					"photographus_panel_{$i}_latest_posts_short_version",
				],
				'render_callback'     => 'photographus_the_latest_posts_panel',
				'container_inclusive' => true,
			] );

			// Create setting for post grid section title.
			$wp_customize->add_setting( "photographus_panel_{$i}_post_grid_title", [
				'default'           => __( 'Post Grid', 'photographus' ),
				'sanitize_callback' => 'sanitize_text_field',
				'transport'         => 'postMessage',
			] );

			// Create control for post grid section title.
			$wp_customize->add_control( "photographus_panel_{$i}_post_grid_title", [
				'label'           => __( 'Section title', 'photographus' ),
				'section'         => 'photographus_options',
				'type'            => 'text',
				'active_callback' => 'photographus_is_post_grid_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );

			// Create setting for post grid number of posts.
			$wp_customize->add_setting( "photographus_panel_{$i}_post_grid_number", [
				'default'           => 20,
				'sanitize_callback' => 'photographus_sanitize_int_greater_null',
				'transport'         => 'postMessage',
			] );

			// Create control for post grid number of posts.
			$wp_customize->add_control( "photographus_panel_{$i}_post_grid_number", [
				'label'           => __( 'Number of posts (skips posts without post thumbnail)', 'photographus' ),
				'section'         => 'photographus_options',
				'type'            => 'number',
				'active_callback' => 'photographus_is_post_grid_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );

			// Create setting to only show title and header meta of latest posts.
			$wp_customize->add_setting( "photographus_panel_{$i}_post_grid_hide_title", [
				'default'           => false,
				'sanitize_callback' => 'photographus_sanitize_checkbox',
				'transport'         => 'postMessage',
			] );

			// Create control to only show title and header meta of latest posts.
			$wp_customize->add_control( "photographus_panel_{$i}_post_grid_hide_title", [
				'label'           => __( 'Hide post titles.', 'photographus' ),
				'section'         => 'photographus_options',
				'type'            => 'checkbox',
				'active_callback' => 'photographus_is_post_grid_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );

			// Create setting to only show title and header meta of latest posts.
			$wp_customize->add_setting( "photographus_panel_{$i}_post_grid_only_gallery_and_image_posts", [
				'default'           => false,
				'sanitize_callback' => 'photographus_sanitize_checkbox',
				'transport'         => 'postMessage',
			] );

			// Create control to only show title and header meta of latest posts.
			$wp_customize->add_control( "photographus_panel_{$i}_post_grid_only_gallery_and_image_posts", [
				'label'           => __( 'Only display posts with post format »Gallery« or »Image«.', 'photographus' ),
				'section'         => 'photographus_options',
				'type'            => 'checkbox',
				'active_callback' => 'photographus_is_post_grid_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );

			// Create setting for post grid category.
			$wp_customize->add_setting( "photographus_panel_{$i}_post_grid_category", [
				'default'           => 0,
				'sanitize_callback' => 'absint',
				'transport'         => 'postMessage',
			] );

			// Create control for post grid category.
			$wp_customize->add_control( "photographus_panel_{$i}_post_grid_category", [
				'label'           => __( 'Only show posts from one category:', 'photographus' ),
				'section'         => 'photographus_options',
				'type'            => 'select',
				'choices'         => $cats,
				'active_callback' => 'photographus_is_post_grid_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );

			$wp_customize->selective_refresh->add_partial( "photographus_panel_{$i}_post_grid_partial", [
				'selector'            => "#frontpage-section-$i",
				'settings'            => [
					"photographus_panel_{$i}_post_grid_title",
					"photographus_panel_{$i}_post_grid_number",
					"photographus_panel_{$i}_post_grid_hide_title",
					"photographus_panel_{$i}_post_grid_only_gallery_and_image_posts",
					"photographus_panel_{$i}_post_grid_category",
				],
				'render_callback'     => 'photographus_the_post_grid_panel',
				'container_inclusive' => true,
			] );
		} // End if().
	} // End for().

	// Change transport to refresh
	$wp_customize->get_setting( 'custom_logo' )->transport = 'refresh';
}

// Include the file with the callback functions (sanitize callbacks and
// active callbacks).
require_once locate_template( 'inc/customizer/callbacks.php' );


// Include the file with the functions to print CSS and enqueue scripts.
require_once locate_template( 'inc/customizer/scripts-and-styles.php' );

