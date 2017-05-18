<?php
/**
 * Template for displaying the header
 *
 * @version 1.0.0
 *
 * @package Photographus
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
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
	<?php
	/**
	 * Adding possibility to add additional classes to the header via filter.
	 *
	 * @param string $header_classes string of additional classes.
	 */
	$header_classes = apply_filters( 'photographus_additional_header_classes', '' ); ?>
	<header class="site-header clearfix <?php echo $header_classes; ?>"
	        role="banner"
		<?php
		/**
		 * Includes a inline style with header image as full background, if we are on the front page
		 * with panels.
		 */
		photographus_the_front_page_header_image(); ?>
	>
		<div class="primary-header -inverted-link-style">
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
		<?php
		/**
		 * Display a scroll down arrow if we have a header image and are on the front page with panels.
		 */
		photographus_the_scroll_arrow_icon(); ?>
	</header>
