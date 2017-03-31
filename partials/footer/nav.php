<?php
/**
 * Footer navigation
 *
 * @version 1.0.0
 *
 * @package Photographia
 */

if ( has_nav_menu( 'footer' ) ) { ?>
	<nav class="secondary-nav-container">
		<h2 class="screen-reader-text">
			<?php /* translators: hidden screen reader headline for the main navigation */
			_e( 'Footer navigation', 'photographia' ); ?>
		</h2>
		<?php wp_nav_menu(
			[
				'theme_location' => 'footer',
				'menu_class'     => 'secondary-nav',
				'container'      => '',
				'depth'          => 1,
			]
		); ?>
	</nav>
<?php }
