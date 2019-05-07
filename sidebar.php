<?php
/**
 * Template for displaying the sidebar
 *
 * @version 1.1.0
 *
 * @package photographus
 */

// Check if we have widgets in the sidebar and are not on the front page
// with panels.
if ( is_active_sidebar( 'sidebar-1' ) && false === photographus_is_front_page_with_panels() ) { ?>
	<aside class="sidebar" role="complementary">
		<h2 class="screen-reader-text">
			<?php
			/* translators: screen reader text for the sidebar */
			_e( 'Sidebar', 'photographus' ); // phpcs:ignore
			?>
			</h2>
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</aside>
	<?php
}
