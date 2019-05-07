<?php
/**
 * Header navigation.
 *
 * @version 1.1.0
 *
 * @package photographus
 */

// Check if primary menu location has a menu with items.
if ( has_nav_menu( 'primary' ) ) { ?>
	<nav class="primary-nav-container">
		<h2 class="screen-reader-text">
			<?php
			/* translators: hidden screen reader headline for the main navigation */
			_e( 'Main navigation', 'photographus' ); // phpcs:ignore
			?>
		</h2>
		<?php
		// Display the menu.
		wp_nav_menu(
			[
				'theme_location' => 'primary',
				'menu_class'     => 'primary-nav',
				'container'      => '',
				'depth'          => 1,
			]
		);
		?>
	</nav>
	<?php
}
