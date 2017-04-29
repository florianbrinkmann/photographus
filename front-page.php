<?php
/**
 * Template for the front page.
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
				 * Count the front page panels.
				 */
				$panel_count = photographia_front_page_panel_count();

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

						if ( 0 !== $panel_count ) {
							$hide_front_page_content = get_theme_mod( 'photographia_hide_static_front_page_content' );
							if ( true === $hide_front_page_content ) {

							} else {
								/**
								 * Get the template part file partials/front-page/content-post-and-page-panel.php.
								 */
								get_template_part( 'partials/front-page/content', 'post-and-page-panel' );
							}
						} else {
							/**
							 * Get the template part file partials/post/content-single.php (link in page.php).
							 */
							get_template_part( 'partials/post/content', 'single' );
						}
					}
				} else {
					/**
					 * Include partials/post/content-single-none.php if no posts were found.
					 */
					get_template_part( 'partials/post/content-single', 'none' );
				}

				/**
				 * Panels
				 */
				if ( 0 !== $panel_count ) {
					photographia_the_front_page_panels();
				} ?>
			</main>
		</div>
	</div>
<?php get_footer();
