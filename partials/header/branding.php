<?php
/**
 * Part of header with logo or title and description.
 *
 * @version 1.0.0
 *
 * @package Photographus
 */

/**
 * Check if we have a custom logo from the customizer.
 */
if ( '' != photographus_get_custom_logo() ) {

	/**
	 * Output an opening h1 tag if we are on the front page and it is the blog overview.
	 */
	if ( ( is_front_page() && is_home() ) ) { ?>
		<h1 class="logo">
	<?php }

	/**
	 * Display the logo.
	 */
	echo photographus_get_custom_logo();

	/**
	 * Closing h1 tag if on front page with blog overview.
	 */
	if ( ( is_front_page() && is_home() ) ) { ?>
		</h1>
	<?php }
} else {

	/**
	 * Wrap title into h1 if we are on front page with blog posts.
	 */
	if ( ( is_front_page() && is_home() ) ) { ?>
		<h1 class="site-title">
			<?php
			/**
			 * Check if we are not on the first blog page on the front page
			 * and output a home link.
			 */
			if ( is_paged() ) { ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php }
				/**
				 * Display blog title.
				 */
				bloginfo( 'name' );
				if ( is_paged() ) { ?>
			</a>
		<?php } ?>
		</h1>
	<?php } else { ?>
		<p class="site-title">
			<?php
			/**
			 * Only link title if not on front page.
			 */
			if ( ! is_front_page() ) { ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php }

				/**
				 * Display blog title.
				 */
				bloginfo( 'name' );
				if ( ! is_front_page() ) { ?>
			</a>
		<?php } ?>
		</p>
	<?php }

	/**
	 * Get blog description and display if not empty.
	 */
	$description = get_bloginfo( 'description', 'display' );
	if ( '' !== $description ) { ?>
		<p class="site-description"><?php echo $description; ?></p>
	<?php }
}
