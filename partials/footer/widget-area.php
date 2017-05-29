<?php
/**
 * Template part for displaying the footer widget area.
 *
 * @version 1.0.0
 *
 * @package Photographus
 */

/**
 * Check if the footer widget area has widgets.
 */
if ( is_active_sidebar( 'sidebar-footer' ) ) { ?>
	<aside class="site-footer-widget-area">
		<?php
		/**
		 * Output the widgets.
		 */
		dynamic_sidebar( 'sidebar-footer' ); ?>
	</aside>
<?php } ?>
