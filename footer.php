<?php
/**
 * Template file for displaying the footer
 *
 * @version 1.0.0
 *
 * @package Photographia
 */

?>
	<footer class="site-footer clearfix">
		<?php
		/**
		 * Include file to display footer widget area.
		 */
		include locate_template( 'partials/footer/widget-area.php' );

		/**
		 * Include file to display footer menu.
		 */
		include locate_template( 'partials/footer/nav.php' );

		/**
		 * Include file to display theme author link.
		 */
		include locate_template( 'partials/footer/theme-author.php' ); ?>
	</footer>
<?php wp_footer();
