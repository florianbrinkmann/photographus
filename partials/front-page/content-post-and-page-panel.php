<?php
/**
 * Template part for displaying content of post or page front page panels.
 *
 * @version 1.0.1
 *
 * @package Photographus
 */

?>
<section id="frontpage-section-<?php echo $photographus_panel_number; // phpcs:ignore ?>" class="clearfix frontpage-section">
	<article style="margin-top: 0">
		<?php
		// Displays the title inside a h2.
		the_title( '<h2 class="frontpage-section-title">', '</h2>' )
		?>
		<div class="entry-content 
		<?php
		// Outputs post type template specific class for
		// styling.
		echo photographus_get_post_type_template_class(); // phpcs:ignore
		?>
		">
			<?php
			// Displays the post thumbnail.
			echo photographus_get_the_post_thumbnail(); // phpcs:ignore
			?>
			<div>
				<?php
				// Displays the post content.
				photographus_the_content();

				// Display pagination if the post is paginated.
				photographus_wp_link_pages();
				?>
			</div>
		</div>
	</article>
</section>
