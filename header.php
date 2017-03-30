<?php
/**
 * Template for displaying the header
 *
 * @version 1.0.0
 *
 * @package Photographia
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if ( is_singular() && pings_open() ) { ?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php }
	wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="wrapper">
	<header class="site-header clearfix
		<?php
		/**
		 * Adding possibility to add additional classes to the header via filter.
		 */
		echo apply_filters( 'photographia_additional_header_classes', $classes = '' ); ?>"
	        role="banner">
		<div class="primary-header">
			<div class="branding">
				<?php
				/**
				 * Include file which displays site title and discription or logo.
				 */
				include locate_template( 'partials/header/branding.php' ); ?>
			</div>
			<?php
			/**
			 * Include file for displaying the main navigation.
			 */
			include locate_template( 'partials/header/nav.php' ); ?>
		</div>
	</header>
