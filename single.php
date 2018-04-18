<?php
/**
 * Default single view of posts.
 *
 * @version 1.0.1
 *
 * @package Photographus
 */

get_header(); ?>
	<div class="content-wrapper clearfix">
		<div class="main">
			<main>
				<?php
				// Check if we have posts.
				if ( have_posts() ) {
					// Loop the posts.
					while ( have_posts() ) {
						// Setup post.
						the_post();

						// Get the template part file. Default file is partials/post/content-single.php.
						// If available, use post format specific files (for example
						// partials/post/content-single-gallery.php) for Gallery format.
						get_template_part( 'partials/post/content-single', get_post_format() );

						// Include comments template, if comments are open and/or we have comments.
						if ( comments_open() || get_comments_number() ) {
							comments_template();
						}
					}
				} else {
					// Include partials/post/content-none.php if no posts were found.
					get_template_part( 'partials/post/content', 'none' );
				}
				?>
			</main>
		</div>
		<?php get_sidebar(); ?>
	</div>
<?php get_footer();
