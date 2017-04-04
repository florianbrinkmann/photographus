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
	 * @param string $heading Type of heading for entry title.
	 * @param bool   $link    If the title should be linked to the single view or not.
	 */
	function photographia_the_entry_header( $heading, $link = true ) {
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
		if ( 'large-featured-image-vertical' === $post_type_template ) {
			printf(
				'%1$s<div><header class="entry-header"><div>%s%s%s</div>%1$s</header></div>',
				$post_thumbnail,
				$title,
				$sticky_label,
				$entry_header_meta
			);
		} else {
			printf(
				'<header class="entry-header"><div>%s%s%s</div>%s</header>',
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
				'large-featured-image-vertical' => 'full',
				'large-featured-image'          => 'full',
				''                              => 'large',
			];

			/**
			 * Get the post type template of the post.
			 * Is an empty string, if no post type template is set.
			 */
			$post_type = photographia_get_post_type_template();

			/**
			 * Get the post thumbnail markup.
			 */
			$post_thumbnail = get_the_post_thumbnail( null, $post_thumbnail_size[ $post_type ] );

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
		if ( is_sticky() ) {
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
		 * Check if we have a category array, otherwise return empty string.
		 */
		if ( is_array( $categories ) ) {
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
			$post_type = str_replace( '.php', '', $template_slug );

			return $post_type;
		} else {
			return '';
		}
	}
}
