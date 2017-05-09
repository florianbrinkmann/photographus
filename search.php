<?php
/**
 * Search template file
 *
 * @version 1.0.0
 *
 * @package Photographia
 */

get_header(); ?>
	<div class="content-wrapper clearfix">
		<div class="main">
			<main>
				<?php
				/**
				 * Check if we have posts.
				 */
				if ( have_posts() ) { ?>
					<div class="archive-header">
						<h1 class="archive-title"><?php printf( __( 'Search Results for: %s', 'schlicht' ), esc_html( get_search_query() ) ); ?></h1>
					</div>
					<?php
					/**
					 * Loop the posts.
					 */
					while ( have_posts() ) {
						/**
						 * Setup the post data.
						 */
						the_post();

						/**
						 * Get the template part file. Default file is partials/post/content.php.
						 * If available, use post format specific files (for example
						 * partials/post/content-gallery.php) for Gallery format.
						 */
						get_template_part( 'partials/post/content', get_post_format() );
					}
				} else { ?>
					<article>
						<div class="entry-header">
							<div>
								<h1 class="entry-title"><?php printf( __( 'Nothing found for: %s', 'schlicht' ), esc_html( get_search_query() ) ); ?></h1>
							</div>
						</div>
						<div class="entry-content">
							<?php get_search_form(); ?>
						</div>
					</article>
					<?php
				}
				photographia_the_posts_pagination(); ?>
			</main>
		</div>
		<?php get_sidebar(); ?>
	</div>
<?php get_footer();
