<?php
/**
 * Template part for displaying the footer widget area.
 *
 * @version 1.0.0
 *
 * @package Photographia
 */

if ( is_active_sidebar( 'sidebar-footer' ) ) { ?>
	<aside class="site-footer-widget-area">
		<?php dynamic_sidebar( 'sidebar-footer' ); ?>
	</aside>
<?php } ?>
