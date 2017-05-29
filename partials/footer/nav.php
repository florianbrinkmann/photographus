<?php
/**
 * Footer navigation.
 *
 * @version 1.0.0
 *
 * @package Photographus
 */

/**
 * Check if the footer menu location has a menu with items.
 */
if ( has_nav_menu( 'footer' ) ) { ?>
	<nav class="secondary-nav-container -inverted-link-style">
		<h2 class="screen-reader-text">
			<?php /* translators: hidden screen reader headline for the main navigation */
			_e( 'Footer navigation', 'photographus' ); ?>
		</h2>
		<?php
		/**
		 * Display the menu.
		 */
		wp_nav_menu(
			[
				'theme_location' => 'footer',
				'menu_class'     => 'secondary-nav',
				'container'      => '',
				'depth'          => 1,
			]
		); ?>
	</nav>
<?php }
