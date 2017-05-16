<?php
/**
 * Caching functions for the front page panels.
 *
 * @version 1.0.0
 *
 * @package Photographus
 */

if ( ! function_exists( 'photographus_refresh_latest_posts_cache' ) ) {
	/**
	 * Forces cache refresh for latest posts panels. If the params are null, a post was updated, so we need to
	 * update the cache for all latest posts panels.
	 *
	 * @param int|null $panel_number    Number of the customizer panel.
	 * @param int|null $number_of_posts Number of posts to display.
	 *
	 * @return string
	 */
	function photographus_refresh_latest_posts_cache( $panel_number = null, $number_of_posts = null ) {
		/**
		 * If $panel_number is null, we need to get the settings from all latest post panels.
		 */
		if ( null === $panel_number ) {
			$num_sections = apply_filters( 'photographus_front_page_sections', 4 );
			for ( $i = 1; $i < ( 1 + $num_sections ); $i ++ ) {
				/**
				 * Get the content type of the current panel.
				 */
				$panel_content_type = get_theme_mod( "photographus_panel_{$i}_content_type" );

				if ( 'latest-posts' === $panel_content_type ) {
					/**
					 * Get the number of posts which should be displayed.
					 */
					$number_of_posts = get_theme_mod( "photographus_panel_{$i}_latest_posts_number", 5 );

					photographus_get_latest_posts( $i, $number_of_posts, true );
				}
			} // End for().
		} else {
			$panel_content_type = get_theme_mod( "photographus_panel_{$panel_number}_content_type" );

			if ( 'latest-posts' === $panel_content_type ) {
				/**
				 * Get the number of posts which should be displayed.
				 */
				$number_of_posts = get_theme_mod( "photographus_panel_{$panel_number}_latest_posts_number", 5 );

				photographus_get_latest_posts( $panel_number, $number_of_posts, true );
			}
		}
	}
} // End if().
add_action( 'wp_update_comment_count', 'photographus_refresh_latest_posts_cache', 10, 3 );

if ( ! function_exists( 'photographus_refresh_post_grid_posts_cache' ) ) {
	/**
	 * Forces cache refresh for post grid posts panels. If the params are null, a post was updated, so we need to
	 * update the cache for all latest posts panels.
	 *
	 * @param int|null $panel_number    Number of the customizer panel.
	 * @param int|null $number_of_posts Number of posts to display.
	 */
	function photographus_refresh_post_grid_posts_cache( $panel_number = null, $number_of_posts = null ) {
		/**
		 * If $panel_number is null, we need to get the settings from all latest post panels.
		 */
		if ( null === $panel_number ) {
			$num_sections = apply_filters( 'photographus_front_page_sections', 4 );
			for ( $i = 1; $i < ( 1 + $num_sections ); $i ++ ) {
				/**
				 * Get the content type of the current panel.
				 */
				$panel_content_type = get_theme_mod( "photographus_panel_{$i}_content_type" );

				if ( 'post-grid' === $panel_content_type ) {
					/**
					 * Get the number of posts which should be displayed.
					 */
					$number_of_posts = get_theme_mod( "photographus_panel_{$i}_post_grid_number", 20 );

					/**
					 * Get value of option only to show image and gallery posts.
					 */
					$only_gallery_and_image_posts = get_theme_mod( "photographus_panel_{$i}_post_grid_only_gallery_and_image_posts", false );

					/**
					 * Get value of option only to show posts from one category.
					 */
					$post_category = get_theme_mod( "photographus_panel_{$i}_post_grid_category", 0 );

					photographus_get_post_grid_posts( $i, $number_of_posts, $only_gallery_and_image_posts, $post_category, true );
				}
			} // End for().
		} else {
			$panel_content_type = get_theme_mod( "photographus_panel_{$panel_number}_content_type" );

			if ( 'post-grid' === $panel_content_type ) {
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

				photographus_get_post_grid_posts( $panel_number, $number_of_posts, $only_gallery_and_image_posts, $post_category, true );
			}
		} // End if().
	}
} // End if().

/**
 * Update the front page panel caches on post publish or update.
 *
 * @param string  $new_status New status of the post.
 * @param string  $old_status Old status of the post.
 * @param WP_Post $post       Post object.
 */
function photographus_cache_update_on_post_update( $new_status, $old_status, $post ) {
	/**
	 * Check if neither the old post status nor the new status is publish. Return if that is true.
	 */
	if ( 'publish' !== $new_status && 'publish' !== $old_status ) {
		return;
	}

	/**
	 * Return if the new and old status are the same but not publish.
	 */
	if ( $new_status === $old_status && 'publish' !== $new_status ) {
		return;
	}
	photographus_refresh_latest_posts_cache();
	photographus_refresh_post_grid_posts_cache();
}

add_action( 'transition_post_status', 'photographus_cache_update_on_post_update', 10, 3 );
