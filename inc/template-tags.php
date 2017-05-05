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

if ( ! function_exists( 'photographia_the_entry_header' ) ) {
	/**
	 * Displays the entry header
	 *
	 * @param string         $heading            Type of heading for entry title.
	 * @param bool           $link               If the title should be linked to the single view or not.
	 * @param        boolean $latest_posts_panel true if it is a call from the latest posts panel.
	 */
	function photographia_the_entry_header( $heading, $link = true, $latest_posts_panel = false ) {
		/**
		 * Get the post type template.
		 */
		$post_type_template = photographia_get_post_type_template();

		/**
		 * Save the post title.
		 */
		$title = photographia_get_the_title( $heading, $link );

		/**
		 * If post is sticky, a label is saved. Otherwise empty string.
		 */
		$sticky_label = photographia_get_the_sticky_label();

		/**
		 * Save entry header meta. Author and date.
		 */
		$entry_header_meta = photographia_get_the_entry_header_meta();

		/**
		 * Save the post thumbnail markup.
		 */
		$post_thumbnail = photographia_get_the_post_thumbnail();

		/**
		 * We need to display the post thumbnail twice for posts with the
		 * post format large featured image vertical.
		 */
		if ( 'large-portrait-featured-image' === $post_type_template ) {
			/**
			 * We do not need the meta information (author and date) on pages,
			 * so we check if we have a page and do not include the placeholder
			 * %4$s.
			 *
			 * Closing div is inserted in the partials files (for example content.php)
			 */
			if ( is_page() && false === $latest_posts_panel ) {
				$format = '%1$s<div><header class="entry-header"><div>%2$s%3$s</div>%1$s</header>';
			} else {
				$format = '%1$s<div><header class="entry-header"><div>%2$s%3$s%4$s</div>%1$s</header>';
			}

			/**
			 * Use $format and fill the placeholders.
			 */
			printf(
				$format,
				$post_thumbnail,
				$title,
				$sticky_label,
				$entry_header_meta
			);
		} else {
			/**
			 * We do not need the meta information (author and date) on pages,
			 * so we check if we have a page and do not include the placeholder
			 * %3$s.
			 *
			 * Closing div is inserted in the partials files (for example content.php)
			 */
			if ( is_page() && false === $latest_posts_panel ) {
				$format = '<header class="entry-header"><div>%1$s%2$s</div>%4$s</header>';
			} else {
				$format = '<header class="entry-header"><div>%1$s%2$s%3$s</div>%4$s</header>';
			}

			/**
			 * Use $format and fill the placeholders.
			 */
			printf(
				$format,
				$title,
				$sticky_label,
				$entry_header_meta,
				$post_thumbnail
			);
		}
	}
}

if ( ! function_exists( 'photographia_get_the_post_thumbnail' ) ) {
	/**
	 * Displays the post thumbnail
	 *
	 * @return string
	 */
	function photographia_get_the_post_thumbnail() {
		/**
		 * Check if post has a post thumbnail. If not, save empty string.
		 */
		if ( has_post_thumbnail() ) {
			/**
			 * Array to connect post thumbnail sizes with post type templates.
			 * If no post type template, we want the large size.
			 */
			$post_thumbnail_size = [
				'large-portrait-featured-image' => 'full',
				'large-featured-image'          => 'full',
				''                              => 'large',
			];

			/**
			 * Get the post type template of the post.
			 * Is an empty string, if no post type template is set.
			 */
			$post_type_template = photographia_get_post_type_template();

			/**
			 * Get the post thumbnail markup.
			 */
			if ( array_key_exists( $post_type_template, $post_thumbnail_size ) ) {
				$post_thumbnail = get_the_post_thumbnail( null, $post_thumbnail_size[ $post_type_template ] );
			} else {
				$post_thumbnail = get_the_post_thumbnail( null, 'large' );
			}

			/**
			 * Wrap it inside a <figure> element.
			 */
			$post_thumbnail_markup = sprintf( '<figure class="post-thumbnail clearfix">%s</figure>', $post_thumbnail );
		} else {
			$post_thumbnail_markup = '';
		}

		return $post_thumbnail_markup;
	}
}

if ( ! function_exists( 'photographia_get_the_title' ) ) {
	/**
	 * Displays the title of a post wrapped with a heading and optionally with a link to the post.
	 *
	 * @param string $heading Type of heading.
	 * @param bool   $link    If the title should be linked to the single view or not.
	 *
	 * @return string
	 */
	function photographia_get_the_title( $heading, $link = true ) {
		/**
		 * Check if the title should be a link.
		 */
		if ( $link ) {
			/**
			 * Build the title markup.
			 */
			$title_markup = the_title(
				sprintf(
					'<%1$s class="entry-title"><a href="%2$s" rel="bookmark">',
					$heading, esc_url( get_permalink() )
				),
				sprintf( '</a></%s>', $heading ),
				false );
		} else {
			/**
			 * Build the title markup without a link.
			 */
			$title_markup = the_title(
				sprintf(
					'<%1$s class="entry-title">',
					$heading, esc_url( get_permalink() )
				),
				sprintf( '</%s>', $heading ),
				false );
		}

		return $title_markup;
	}
}

if ( ! function_exists( 'photographia_the_sticky_label' ) ) {
	/**
	 * Display a »Featured« box for sticky posts.
	 *
	 * @return string
	 */
	function photographia_get_the_sticky_label() {
		/**
		 * Just display label if we have a sticky post and
		 * are not on the single view of the post.
		 */
		if ( is_sticky() && ! is_single() ) {
			/* translators: String for the label of sticky posts. Displayed above the title */
			$sticky_label_markup = sprintf(
				'<p class="sticky-post-featured-string"><span>%s</span></p>',
				__( 'Featured', 'photographia' )
			);
		} else {
			$sticky_label_markup = '';
		}

		return $sticky_label_markup;
	}
}

if ( ! function_exists( 'photographia_the_entry_header_meta' ) ) {
	/**
	 * Displays author and date of the post.
	 */
	function photographia_get_the_entry_header_meta() {
		$entry_header_meta_markup = sprintf(
			'<p class="entry-meta -header">%s · %s</p>',
			sprintf( /* translators: s = author name */
				__( 'by %s', 'photographia' ), get_the_author()
			),
			photographia_get_the_date()
		);

		return $entry_header_meta_markup;
	}
}

if ( ! function_exists( 'photographia_get_the_date' ) ) {
	/**
	 * Returns get_the_date() with or without a link to the single view.
	 *
	 * @param bool $link If the date should link to the single view.
	 *
	 * @return string
	 */
	function photographia_get_the_date( $link = true ) {
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

if ( ! function_exists( 'photographia_the_content' ) ) {
	/**
	 * Displays the_content() with a more accessible more tag
	 */
	function photographia_the_content() {
		/* translators: visible text for the more tag */
		the_content(
			sprintf(
				'<span aria-hidden="true">%1s</span><span class="screen-reader-text">%2s</span>',
				__( 'Continue reading', 'photographia' ),
				sprintf( /* translators: continue reading text for screen reader users. s=post title */
					__( 'Continue reading %s', 'photographia' ),
					the_title( '', '', false )
				)
			)
		);
	}
}

if ( ! function_exists( 'photographia_the_entry_footer_meta' ) ) {
	/**
	 * Displays the_content() with a more accessible more tag.
	 */
	function photographia_the_entry_footer_meta() {
		/**
		 * Save the category markup. Empty string if post has no categories.
		 */
		$meta_markup = photographia_get_categories_list();

		/**
		 * Get the tag markup. Empty string if post has no tags.
		 */
		$tags = photographia_get_tag_list();
		if ( '' !== $tags ) {
			/**
			 * Add the tag markup to the $meta_markup string.
			 */
			if ( '' !== $meta_markup ) {
				$meta_markup .= " · $tags";
			} else {
				$meta_markup .= "$tags";
			}
		}

		/**
		 * Get comments separated by type.
		 */
		$comments_by_type = photographia_get_comments_by_type();

		/**
		 * Get comments number text.
		 */
		$comments = photographia_get_comments_number_text( $comments_by_type );
		if ( '' !== $comments ) {
			/**
			 * Add the comment number markup to the $meta_markup string.
			 */
			if ( '' !== $meta_markup ) {
				$meta_markup .= " · $comments";
			} else {
				$meta_markup .= "$comments";
			}
		}

		/**
		 * Get trackback number text.
		 */
		$trackbacks = photographia_get_trackback_number_text( $comments_by_type );
		if ( '' !== $trackbacks ) {
			/**
			 * Add the comment number markup to the $meta_markup string.
			 */
			if ( '' !== $meta_markup ) {
				$meta_markup .= " · $trackbacks";
			} else {
				$meta_markup .= "$trackbacks";
			}
		}

		/**
		 * Display the footer meta markup.
		 */
		printf(
			'<p class="entry-meta -footer">%s</p>',
			$meta_markup
		);
	}
}

if ( ! function_exists( 'photographia_get_categories_list' ) ) {
	/**
	 * Returns list of categories for a post.
	 *
	 * @return string
	 */
	function photographia_get_categories_list() {
		/**
		 * Get array of post categories.
		 */
		$categories = get_the_category();

		/**
		 * Check if we have categories in the array. Otherwise return empty string.
		 */
		if ( ! empty( $categories ) ) {
			/**
			 * Build the markup.
			 */
			$categories_markup = sprintf( /* translators: 1=category label; 2=category list */
				__( '%1$s: %2$s', 'photographia' ),

				/**
				 * Display singular or plural label based on the category count.
				 */
				_n(
					'Category',
					'Categories',
					count( $categories ),
					'photographia'
				), /* translators: term delimiter */
				get_the_category_list( __( ', ', 'photographia' ) )
			);

			return $categories_markup;
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'photographia_get_tag_list' ) ) {
	/**
	 * Returns list of tags for a post.
	 *
	 * @return string
	 */
	function photographia_get_tag_list() {
		/**
		 * Get tag array.
		 */
		$tags = get_the_tags();

		/**
		 * Check if we have a tag array, otherwise return empty string.
		 */
		if ( is_array( $tags ) ) {
			/**
			 * Build the markup.
			 */
			$tags_markup = sprintf( /* translators: 1=tag label; 2=tag list */
				__( '%1$s: %2$s', 'photographia' ),

				/**
				 * Display singular or plural label based on tag count.
				 */
				_n(
					'Tag',
					'Tags',
					count( $tags ),
					'photographia'
				), /* translators: term delimiter */
				get_the_tag_list( '', __( ', ', 'photographia' ) )
			);

			return $tags_markup;
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'photographia_get_comments_number_text' ) ) {
	/**
	 * Returns string for the number of comments.
	 *
	 * @param array $comments_by_type array of type separated comments.
	 *
	 * @return string
	 */
	function photographia_get_comments_number_text( $comments_by_type ) {
		/**
		 * Check if we have comments, otherwise return empty string.
		 */
		if ( $comments_by_type['comment'] ) {
			/**
			 * Save the comment count.
			 */
			$comment_number = count( $comments_by_type['comment'] );

			/**
			 * Build the comments number text.
			 */
			$comments_number_text = sprintf( /* translators: s=comment count */
				__( 'Comments: %s', 'photographia' ),
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

if ( ! function_exists( 'photographia_get_trackback_number_text' ) ) {
	/**
	 * Returns string for the number of trackbacks.
	 *
	 * @param array $comments_by_type array of type separated comments.
	 *
	 * @return string
	 */
	function photographia_get_trackback_number_text( $comments_by_type ) {
		/**
		 * Check if we have pings, otherwise return empty string.
		 */
		if ( $comments_by_type['pings'] ) {
			/**
			 * Save the trackback count.
			 */
			$trackback_number = count( $comments_by_type['pings'] );

			/**
			 * Build the trackback number text.
			 */
			$trackback_number_text = sprintf( /* translators: s=trackback count */
				__( 'Trackbacks: %s', 'photographia' ),
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

if ( ! function_exists( 'photographia_get_comments_by_type' ) ) {
	/**
	 * Returns the comments separated by type (comments and pingbacks)
	 *
	 * @return array
	 */
	function photographia_get_comments_by_type() {
		$comment_args     = [
			'order'   => 'ASC',
			'orderby' => 'comment_date_gmt',
			'status'  => 'approve',
			'post_id' => get_the_ID(),
		];
		$comments         = get_comments( $comment_args );
		$comments_by_type = separate_comments( $comments );

		return $comments_by_type;
	}
}

if ( ! function_exists( 'photographia_get_post_type_template' ) ) {
	/**
	 * Returns the post type template slug without templates/ dir and .php ending.
	 *
	 * @return string
	 */
	function photographia_get_post_type_template() {
		$template_slug = get_page_template_slug();
		if ( '' !== $template_slug ) {
			/**
			 * Remove templates/ from slug.
			 */
			$template_slug = str_replace( 'templates/', '', $template_slug );

			/**
			 * Remove .php file ending.
			 */
			$post_type_template = str_replace( '.php', '', $template_slug );

			return $post_type_template;
		} else {
			return '';
		}
	}
}

if ( ! function_exists( 'photographia_comments' ) ) {
	/**
	 * Callback function for displaying the comment list.
	 *
	 * @param object $comment WP_Comment object.
	 * @param array  $args    Array of arguments.
	 * @param int    $depth   Depth of comment.
	 *
	 * @return void
	 */
	function photographia_comments( $comment, $args, $depth ) { ?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
			<div class="comment-meta">
				<?php echo get_avatar( $comment, 44 ); ?>
				<p class="comment-author-date">
					<?php comment_author_link(); ?> ·&nbsp;
					<?php printf(
						'<time datetime="%2$s"><a href="%1$s">%3$s</a></time>',
						get_comment_link( $comment->comment_ID ),
						get_comment_time( 'c' ),
						sprintf( /* translators: 1=date 2=time */
							__( '%1$s @ %2$s', 'photographia' ),
							get_comment_date(),
							get_comment_time()
						)
					);
					edit_comment_link(
						__( 'Edit', 'photographia' ),
						' ·&nbsp;',
						''
					); ?>
				</p>
			</div>
			<div class="comment-content-wrapper">
				<div class="comment-content">
					<?php if ( '0' === $comment->comment_approved ) { ?>
						<p>
							<strong><?php _e( 'Your comment is awaiting moderation.', 'photographia' ); ?></strong>
						</p>
					<?php }
					comment_text(); ?>
				</div>
			</div>

			<div class="reply">
				<?php comment_reply_link( [
					'reply_text' => __( 'Reply', 'photographia' ),
					'depth'      => $depth,
					'max_depth'  => $args['max_depth'],
				] ); ?>
			</div>
		</div>
		<?php
	}
} // End if().

if ( ! function_exists( 'photographia_wp_link_pages' ) ) {
	/**
	 * Displays a pagination for paginated posts and pages
	 */
	function photographia_wp_link_pages() {
		/* translators: Label for pagination of paginated posts and pages */
		wp_link_pages( [
			'before'    => '<ul class="page-numbers"><li><span>' . __( 'Pages:', 'photographia' ) . '</span></li><li>',
			'after'     => '</li></ul>',
			'separator' => '</li><li>',
		] );
	}
}

if ( ! function_exists( 'photographia_the_posts_pagination' ) ) {
	/**
	 * Displays a pagination for archive pages.
	 */
	function photographia_the_posts_pagination() {
		/* translators: Label for pagination of paginated posts and pages */
		the_posts_pagination( [
			'type'      => 'list',
			'prev_text' => __( 'Previous', 'photographia' ),
			'next_text' => __( 'Next', 'photographia' ),
		] );
	}
}

if ( ! function_exists( 'photographia_front_page_panel_count' ) ) {
	/**
	 * Returns number of used front page panels.
	 *
	 * @return int
	 */
	function photographia_front_page_panel_count() {
		$panel_count = 0;

		/**
		 * Filter number of front page sections in Photographia.
		 *
		 * @param int $num_sections Number of front page sections.
		 */
		$num_sections = apply_filters( 'photographia_front_page_sections', 4 );
		for ( $i = 1; $i < ( 1 + $num_sections ); $i ++ ) {
			/**
			 * Get the content type of the current panel.
			 */
			$panel_content_type = get_theme_mod( "photographia_panel_{$i}_content_type" );

			/**
			 * We need to do additional tests for post and page panels, because it is possible
			 * that the content type is selected without selecting a post or page.
			 */
			switch ( $panel_content_type ) {
				case '0':
					break;
				case 'page':
					$panel_page_id = get_theme_mod( "photographia_panel_{$i}_page" );
					if ( 0 !== $panel_page_id ) {
						$panel_count ++;
					}
					break;
				case 'post':
					$panel_post_id = get_theme_mod( "photographia_panel_{$i}_post" );
					if ( 0 !== $panel_post_id ) {
						$panel_count ++;
					}
					break;
				case 'latest-posts':
					$panel_count ++;
					break;
				case 'post-grid':
					$panel_count ++;
					break;
			} // End switch().
		} // End for().

		return $panel_count;
	}
} // End if().

if ( ! function_exists( 'photographia_the_front_page_panels' ) ) {
	/**
	 * Returns number of used front page panels.
	 *
	 * @param WP_Customize_Control|null $partial Custoizer partial.
	 */
	function photographia_the_front_page_panels( $partial = null ) {
		/**
		 * Filter number of front page sections in Photographia.
		 *
		 * @param int $num_sections Number of front page sections.
		 */
		$num_sections = apply_filters( 'photographia_front_page_sections', 4 );
		for ( $i = 1; $i < ( 1 + $num_sections ); $i ++ ) {
			/**
			 * Check if $partial is not null (so we have a customizer selective refresh)
			 * and update the panel number.
			 */
			if ( $partial !== null ) {
				$id = $partial->id;
				$i  = filter_var( $id, FILTER_SANITIZE_NUMBER_INT );
			}

			/**
			 * Get the content type of the current panel.
			 */
			$panel_content_type = get_theme_mod( "photographia_panel_{$i}_content_type" );

			switch ( $panel_content_type ) {
				/**
				 * No content type for panel is chosen.
				 */
				case '0':
					/**
					 * Display a placeholder for the panel (only if in customizer preview).
					 */
					photographia_the_customizer_panel_placeholder( $i );
					break;

				/**
				 * The panel has the content type »page«.
				 */
				case 'page':
					/**
					 * Display the page.
					 */
					photographia_the_page_panel( null, $i );
					break;

				/**
				 * The panel has the content type »post«.
				 */
				case 'post':
					/**
					 * Display the post.
					 */
					photographia_the_post_panel( null, $i );
					break;

				/**
				 * The panel has the content type »lastest posts«.
				 */
				case 'latest-posts':
					/**
					 * Display the latest posts panel.
					 */
					photographia_the_latest_posts_panel( null, $i );
					break;

				/**
				 * The panel has the content type »post grid«.
				 */
				case 'post-grid':
					/**
					 * Display the post grid panel.
					 */
					photographia_the_post_grid_panel( null, $i );
					break;
			} // End switch().

			/**
			 * Break the for loop after first pass if $partial is not null.
			 */
			if ( $partial !== null ) {
				break;
			}
		} // End for().
	}
} // End if().

if ( ! function_exists( 'photographia_customizer_panel_placeholder' ) ) {
	/**
	 * Displays placeholders for empty panels if in customizer preview.
	 *
	 * @param int $panel_number Number of current panels.
	 */
	function photographia_the_customizer_panel_placeholder( $panel_number ) {
		/**
		 * Only display a placeholder if we are in the customizer preview.
		 */
		if ( is_customize_preview() ) {
			echo "<section id='frontpage-section-$panel_number'>Section placeholder</section>";
		}
	}
} // End if().

if ( ! function_exists( 'photographia_get_post_type_template_class' ) ) {
	/**
	 * Returns post type template class string for layout purposes.
	 *
	 * @return string
	 */
	function photographia_get_post_type_template_class() {
		/**
		 * Get the post type template name.
		 * Empty string if no template is used.
		 */
		$post_type_template = photographia_get_post_type_template();

		/**
		 * Add post template class if post has a template
		 */
		if ( '' !== $post_type_template ) {
			$post_type_template_class = "-$post_type_template-template";
		} else {
			$post_type_template_class = '';
		}

		return $post_type_template_class;
	}
} // End if().

if ( ! function_exists( 'photographia_get_latest_posts' ) ) {
	/**
	 * Returns latests posts.
	 *
	 * The routine for updating transients before they expire is from https://wordpress.org/support/topic/lightweight-use-of-transients/
	 *
	 * @param int     $panel_number    Number of panels.
	 * @param int     $number_of_posts Number of posts.
	 * @param boolean $force_refresh   If cache should be refreshed.
	 *
	 * @return object
	 */
	function photographia_get_latest_posts( $panel_number, $number_of_posts, $force_refresh = false ) {
		$timeout_transient = 3 * WEEK_IN_SECONDS;
		/**
		 * Check if we already have a latest posts cache for this panel.
		 */
		$latest_posts = get_transient( "photographia_latest_posts_panel_$panel_number" );
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
			] );

			if ( ! is_wp_error( $latest_posts ) && $latest_posts->have_posts() ) {
				$latest_posts->last_check = time();
				set_transient( "photographia_latest_posts_panel_$panel_number", $latest_posts, 5 * WEEK_IN_SECONDS );
			}
		}

		return $latest_posts;
	}
} // End if().

if ( ! function_exists( 'photographia_get_post_grid_posts' ) ) {
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
	 * @return object
	 */
	function photographia_get_post_grid_posts( $panel_number, $number_of_posts, $only_gallery_and_image_posts = false, $post_category = 0, $force_refresh = false ) {
		$timeout_transient = 3 * WEEK_IN_SECONDS;
		/**
		 * Check if we already have a post grid cache for this panel.
		 */
		$post_grid_posts = get_transient( "photographia_post_grid_panel_$panel_number" );
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
				set_transient( "photographia_post_grid_panel_$panel_number", $post_grid_posts, 5 * WEEK_IN_SECONDS );
			}
		}

		return $post_grid_posts;
	}
} // End if().

if ( ! function_exists( 'photographia_the_latest_posts_panel' ) ) {
	/**
	 * Displays the latest posts panel.
	 *
	 * @param WP_Customize_Partial $partial      Customizer partial.
	 * @param int                  $panel_number Number of posts.
	 */
	function photographia_the_latest_posts_panel( $partial = null, $panel_number = null ) {
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
		$number_of_posts = get_theme_mod( "photographia_panel_{$panel_number}_latest_posts_number", 5 );

		/**
		 * Check if we should only display the title and meta of the posts.
		 */
		$short_version = get_theme_mod( "photographia_panel_{$panel_number}_latest_posts_short_version", false );

		/**
		 * Build query.
		 */
		$latest_posts = photographia_get_latest_posts( $panel_number, $number_of_posts );

		if ( $latest_posts->have_posts() ) { ?>
			<section id="frontpage-section-<?php echo $panel_number; ?>" class="frontpage-section clearfix">
				<?php
				/**
				 * Get the title for the panel.
				 */
				$section_title = get_theme_mod( "photographia_panel_{$panel_number}_latest_posts_title", __( 'Latests posts', 'photographia' ) );

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
					}
				} ?>
			</section>
		<?php } // End if().
	}
} // End if().

if ( ! function_exists( 'photographia_the_post_grid_panel' ) ) {
	/**
	 * Displays the post grid panel.
	 *
	 * @param WP_Customize_Partial $partial      Customizer partial.
	 * @param int                  $panel_number Number of posts.
	 */
	function photographia_the_post_grid_panel( $partial = null, $panel_number = null ) {
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
		$number_of_posts = get_theme_mod( "photographia_panel_{$panel_number}_post_grid_number", 20 );

		/**
		 * Get value of option only to show image and gallery posts.
		 */
		$only_gallery_and_image_posts = get_theme_mod( "photographia_panel_{$panel_number}_post_grid_only_gallery_and_image_posts", false );

		/**
		 * Get value of option only to show posts from one category.
		 */
		$post_category = get_theme_mod( "photographia_panel_{$panel_number}_post_grid_category", 0 );

		$post_grid_posts = photographia_get_post_grid_posts( $panel_number, $number_of_posts, $only_gallery_and_image_posts, $post_category, false );

		/**
		 * Check if we have posts.
		 */
		if ( $post_grid_posts->have_posts() ) { ?>
			<section id="frontpage-section-<?php echo $panel_number; ?>" class="frontpage-section clearfix">
				<?php
				/**
				 * Get the title for the panel.
				 */
				$section_title = get_theme_mod( "photographia_panel_{$panel_number}_post_grid_title", __( 'Post grid', 'photographia' ) );

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
						$hide_gallery_titles = get_theme_mod( "photographia_panel_{$panel_number}_post_grid_hide_title" );

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

if ( ! function_exists( 'photographia_the_post_panel' ) ) {
	/**
	 * Displays the post grid panel.
	 *
	 * @param WP_Customize_Partial $partial      Customizer partial.
	 * @param int                  $panel_number Number of posts.
	 */
	function photographia_the_post_panel( $partial = null, $panel_number = null ) {
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
		$panel_post_id = get_theme_mod( "photographia_panel_{$panel_number}_post" );

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
			photographia_the_customizer_panel_placeholder( $panel_number );
		} // End if().
	}
} // End if().

if ( ! function_exists( 'photographia_the_page_panel' ) ) {
	/**
	 * Displays the post grid panel.
	 *
	 * @param WP_Customize_Partial $partial      Customizer partial.
	 * @param int                  $panel_number Number of posts.
	 */
	function photographia_the_page_panel( $partial = null, $panel_number = null ) {
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
		$panel_post_id = get_theme_mod( "photographia_panel_{$panel_number}_page" );

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
			photographia_the_customizer_panel_placeholder( $panel_number );
		} // End if().
	}
} // End if().

if ( ! function_exists( 'photographia_is_front_page_with_panels' ) ) {
	/**
	 * Checks if we are on the front page with panels.
	 *
	 * @return boolean
	 */
	function photographia_is_front_page_with_panels() {
		/**
		 * Count the front page panels.
		 */
		$panel_count = photographia_front_page_panel_count();
		if ( is_front_page() && is_page() && 0 !== $panel_count ) {
			return true;
		} else {
			return false;
		}
	}
} // End if().

if ( ! function_exists( 'photographia_the_front_page_header_image' ) ) {
	/**
	 * Displays header image as background image inside style tag, if we are on the front page
	 * and have panels.
	 */
	function photographia_the_front_page_header_image() {
		$header_image = get_header_image();
		if ( true === photographia_is_front_page_with_panels() && has_header_image() && false !== $header_image ) {
			echo 'style="background-image: linear-gradient( to right, rgba( 0, 0, 0, .6 ), rgba( 0, 0, 0, .6 ) ), url( ' . $header_image . ' );"';
		} else {
		}
	}
} // End if().

if ( ! function_exists( 'photographia_the_scroll_arrow_icon' ) ) {
	/**
	 * Displays scroll down arrow link if we have a header image and are on the front page with panels.
	 */
	function photographia_the_scroll_arrow_icon() {
		$header_image = get_header_image();
		if ( true === photographia_is_front_page_with_panels() && has_header_image() && false !== $header_image ) { ?>
			<p>
				<a href="#main" class="scroll-link">
					<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
					     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16"
					     enable-background="new 0 0 16 16" xml:space="preserve"><polygon
							points="8,12.7 1.3,6 2.7,4.6 8,9.9 13.3,4.6 14.7,6 "></polygon></svg>
				</a>
			</p>
		<?php } else {
		}
	}
} // End if().

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
