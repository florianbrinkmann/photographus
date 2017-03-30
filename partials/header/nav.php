<?php
/**
 * Header navigation
 *
 * @version 1.0.0
 *
 * @package Photographia
 */

if ( has_nav_menu( 'primary' ) ) { ?>
	<nav class="primary-nav-container">
		<h2 class="screen-reader-text">
			<?php /* translators: hidden screen reader headline for the main navigation */
			_e( 'Main navigation', 'photographia' ); ?>
		</h2>
		<?php wp_nav_menu(
			[
				'theme_location' => 'primary',
				'menu_class'     => 'primary-nav',
				'container'      => '',
				'depth'          => 1,
			]
		); ?>
	</nav>
<?php }
