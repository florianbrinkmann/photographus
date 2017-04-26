<?php
/**
 * Customizer functions
 *
 * @version 1.0.0
 *
 * @package Photographia
 */

/**
 * Customizer settings
 *
 * @param WP_Customize_Manager $wp_customize The Customizer object.
 */
function photographia_customize_register( $wp_customize ) {
	$wp_customize->remove_control( 'header_textcolor' );

	$wp_customize->add_section( 'photographia_options', [
		'title' => __( 'Theme options', 'photographia' ),
	] );

	$wp_customize->add_setting( 'photographia_header_layout', [
		'default'           => false,
		'sanitize_callback' => 'photographia_sanitize_checkbox',
	] );

	$wp_customize->add_control( 'photographia_header_layout', [
		'type'    => 'checkbox',
		'section' => 'photographia_options',
		'label'   => __( 'Alternative header layout', 'photographia' ),
	] );

	/**
	 * Front page panels. Inspired by https://core.trac.wordpress.org/browser/tags/4.7.3/src/wp-content/themes/twentyseventeen/inc/customizer.php#L88
	 */

	/**
	 * Filter number of front page sections in Photographia.
	 *
	 * @param int $num_sections Number of front page sections.
	 */
	$num_sections = apply_filters( 'photographia_front_page_sections', 4 );


	/**
	 * Filter number of posts which are displayed in the post panel dropdown.
	 *
	 * @param int $post_number Number of posts.
	 */
	$post_number = apply_filters( 'photographia_front_page_posts_number', 500 );

	/**
	 * Get the last posts for the dropdown menu for post panels.
	 */
	$post_panel_posts = new WP_Query( [
		'post_type'      => 'post',
		'posts_per_page' => $post_number,
		'no_found_rows'  => true,
	] );

	/**
	 * Build the choices array for the post panel.
	 */
	$post_panel_choices = [];
	if ( $post_panel_posts->have_posts() ) {
		while ( $post_panel_posts->have_posts() ) {
			$post_panel_posts->the_post();

			$post_panel_choices[ get_the_ID() ] = get_the_title();
		}
	}

	/**
	 * Build the choices array for the post grid panel category.
	 *
	 * @link https://blog.josemcastaneda.com/2015/05/13/customizer-dropdown-category-selection/
	 */
	$cats = [];
	foreach ( get_categories() as $categories => $category ) {
		$cats[ $category->term_id ] = $category->name;
	}

	/**
	 * Add a value to the array so the user can choose a neutral value when he does not
	 * want to show a post.
	 */
	$first_select_value = [ 0 => __( '— Select —', 'photographia' ) ];
	$cats               = $first_select_value + $cats;

	/**
	 * Create a setting and control for each of the sections available in the theme.
	 */
	for ( $i = 1; $i < ( 1 + $num_sections ); $i ++ ) {
		/**
		 * Create setting for saving the content type choice.
		 */
		$wp_customize->add_setting( "photographia_panel_{$i}_content_type", [
			'default'           => 0,
			'sanitize_callback' => 'photographia_sanitize_select',
		] );

		/**
		 * Create control for content choice.
		 */
		$wp_customize->add_control( "photographia_panel_{$i}_content_type", [
			/* translators: i = number of panel in customizer */
			'label'   => __( "Panel $i", 'photographia' ),
			'type'    => 'select',
			'section' => 'photographia_options',
			'choices' => [
				0              => __( '— Select —', 'photographia' ),
				'page'         => __( 'Page', 'photographia' ),
				'post'         => __( 'Post', 'photographia' ),
				'latest-posts' => __( 'Latest Posts', 'photographia' ),
				'post-grid'    => __( 'Post Grid', 'photographia' ),
			],
		] );

		/**
		 * Create setting for page.
		 */
		$wp_customize->add_setting( "photographia_panel_{$i}_page", [
			'default'           => false,
			'sanitize_callback' => 'absint',
		] );

		/**
		 * Create control for page.
		 */
		$wp_customize->add_control( "photographia_panel_{$i}_page", [
			'label'           => __( 'Select page', 'photographia' ),
			'section'         => 'photographia_options',
			'type'            => 'dropdown-pages',
			'allow_addition'  => true,
			'active_callback' => 'photographia_is_page_panel',
			'input_attrs'     => [
				'data-panel-number' => $i,
			],
		] );

		/**
		 * Check if we have posts to show, before creating the post controls and settings.
		 */
		if ( ! empty( $post_panel_choices ) ) {
			/**
			 * Add a value to the array so the user can choose a neutral value when he does not
			 * want to show a post.
			 */
			$first_select_value = [ 0 => __( '— Select —', 'photographia' ) ];
			$post_panel_choices = $first_select_value + $post_panel_choices;

			/**
			 * Create setting for post.
			 */
			$wp_customize->add_setting( "photographia_panel_{$i}_post", [
				'default'           => false,
				'sanitize_callback' => 'absint',
			] );

			/**
			 * Create control for post.
			 */
			$wp_customize->add_control( "photographia_panel_{$i}_post", [
				'label'           => __( 'Select post', 'photographia' ),
				'section'         => 'photographia_options',
				'type'            => 'select',
				'choices'         => $post_panel_choices,
				'active_callback' => 'photographia_is_post_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );

			/**
			 * Create setting for latest posts.
			 */
			$wp_customize->add_setting( "photographia_panel_{$i}_latest_posts", [
				'default'           => 5,
				'sanitize_callback' => 'absint',
			] );

			/**
			 * Create control for latest posts.
			 */
			$wp_customize->add_control( "photographia_panel_{$i}_latest_posts", [
				'label'           => __( 'Number of posts', 'photographia' ),
				'section'         => 'photographia_options',
				'type'            => 'text',
				'active_callback' => 'photographia_is_latest_posts_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );

			/**
			 * Create setting to only show title and header meta of latest posts.
			 */
			$wp_customize->add_setting( "photographia_panel_{$i}_latest_posts_short_version", [
				'default'           => false,
				'sanitize_callback' => 'photographia_sanitize_checkbox',
			] );

			/**
			 * Create control to only show title and header meta of latest posts.
			 */
			$wp_customize->add_control( "photographia_panel_{$i}_latest_posts_short_version", [
				'label'           => __( 'Only show title and header meta of posts.', 'photographia' ),
				'section'         => 'photographia_options',
				'type'            => 'checkbox',
				'active_callback' => 'photographia_is_latest_posts_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );

			/**
			 * Create setting for latest posts.
			 */
			$wp_customize->add_setting( "photographia_panel_{$i}_post_grid", [
				'default'           => 20,
				'sanitize_callback' => 'absint',
			] );

			/**
			 * Create control for latest posts.
			 */
			$wp_customize->add_control( "photographia_panel_{$i}_post_grid", [
				'label'           => __( 'Number of posts (skips posts without post thumbnail)', 'photographia' ),
				'section'         => 'photographia_options',
				'type'            => 'text',
				'active_callback' => 'photographia_is_post_grid_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );

			/**
			 * Create setting to only show title and header meta of latest posts.
			 */
			$wp_customize->add_setting( "photographia_panel_{$i}_post_grid_hide_title", [
				'default'           => false,
				'sanitize_callback' => 'photographia_sanitize_checkbox',
			] );

			/**
			 * Create control to only show title and header meta of latest posts.
			 */
			$wp_customize->add_control( "photographia_panel_{$i}_post_grid_hide_title", [
				'label'           => __( 'Hide post titles.', 'photographia' ),
				'section'         => 'photographia_options',
				'type'            => 'checkbox',
				'active_callback' => 'photographia_is_post_grid_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );

			/**
			 * Create setting to only show title and header meta of latest posts.
			 */
			$wp_customize->add_setting( "photographia_panel_{$i}_post_grid_only_gallery_and_image_posts", [
				'default'           => false,
				'sanitize_callback' => 'photographia_sanitize_checkbox',
			] );

			/**
			 * Create control to only show title and header meta of latest posts.
			 */
			$wp_customize->add_control( "photographia_panel_{$i}_post_grid_only_gallery_and_image_posts", [
				'label'           => __( 'Only display posts with post format »Gallery« or »Image«.', 'photographia' ),
				'section'         => 'photographia_options',
				'type'            => 'checkbox',
				'active_callback' => 'photographia_is_post_grid_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );


			/**
			 * Create setting for post grid category.
			 */
			$wp_customize->add_setting( "photographia_panel_{$i}_post_grid_category", [
				'default'           => false,
				'sanitize_callback' => 'absint',
			] );

			/**
			 * Create control for post grid category.
			 */
			$wp_customize->add_control( "photographia_panel_{$i}_post_grid_category", [
				'label'           => __( 'Only show posts from one category:', 'photographia' ),
				'section'         => 'photographia_options',
				'type'            => 'select',
				'choices'         => $cats,
				'active_callback' => 'photographia_is_post_grid_panel',
				'input_attrs'     => [
					'data-panel-number' => $i,
				],
			] );
		} // End if().
	} // End for().

	/**
	 * Change transport to refresh
	 */
	$wp_customize->get_setting( 'custom_logo' )->transport = 'refresh';
}

add_action( 'customize_register', 'photographia_customize_register', 11 );

/**
 * Checkbox sanitization callback example.
 *
 * Sanitization callback for 'checkbox' type controls. This callback sanitizes `$checked`
 * as a boolean value, either TRUE or FALSE.
 *
 * @link https://github.com/WPTRT/code-examples/blob/master/customizer/sanitization-callbacks.php
 *
 * @param bool $checked Whether the checkbox is checked.
 *
 * @return bool Whether the checkbox is checked.
 */
function photographia_sanitize_checkbox( $checked ) {
	/**
	 * Boolean check.
	 */
	return ( ( isset( $checked ) && true === $checked ) ? true : false );
}

/**
 * Select sanitization callback example.
 *
 * - Sanitization: select
 * - Control: select, radio
 *
 * Sanitization callback for 'select' and 'radio' type controls. This callback sanitizes `$input`
 * as a slug, and then validates `$input` against the choices defined for the control.
 *
 * @link https://github.com/WPTRT/code-examples/blob/master/customizer/sanitization-callbacks.php
 *
 * @see  sanitize_key()               https://developer.wordpress.org/reference/functions/sanitize_key/
 * @see  $wp_customize->get_control()
 *       https://developer.wordpress.org/reference/classes/wp_customize_manager/get_control/
 *
 * @param string               $input   Slug to sanitize.
 * @param WP_Customize_Setting $setting Setting instance.
 *
 * @return string Sanitized slug if it is a valid choice; otherwise, the setting default.
 */
function photographia_sanitize_select( $input, $setting ) {
	/**
	 * Ensure input is a slug.
	 */
	$input = sanitize_key( $input );

	/**
	 * Get list of choices from the control associated with the setting.
	 */
	$choices = $setting->manager->get_control( $setting->id )->choices;

	/**
	 * If the input is a valid key, return it; otherwise, return the default.
	 */
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Check if we need to display the dropdown-pages control for a panel.
 *
 * @param WP_Customize_Control $control Control object.
 *
 * @return bool
 */
function photographia_is_page_panel( $control ) {
	/**
	 * Get the panel number from the input_attrs array, defined in the add_control() call.
	 */
	$panel_number = $control->input_attrs['data-panel-number'];

	/**
	 * Get the value of the content type control.
	 */
	$content_type = $control->manager->get_setting( "photographia_panel_{$panel_number}_content_type" )->value();

	/**
	 * Return true if the value is page.
	 */
	if ( 'page' === $content_type ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if we need to display the posts control for a panel.
 *
 * @param WP_Customize_Control $control Control object.
 *
 * @return bool
 */
function photographia_is_post_panel( $control ) {
	/**
	 * Get the panel number from the input_attrs array, defined in the add_control() call.
	 */
	$panel_number = $control->input_attrs['data-panel-number'];

	/**
	 * Get the value of the content type control.
	 */
	$content_type = $control->manager->get_setting( "photographia_panel_{$panel_number}_content_type" )->value();

	/**
	 * Return true if the value is page.
	 */
	if ( 'post' === $content_type ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if we need to display the latest posts control for a panel.
 *
 * @param WP_Customize_Control $control Control object.
 *
 * @return bool
 */
function photographia_is_latest_posts_panel( $control ) {
	/**
	 * Get the panel number from the input_attrs array, defined in the add_control() call.
	 */
	$panel_number = $control->input_attrs['data-panel-number'];

	/**
	 * Get the value of the content type control.
	 */
	$content_type = $control->manager->get_setting( "photographia_panel_{$panel_number}_content_type" )->value();

	/**
	 * Return true if the value is page.
	 */
	if ( 'latest-posts' === $content_type ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Check if we need to display the latest posts control for a panel.
 *
 * @param WP_Customize_Control $control Control object.
 *
 * @return bool
 */
function photographia_is_post_grid_panel( $control ) {
	/**
	 * Get the panel number from the input_attrs array, defined in the add_control() call.
	 */
	$panel_number = $control->input_attrs['data-panel-number'];

	/**
	 * Get the value of the content type control.
	 */
	$content_type = $control->manager->get_setting( "photographia_panel_{$panel_number}_content_type" )->value();

	/**
	 * Return true if the value is page.
	 */
	if ( 'post-grid' === $content_type ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Prints CSS inside header
 */
function photographia_customizer_css() {
	/**
	 * Check if Header text should be displayed. Otherwise hide it.
	 */
	if ( display_header_text() ) {
		return;
	} else { ?>
		<style type="text/css">
			.site-title,
			.site-description {
				clip: rect(1px, 1px, 1px, 1px);
				height: 1px;
				overflow: hidden;
				position: absolute !important;
				width: 1px;
				word-wrap: normal !important;
			}
		</style>
	<?php }
}

add_action( 'wp_head', 'photographia_customizer_css' );
