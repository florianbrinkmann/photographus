<?php
/**
 * Template tags, used in template files.
 *
 * @version 1.0.0
 *
 * @package Photographus
 */

if ( ! function_exists( 'photographus_get_custom_logo' ) ) {
	/**
	 * Get the custom logo.
	 *
	 * @return string Custom logo markup or empty string.
	 */
	function photographus_get_custom_logo() {
		/**
		 * Wrap inside function_exists() to preserve back compat with WordPress versions older than 4.5.
		 */
		if ( function_exists( 'get_custom_logo' ) ) {
			/**
			 * Check if we have a custom logo.
			 */
			if ( has_custom_logo() ) {
				/**
				 * Return the custom logo.
				 */
				return get_custom_logo();
			}
		}

		/**
		 * Return empty string, if we do not have a custom logo or WordPress is older than 4.5.
		 */
		return '';
	}
} // End if().

if ( ! function_exists( 'photographus_the_entry_header' ) ) {
	/**
	 * Displays the entry header.
	 *
	 * @param string  $heading            Type of heading for entry title.
	 * @param bool    $link               If the title should be linked to the single view or not.
	 * @param boolean $latest_posts_panel true if it is a call from the latest posts panel.
	 */
	function photographus_the_entry_header( $heading, $link = true, $latest_posts_panel = false ) {
		/**
		 * Get the post type template.
		 */
		$post_type_template = photographus_get_post_type_template();

		/**
		 * Save the post title.
		 */
		$title = photographus_get_the_title( $heading, $link );

		/**
		 * If post is sticky, a label is saved. Otherwise empty string.
		 */
		$sticky_label = photographus_get_the_sticky_label();

		/**
		 * Save entry header meta. Author and date.
		 */
		$entry_header_meta = photographus_get_the_entry_header_meta();

		/**
		 * Save the post thumbnail markup.
		 */
		$post_thumbnail = photographus_get_the_post_thumbnail();

		/**
		 * We need to display the post thumbnail twice for posts with the
		 * post format large featured image vertical.
		 */
		if ( 'large-portrait-featured-image' === $post_type_template || 'large-portrait-featured-image-no-sidebar' === $post_type_template ) {
			/**
			 * We do not need the meta information (author and date) on pages,
			 * so we check if we have a page and do not include the placeholder
			 * %4$s.
			 *
			 * Closing div is inserted in the partials files (for example content.php)
			 */
			if ( is_page() && false === $latest_posts_panel ) {
				$format = '%1$s<div><header class="entry-header -inverted-link-style"><div>%2$s%3$s</div>%1$s</header>';
			} else {
				$format = '%1$s<div><header class="entry-header -inverted-link-style"><div>%2$s%3$s%4$s</div>%1$s</header>';
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
				$format = '<header class="entry-header -inverted-link-style"><div>%1$s%2$s</div>%4$s</header>';
			} else {
				$format = '<header class="entry-header -inverted-link-style"><div>%1$s%2$s%3$s</div>%4$s</header>';
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
} // End if().

if ( ! function_exists( 'photographus_get_the_post_thumbnail' ) ) {
	/**
	 * Displays the post thumbnail.
	 *
	 * @return string Post thumbnail markup.
	 */
	function photographus_get_the_post_thumbnail() {
		/**
		 * Check if post has a post thumbnail. If not, save empty string.
		 */
		if ( has_post_thumbnail() ) {
			/**
			 * Array to connect post thumbnail sizes with post type templates.
			 * If no post type template, we want the large size.
			 */
			$post_thumbnail_size = [
				'large-portrait-featured-image'            => 'full',
				'large-portrait-featured-image-no-sidebar' => 'full',
				'large-featured-image'                     => 'full',
				'large-featured-image-no-sidebar'          => 'full',
				''                                         => 'large',
			];

			/**
			 * Get the post type template of the post.
			 * Is an empty string, if no post type template is set.
			 */
			$post_type_template = photographus_get_post_type_template();

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
} // End if().

if ( ! function_exists( 'photographus_get_the_title' ) ) {
	/**
	 * Displays the title of a post wrapped with a heading and optionally with a link to the post.
	 *
	 * @param string $heading Type of heading.
	 * @param bool   $link    If the title should be linked to the single view or not.
	 *
	 * @return string Title markup.
	 */
	function photographus_get_the_title( $heading, $link = true ) {
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
} // End if().

if ( ! function_exists( 'photographus_the_entry_header_meta' ) ) {
	/**
	 * Displays author and date of the post.
	 *
	 * @return string Entry header markup.
	 */
	function photographus_get_the_entry_header_meta() {
		$entry_header_meta_markup = sprintf(
			'<p class="entry-meta -header">%s · %s</p>',
			sprintf( /* translators: s = author name */
				__( 'by %s', 'photographus' ), get_the_author()
			),
			photographus_get_the_date()
		);

		return $entry_header_meta_markup;
	}
} // End if().

if ( ! function_exists( 'photographus_the_content' ) ) {
	/**
	 * Displays the_content() with a more accessible more tag.
	 */
	function photographus_the_content() {
		/* translators: visible text for the more tag */
		the_content(
			sprintf(
				'<span aria-hidden="true">%1s</span><span class="screen-reader-text">%2s</span>',
				__( 'Continue reading', 'photographus' ),
				sprintf( /* translators: continue reading text for screen reader users. s=post title */
					__( 'Continue reading %s', 'photographus' ),
					the_title( '', '', false )
				)
			)
		);
	}
} // End if().

if ( ! function_exists( 'photographus_the_entry_footer_meta' ) ) {
	/**
	 * Displays the_content() with a more accessible more tag.
	 */
	function photographus_the_entry_footer_meta() {
		/**
		 * Save the category markup. Empty string if post has no categories.
		 */
		$meta_markup = photographus_get_categories_list();

		/**
		 * Get the tag markup. Empty string if post has no tags.
		 */
		$tags = photographus_get_tag_list();
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
		$comments_by_type = photographus_get_comments_by_type();

		/**
		 * Get comments number text.
		 */
		$comments = photographus_get_comments_number_text( $comments_by_type );
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
		$trackbacks = photographus_get_trackback_number_text( $comments_by_type );
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
} // End if().

if ( ! function_exists( 'photographus_get_comments_by_type' ) ) {
	/**
	 * Returns the comments separated by type (comments and pingbacks).
	 *
	 * @return array Post reactions reparated by type.
	 */
	function photographus_get_comments_by_type() {
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

if ( ! function_exists( 'photographus_get_post_type_template' ) ) {
	/**
	 * Returns the post type template slug without templates/ dir and .php ending.
	 *
	 * @return string Post type template without file ending and templates/ dir.
	 */
	function photographus_get_post_type_template() {
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

if ( ! function_exists( 'photographus_comments' ) ) {
	/**
	 * Callback function for displaying the comment list.
	 *
	 * @param object $comment WP_Comment object.
	 * @param array  $args    Array of arguments.
	 * @param int    $depth   Depth of comment.
	 */
	function photographus_comments( $comment, $args, $depth ) { ?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
			<div class="comment-meta">
				<?php echo get_avatar( $comment, 44 ); ?>
				<p class="comment-author-date">
					<?php
					/**
					 * Display the name of the comment author. Linked to the site he submitted in the Website field.
					 */
					comment_author_link(); ?> ·&nbsp;
					<?php
					/**
					 * Display the comment date, linked to the comment’s permalink.
					 */
					printf(
						'<time datetime="%2$s"><a href="%1$s">%3$s</a></time>',
						get_comment_link( $comment->comment_ID ),
						get_comment_time( 'c' ),
						sprintf( /* translators: 1=date 2=time */
							__( '%1$s @ %2$s', 'photographus' ),
							get_comment_date(),
							get_comment_time()
						)
					);

					/**
					 * Display the edit link (only visible for users with the right capabilities).
					 */
					edit_comment_link(
						__( 'Edit', 'photographus' ),
						' ·&nbsp;',
						''
					); ?>
				</p>
			</div>
			<div class="comment-content-wrapper">
				<div class="comment-content">
					<?php
					/**
					 * Check if the comment is not approved yet.
					 */
					if ( '0' === $comment->comment_approved ) { ?>
						<p>
							<strong><?php _e( 'Your comment is awaiting moderation.', 'photographus' ); ?></strong>
						</p>
					<?php }

					/**
					 * Display the comment text.
					 */
					comment_text(); ?>
				</div>
			</div>

			<div class="reply">
				<?php
				/**
				 * Display the reply link.
				 */
				comment_reply_link( [
					'reply_text' => __( 'Reply', 'photographus' ),
					'depth'      => $depth,
					'max_depth'  => $args['max_depth'],
				] ); ?>
			</div>
		</div>
		<?php
	}
} // End if().

if ( ! function_exists( 'photographus_wp_link_pages' ) ) {
	/**
	 * Displays a pagination for paginated posts and pages.
	 */
	function photographus_wp_link_pages() {
		/* translators: Label for pagination of paginated posts and pages */
		wp_link_pages( [
			'before'    => '<ul class="page-numbers"><li><span>' . __( 'Pages:', 'photographus' ) . '</span></li><li>',
			'after'     => '</li></ul>',
			'separator' => '</li><li>',
		] );
	}
}

if ( ! function_exists( 'photographus_the_posts_pagination' ) ) {
	/**
	 * Displays a pagination for archive pages.
	 */
	function photographus_the_posts_pagination() {
		/* translators: Label for pagination of paginated posts and pages */
		the_posts_pagination( [
			'type'      => 'list',
			'prev_text' => __( 'Previous', 'photographus' ),
			'next_text' => __( 'Next', 'photographus' ),
		] );
	}
}

if ( ! function_exists( 'photographus_front_page_panel_count' ) ) {
	/**
	 * Returns number of used front page panels.
	 *
	 * @return int Front page panel count.
	 */
	function photographus_front_page_panel_count() {
		$panel_count = 0;

		/**
		 * Filter number of front page sections in Photographus.
		 *
		 * @param int $num_sections Number of front page sections.
		 */
		$num_sections = apply_filters( 'photographus_front_page_sections', 4 );
		for ( $i = 1; $i < ( 1 + $num_sections ); $i ++ ) {
			/**
			 * Get the content type of the current panel.
			 */
			$panel_content_type = get_theme_mod( "photographus_panel_{$i}_content_type" );

			/**
			 * We need to do additional tests for post and page panels, because it is possible
			 * that the content type is selected without selecting a post or page.
			 */
			switch ( $panel_content_type ) {
				case '0':
					break;
				case 'page':
					$panel_page_id = get_theme_mod( "photographus_panel_{$i}_page" );
					if ( 0 !== $panel_page_id ) {
						$panel_count ++;
					}
					break;
				case 'post':
					$panel_post_id = get_theme_mod( "photographus_panel_{$i}_post" );
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

if ( ! function_exists( 'photographus_the_front_page_panels' ) ) {
	/**
	 * Returns number of used front page panels.
	 *
	 * @param WP_Customize_Control|null $partial Custoizer partial.
	 */
	function photographus_the_front_page_panels( $partial = null ) {
		/**
		 * Filter number of front page sections in Photographus.
		 *
		 * @param int $num_sections Number of front page sections.
		 */
		$num_sections = apply_filters( 'photographus_front_page_sections', 4 );
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
			$panel_content_type = get_theme_mod( "photographus_panel_{$i}_content_type" );

			switch ( $panel_content_type ) {
				/**
				 * No content type for panel is chosen.
				 */
				case '0':
					/**
					 * Display a placeholder for the panel (only if in customizer preview).
					 */
					photographus_the_customizer_panel_placeholder( $i );
					break;

				/**
				 * The panel has the content type »page«.
				 */
				case 'page':
					/**
					 * Display the page.
					 */
					photographus_the_page_panel( null, $i );
					break;

				/**
				 * The panel has the content type »post«.
				 */
				case 'post':
					/**
					 * Display the post.
					 */
					photographus_the_post_panel( null, $i );
					break;

				/**
				 * The panel has the content type »lastest posts«.
				 */
				case 'latest-posts':
					/**
					 * Display the latest posts panel.
					 */
					photographus_the_latest_posts_panel( null, $i );
					break;

				/**
				 * The panel has the content type »post grid«.
				 */
				case 'post-grid':
					/**
					 * Display the post grid panel.
					 */
					photographus_the_post_grid_panel( null, $i );
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

if ( ! function_exists( 'photographus_get_post_type_template_class' ) ) {
	/**
	 * Returns post type template class string for layout purposes.
	 *
	 * @return string Post type template class.
	 */
	function photographus_get_post_type_template_class() {
		/**
		 * Get the post type template name.
		 * Empty string if no template is used.
		 */
		$post_type_template = photographus_get_post_type_template();

		/**
		 * Check if this is a page template which should hide the sidebar
		 * (contains »-no-sidebar«).
		 * Returns false if no-sidebar cannot be found.
		 */
		$no_sidebar_pos = strpos( $post_type_template, '-no-sidebar' );

		/**
		 * Remove »-no-sidebar« part so the CSS styles will match
		 */
		if ( $no_sidebar_pos !== false ) {
			$post_type_template = str_replace( '-no-sidebar', '', $post_type_template );
		}

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

if ( ! function_exists( 'photographus_the_front_page_header_image' ) ) {
	/**
	 * Displays header image as background image inside style tag, if we are on the front page
	 * and have panels.
	 */
	function photographus_the_front_page_header_image() {
		$header_image = get_header_image();
		if ( true === photographus_is_front_page_with_panels() && has_header_image() && false !== $header_image ) {
			echo 'style="background-image: linear-gradient( to right, rgba( 0, 0, 0, .6 ), rgba( 0, 0, 0, .6 ) ), url( ' . $header_image . ' );"';
		}
	}
} // End if().

if ( ! function_exists( 'photographus_the_scroll_arrow_icon' ) ) {
	/**
	 * Displays scroll down arrow link if we have a header image and are on the front page with panels.
	 */
	function photographus_the_scroll_arrow_icon() {
		$header_image = get_header_image();
		if ( true === photographus_is_front_page_with_panels() && has_header_image() && false !== $header_image ) { ?>
			<p>
				<a href="#main" class="scroll-link">
					<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
					     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 16"
					     enable-background="new 0 0 16 16" xml:space="preserve"><polygon
							points="8,12.7 1.3,6 2.7,4.6 8,9.9 13.3,4.6 14.7,6 "></polygon></svg>
				</a>
			</p>
		<?php }
	}
} // End if().
