<?php
/**
 * Functions which are not called by template files and have to do with
 * the front page panels feature.
 *
 * @version 1.0.0
 *
 * @package Photographus
 */

if ( ! function_exists( 'photographus_is_front_page_with_panels' ) ) {
	/**
	 * Checks if we are on the front page with panels.
	 *
	 * @return boolean true if on front page with panels, false otherwise.
	 */
	function photographus_is_front_page_with_panels() {
		/**
		 * Count the front page panels.
		 */
		$panel_count = photographus_front_page_panel_count();
		if ( is_front_page() && is_page() && 0 !== $panel_count ) {
			return true;
		} else {
			return false;
		}
	}
} // End if().

if ( ! function_exists( 'photographus_get_latest_posts' ) ) {
	/**
	 * Returns latest posts.
	 *
	 * The routine for updating transients before they expire is from https://wordpress.org/support/topic/lightweight-use-of-transients/
	 *
	 * @param int     $panel_number    Number of panels.
	 * @param int     $number_of_posts Number of posts.
	 * @param boolean $force_refresh   If cache should be refreshed.
	 *
	 * @return object WP_Query object of latest posts.
	 */
	function photographus_get_latest_posts( $panel_number, $number_of_posts, $force_refresh = false ) {
		$timeout_transient = 3 * WEEK_IN_SECONDS;
		/**
		 * Check if we already have a latest posts cache for this panel.
		 */
		$latest_posts = get_transient( "photographus_latest_posts_panel_$panel_number" );
		if ( ! is_object( $latest_posts ) ) {
			$latest_posts = new stdClass();
		}
		if ( true === $force_refresh || ! isset( $latest_posts->last_check ) || $timeout_transient < ( time() - $latest_posts->last_check ) || is_customize_preview() ) {
			/**
			 * Get the latest posts.
			 */
			$latest_posts = new WP_Query( [
				'post_type'           => 'post',
				'posts_per_page'      => $number_of_posts,
				'no_found_rows'       => true,
				'ignore_sticky_posts' => 1,
				'post_status'         => 'publish',
			] );

			if ( ! is_wp_error( $latest_posts ) && $latest_posts->have_posts() ) {
				$latest_posts->last_check = time();
				set_transient( "photographus_latest_posts_panel_$panel_number", $latest_posts, 5 * WEEK_IN_SECONDS );
			}
		} // End if().

		return $latest_posts;
	}
} // End if().

if ( ! function_exists( 'photographus_get_post_grid_posts' ) ) {
	/**
	 * Returns latest posts for post grid.
	 *
	 * The routine for updating transients before they expire is from https://wordpress.org/support/topic/lightweight-use-of-transients/
	 *
	 * @param int     $panel_number                 Number of panels.
	 * @param int     $number_of_posts              Number of posts.
	 * @param boolean $only_gallery_and_image_posts If only posts with gallery and image post type should
	 *                                              be displayed.
	 * @param int     $post_category                Number of posts.
	 * @param boolean $force_refresh                If cache should be refreshed.
	 *
	 * @return object WP_Query object of post grid posts.
	 */
	function photographus_get_post_grid_posts( $panel_number, $number_of_posts, $only_gallery_and_image_posts = false, $post_category = 0, $force_refresh = false ) {
		$timeout_transient = 3 * WEEK_IN_SECONDS;
		/**
		 * Check if we already have a post grid cache for this panel.
		 */
		$post_grid_posts = get_transient( "photographus_post_grid_panel_$panel_number" );
		if ( ! is_object( $post_grid_posts ) ) {
			$post_grid_posts = new stdClass();
		}
		if ( true === $force_refresh || ! isset( $post_grid_posts->last_check ) || $timeout_transient < ( time() - $post_grid_posts->last_check ) || is_customize_preview() ) {
			/**
			 * Build $tax_query array
			 */
			$tax_query = [ 'relation' => 'AND' ];

			if ( true === $only_gallery_and_image_posts ) {
				$tax_query[] = [
					'taxonomy' => 'post_format',
					'field'    => 'slug',
					'terms'    => [
						'post-format-gallery',
						'post-format-image'
					],
				];
			}

			if ( 0 !== $post_category ) {
				$tax_query[] = [
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => [
						$post_category
					],
				];
			}

			/**
			 * Build query.
			 */
			$post_grid_posts = new WP_Query( [
				'post_type'           => 'post',
				'posts_per_page'      => $number_of_posts,
				'no_found_rows'       => true,
				'ignore_sticky_posts' => 1,
				'post_status'         => 'publish',
				'meta_query'          => [
					'relation' => 'AND',
					[
						'key' => '_thumbnail_id',
					],
				],
				'tax_query'           => $tax_query,
			] );

			if ( ! is_wp_error( $post_grid_posts ) && $post_grid_posts->have_posts() ) {
				$post_grid_posts->last_check = time();
				set_transient( "photographus_post_grid_panel_$panel_number", $post_grid_posts, 5 * WEEK_IN_SECONDS );
			}
		} // End if().

		return $post_grid_posts;
	}
} // End if().

if ( ! function_exists( 'photographus_the_latest_posts_panel' ) ) {
	/**
	 * Displays the latest posts panel.
	 *
	 * @param WP_Customize_Partial $partial      Customizer partial.
	 * @param int                  $panel_number Number of posts.
	 */
	function photographus_the_latest_posts_panel( $partial = null, $panel_number = null ) {
		/**
		 * Get panel number if $panel_number is not a number
		 */
		if ( ! is_numeric( $panel_number ) ) {
			$id           = $partial->id;
			$panel_number = filter_var( $id, FILTER_SANITIZE_NUMBER_INT );
		}

		/**
		 * Get the number of posts which should be displayed.
		 */
		$number_of_posts = get_theme_mod( "photographus_panel_{$panel_number}_latest_posts_number", 5 );

		/**
		 * Check if we should only display the title and meta of the posts.
		 */
		$short_version = get_theme_mod( "photographus_panel_{$panel_number}_latest_posts_short_version", false );

		/**
		 * Build query.
		 */
		$latest_posts = photographus_get_latest_posts( $panel_number, $number_of_posts );

		if ( $latest_posts->have_posts() ) { ?>
			<section id="frontpage-section-<?php echo $panel_number; ?>"
			         class="frontpage-section latest-blog-posts-section clearfix">
				<?php
				/**
				 * Get the title for the panel.
				 */
				$section_title = get_theme_mod( "photographus_panel_{$panel_number}_latest_posts_title", __( 'Latest Posts', 'photographus' ) );

				/**
				 * Check if we have a title.
				 */
				if ( '' !== $section_title ) {
					/**
					 * If we have a title, build the title markup and set the heading element for the
					 * latest posts to h3.
					 */
					$section_title   = "<h2 class='frontpage-section-title'>$section_title</h2>";
					$heading_element = 'h3';
				} else {
					/**
					 * If we have no panel title, set the heading element for the titles of the latest
					 * posts to h2.
					 */
					$heading_element = 'h2';
				}
				echo $section_title;

				/**
				 * Loop through the latest posts.
				 */
				while ( $latest_posts->have_posts() ) {
					$latest_posts->the_post();

					/**
					 * Check if we only need to display the short version.
					 */
					if ( $short_version ) {
						/**
						 * Get the template part file partials/front-page/content-latest-posts-panel-short-version.php.
						 * Here we use include(locate_template()) to have access to the $latest_posts object
						 * in the partial.
						 *
						 * @link: http://keithdevon.com/passing-variables-to-get_template_part-in-wordpress/
						 */
						include( locate_template( 'partials/front-page/content-latest-posts-panel-short-version.php' ) );
					} else {
						/**
						 * Get the template part file partials/front-page/content-latest-posts-panel.php.
						 * Here we use include(locate_template()) to have access to the $latest_posts object
						 * in the partial.
						 *
						 * @link: http://keithdevon.com/passing-variables-to-get_template_part-in-wordpress/
						 */
						include( locate_template( 'partials/front-page/content-latest-posts-panel.php' ) );
					} // End if().
				} // End while().

				/**
				 * Get the URL of the blog page.
				 */
				$blog_page_url = get_permalink( get_option( 'page_for_posts' ) );

				/**
				 * Check if we have a URL.
				 */
				if ( $blog_page_url ) { ?>
					<p>
						<a href="<?php echo $blog_page_url; ?>"
						   class="cta"><?php _e( 'Go to blog', 'photographus' ); ?></a>
					</p>
				<?php } ?>
			</section>
		<?php } // End if().
	}
} // End if().

if ( ! function_exists( 'photographus_the_post_grid_panel' ) ) {
	/**
	 * Displays the post grid panel.
	 *
	 * @param WP_Customize_Partial $partial      Customizer partial.
	 * @param int                  $panel_number Number of posts.
	 */
	function photographus_the_post_grid_panel( $partial = null, $panel_number = null ) {
		/**
		 * Get panel number if $panel_number is not a number
		 */
		if ( ! is_numeric( $panel_number ) ) {
			$id           = $partial->id;
			$panel_number = filter_var( $id, FILTER_SANITIZE_NUMBER_INT );
		}

		/**
		 * Get the number of posts which should be displayed.
		 */
		$number_of_posts = get_theme_mod( "photographus_panel_{$panel_number}_post_grid_number", 20 );

		/**
		 * Get value of option only to show image and gallery posts.
		 */
		$only_gallery_and_image_posts = get_theme_mod( "photographus_panel_{$panel_number}_post_grid_only_gallery_and_image_posts", false );

		/**
		 * Get value of option only to show posts from one category.
		 */
		$post_category = get_theme_mod( "photographus_panel_{$panel_number}_post_grid_category", 0 );

		$post_grid_posts = photographus_get_post_grid_posts( $panel_number, $number_of_posts, $only_gallery_and_image_posts, $post_category, false );

		/**
		 * Check if we have posts.
		 */
		if ( $post_grid_posts->have_posts() ) { ?>
			<section id="frontpage-section-<?php echo $panel_number; ?>" class="frontpage-section clearfix">
				<?php
				/**
				 * Get the title for the panel.
				 */
				$section_title = get_theme_mod( "photographus_panel_{$panel_number}_post_grid_title", __( 'Post Grid', 'photographus' ) );

				/**
				 * Check if we have a title.
				 */
				if ( '' !== $section_title ) {
					/**
					 * Build the title markup and set h3 for the titles of the posts in the grid.
					 */
					$section_title   = "<h2 class='frontpage-section-title'>$section_title</h2>";
					$heading_element = 'h3';
				} else {
					/**
					 * We have no panel title, so we set the titles of the post grid posts to h2.
					 */
					$heading_element = 'h2';
				}
				echo $section_title; ?>
				<div class="gallery-grid-wrapper clearfix">
					<div class="gallery-grid">
						<?php
						/**
						 * Get the value of the option to hide the post titles in the grid.
						 */
						$hide_gallery_titles = get_theme_mod( "photographus_panel_{$panel_number}_post_grid_hide_title" );

						/**
						 * Build a small classes string, depending on if we should hide the titles
						 * (with screen-reader-class) or not (without screen-reader-class).
						 */
						if ( true === $hide_gallery_titles ) {
							$entry_title_div_class_string = 'entry-title screen-reader-text';
						} else {
							$entry_title_div_class_string = 'entry-title';
						}

						/**
						 * Loop through the post grid posts.
						 */
						while ( $post_grid_posts->have_posts() ) {
							$post_grid_posts->the_post();

							/**
							 * Get the template part file partials/front-page/content-post-grid-panel.php.
							 * Here we use include(locate_template()) to have access to the $post_grid_posts object
							 * in the partial.
							 *
							 * @link: http://keithdevon.com/passing-variables-to-get_template_part-in-wordpress/
							 */
							include( locate_template( 'partials/front-page/content-post-grid-panel.php' ) );
						} ?>
					</div>
				</div>
			</section>
		<?php } // End if().
	}
} // End if().

if ( ! function_exists( 'photographus_the_post_panel' ) ) {
	/**
	 * Displays the post grid panel.
	 *
	 * @param WP_Customize_Partial $partial      Customizer partial.
	 * @param int                  $panel_number Number of posts.
	 */
	function photographus_the_post_panel( $partial = null, $panel_number = null ) {
		/**
		 * Get panel number if $panel_number is not a number
		 */
		if ( ! is_numeric( $panel_number ) ) {
			$id           = $partial->id;
			$panel_number = filter_var( $id, FILTER_SANITIZE_NUMBER_INT );
		}

		/**
		 * Get the ID of the post.
		 */
		$panel_post_id = get_theme_mod( "photographus_panel_{$panel_number}_post" );

		/**
		 * Check if the ID is not 0, which means we have a post to show.
		 */
		if ( 0 !== $panel_post_id ) {
			global $post;
			$post = get_post( $panel_post_id );
			setup_postdata( $post );

			/**
			 * Get the template part file partials/front-page/content-post-and-page-panel.php.
			 * Here we use include(locate_template()) to have access to the $i variable
			 * in the partial.
			 *
			 * @link: http://keithdevon.com/passing-variables-to-get_template_part-in-wordpress/
			 */
			include( locate_template( 'partials/front-page/content-post-and-page-panel.php' ) );

			wp_reset_postdata();
		} else {
			/**
			 * Display a placeholder for the panel (only if in customizer preview).
			 */
			photographus_the_customizer_panel_placeholder( $panel_number );
		} // End if().
	}
} // End if().

if ( ! function_exists( 'photographus_the_page_panel' ) ) {
	/**
	 * Displays the post grid panel.
	 *
	 * @param WP_Customize_Partial $partial      Customizer partial.
	 * @param int                  $panel_number Number of posts.
	 */
	function photographus_the_page_panel( $partial = null, $panel_number = null ) {
		/**
		 * Get panel number if $panel_number is not a number
		 */
		if ( ! is_numeric( $panel_number ) ) {
			$id           = $partial->id;
			$panel_number = filter_var( $id, FILTER_SANITIZE_NUMBER_INT );
		}

		/**
		 * Get the ID of the post.
		 */
		$panel_post_id = get_theme_mod( "photographus_panel_{$panel_number}_page" );

		/**
		 * Check if the ID is not 0, which means we have a post to show.
		 */
		if ( 0 !== $panel_post_id ) {
			global $post;
			$post = get_post( $panel_post_id );
			setup_postdata( $post );

			/**
			 * Get the template part file partials/front-page/content-post-and-page-panel.php.
			 * Here we use include(locate_template()) to have access to the $i variable
			 * in the partial.
			 *
			 * @link: http://keithdevon.com/passing-variables-to-get_template_part-in-wordpress/
			 */
			include( locate_template( 'partials/front-page/content-post-and-page-panel.php' ) );

			wp_reset_postdata();
		} else {
			/**
			 * Display a placeholder for the panel (only if in customizer preview).
			 */
			photographus_the_customizer_panel_placeholder( $panel_number );
		} // End if().
	}
} // End if().

if ( ! function_exists( 'photographus_customizer_panel_placeholder' ) ) {
	/**
	 * Displays placeholders for empty panels if in customizer preview.
	 *
	 * @param int $panel_number Number of current panels.
	 */
	function photographus_the_customizer_panel_placeholder( $panel_number ) {
		/**
		 * Only display a placeholder if we are in the customizer preview.
		 */
		if ( is_customize_preview() ) {
			echo "<section id='frontpage-section-$panel_number' class='frontpage-section frontpage-section-placeholder'><h2 class='frontpage-section-title'>Section placeholder</h2></section>";
		}
	}
} // End if().
