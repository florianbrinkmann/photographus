<?php
/**
 * Template for the front page.
 *
 * @version 1.0.0
 *
 * @package Photographus
 */

get_header(); ?>
	<div class="content-wrapper clearfix">
		<div class="main" id="main">
			<main>
				<?php
				/**
				 * Count the front page panels.
				 */
				$panel_number = photographus_front_page_panel_count();

				/**
				 * Check if we have a front page.
				 */
				if ( have_posts() ) {
					/**
					 * Loop the content.
					 */
					while ( have_posts() ) {
						/**
						 * Setup the post data.
						 */
						the_post();

						if ( 0 !== $panel_number ) {
							$hide_front_page_content = get_theme_mod( 'photographus_hide_static_front_page_content' );
							if ( true === $hide_front_page_content ) {

							} else {
								/**
								 * Get the template part file partials/front-page/content-post-and-page-panel.php.
								 * Here we use include(locate_template()) to have access to the $panel_number var
								 * in the partial.
								 *
								 * @link: http://keithdevon.com/passing-variables-to-get_template_part-in-wordpress/
								 */
								include( locate_template( 'partials/front-page/content-post-and-page-panel.php' ) );
							}
						} else {
							if ( is_page() ) {
								/**
								 * Get the template part file partials/post/content-single.php (link in page.php).
								 */
								get_template_part( 'partials/post/content', 'single' );
							} else {
								/**
								 * Get the template part file partials/post/content-single.php (link in page.php).
								 */
								get_template_part( 'partials/post/content', get_post_format() );
							}
						}
					}
				} else {
					/**
					 * Include partials/post/content-none.php if no posts were found.
					 */
					get_template_part( 'partials/post/content', 'none' );
				} // End if().

				/**
				 * Panels
				 */
				if ( 0 !== $panel_number ) {
					photographus_the_front_page_panels();
				}
				photographus_the_posts_pagination(); ?>
			</main>
		</div>
		<?php if ( 0 === $panel_number ) {
			get_sidebar();
		} ?>
	</div>
<?php get_footer();
