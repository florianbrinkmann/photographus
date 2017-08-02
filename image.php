<?php
/**
 * Template for image attachment view
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
				if ( have_posts() ) {
					/**
					 * Loop the posts.
					 */
					while ( have_posts() ) {
						/**
						 * Setup post.
						 */
						the_post();

						/**
						 * Get the template part file partials/attachment/content-image.php.
						 */
						get_template_part( 'partials/attachment/content', 'image' );

						/**
						 * Include comments template, if comments are open and/or we have comments.
						 */
						if ( comments_open() || get_comments_number() ) {
							comments_template( '', true );
						}
					}
				} else {
					/**
					 * Include partials/post/content-none.php if no posts were found.
					 */
					get_template_part( 'partials/post/content', 'none' );
				}
				?>
			</main>
		</div>
	</div>
<?php get_footer();
