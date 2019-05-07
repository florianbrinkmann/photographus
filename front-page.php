<?php
/**
 * Template for the front page.
 *
 * @version 1.1.0
 *
 * @package photographus
 */

get_header(); ?>
	<div class="content-wrapper clearfix">
		<div class="main" id="main">
			<main>
				<?php
				// Count the front page panels.
				$photographus_panel_number = photographus_front_page_panel_count();

				// Check if we have a front page.
				if ( have_posts() ) {
					// Loop the content.
					while ( have_posts() ) {
						// Setup post.
						the_post();

						// Check if we have panels and the front page is set to a static page.
						if ( 0 !== $photographus_panel_number && 'page' === get_option( 'show_on_front' ) ) {
							$photographus_hide_front_page_content = get_theme_mod( 'photographus_hide_static_front_page_content' );
							if ( true !== $photographus_hide_front_page_content ) {
								// Temporarily set the $photographus_panel_number to 0 so the section of the home page does
								// not get the same ID like the first front page panel.
								$photographus_panel_number_tmp = $photographus_panel_number;
								$photographus_panel_number     = 0;

								// Get the template part file partials/front-page/content-post-and-page-panel.php.
								// Here we use include(locate_template()) to have access to the $photographus_panel_number var in the partial.
								// @link: http://keithdevon.com/passing-variables-to-get_template_part-in-wordpress/.
								include locate_template( 'partials/front-page/content-post-and-page-panel.php' );

								// Reset the $photographus_panel_number to the correct value.
								$photographus_panel_number = $photographus_panel_number_tmp;
							}
						} else {
							if ( is_page() ) {
								// Get the template part file partials/post/content-single.php (link in page.php).
								get_template_part( 'partials/post/content', 'single' );
							} else {
								// Get the template part file partials/post/content-single.php (link in page.php).
								get_template_part( 'partials/post/content', get_post_format() );
							}
						}
					}
				} else {
					// Include partials/post/content-none.php if no posts were found.
					get_template_part( 'partials/post/content', 'none' );
				}

				// Display the panels.
				if ( 0 !== $photographus_panel_number && 'page' === get_option( 'show_on_front' ) ) {
					photographus_the_front_page_panels();
				}
				photographus_the_posts_pagination();
				?>
			</main>
		</div>
		<?php get_sidebar(); ?>
	</div>
<?php
get_footer();
