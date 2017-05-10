<?php
/**
 * Archive template file
 *
 * @version 1.0.0
 *
 * @package Photographus
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
						<h1 class="archive-title"><?php the_archive_title(); ?></h1>
						<?php the_archive_description(); ?>
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
				} else {
					/**
					 * Include partials/post/content-none.php if no posts were found.
					 */
					get_template_part( 'partials/post/content', 'none' );
				}
				photographus_the_posts_pagination(); ?>
			</main>
		</div>
		<?php get_sidebar(); ?>
	</div>
<?php get_footer();
