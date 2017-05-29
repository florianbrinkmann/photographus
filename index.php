<?php
/**
 * Main Template file
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
					 * Check if we are on the latest posts page but not on the front page.
					 * This means, a static site was chosen to display the latest posts.
					 */
					if ( is_home() && ! is_front_page() ) { ?>
						<header>
							<h1 class="screen-reader-text"><?php single_post_title(); ?></h1>
						</header>
					<?php }

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
				} // End if().
				photographus_the_posts_pagination(); ?>
			</main>
		</div>
		<?php get_sidebar(); ?>
	</div>
<?php get_footer();
