<?php
/**
 * Template for displaying the header
 *
 * @version 1.0.1
 *
 * @package Photographus
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php
	// Check if we are in a single view and the pings are open.
	if ( is_singular() && pings_open() ) {
		?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php
	}

	// Fire wp_head action, which includes styles, scripts, et cetera, from core, themes, and plugins.
	wp_head();
	?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="wrapper">
	<?php
	/**
	 * Adding possibility to add additional classes to the header via filter.
	 *
	 * @param string $photographus_header_classes string of additional classes.
	 */
	$photographus_header_classes = apply_filters( 'photographus_additional_header_classes', '' );
	?>
	<header class="site-header clearfix <?php echo esc_attr( $photographus_header_classes ); ?>"
			role="banner"
		<?php
		// Includes a inline style with header image as full background, if we are on the front page
		// with panels.
		photographus_the_front_page_header_image();
		?>
	>
		<div class="primary-header -inverted-link-style">
			<div class="branding">
				<?php
				// Include file which displays site title and discription or logo.
				require locate_template( 'partials/header/branding.php' );
				?>
			</div>
			<?php
			// Include file for displaying the main navigation.
			require locate_template( 'partials/header/nav.php' );
			?>
		</div>
		<?php
		// Display a scroll down arrow if we have a header image and are on the front page with panels.
		photographus_the_scroll_arrow_icon();
		?>
	</header>
