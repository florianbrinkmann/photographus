<?php
/**
 * Template part for displaying content image attachment pages.
 *
 * @version 1.0.1
 *
 * @package Photographus
 */

?>
<article <?php post_class( 'clearfix' ); ?>>
	<?php
	// Display the header meta.
	photographus_the_entry_header( 'h1' );
	?>
	<div class="entry-content">
		<?php
		// Displays the image.
		echo wp_get_attachment_image( get_the_ID(), 'full' );
		?>
	</div>
</article>
