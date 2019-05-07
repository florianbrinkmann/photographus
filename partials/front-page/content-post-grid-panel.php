<?php
/**
 * Template part for displaying content of posts in the post grid.
 *
 * @version 1.0.1
 *
 * @package Photographus
 */

// Get the post thumbnail ID.
$photographus_post_thumbnail_id = get_post_thumbnail_id();

/**
 * Filter images size of post grid images.
 *
 * @param string $photographus_size Images size.
 */
$photographus_size = apply_filters( 'photographus_post_grid_thumbnail_size', 'medium' );

// Get array with post thumbnail URL, width and height.
$photographus_post_thumbnail = wp_get_attachment_image_src( $photographus_post_thumbnail_id, $photographus_size );

// Add class to gallery grid item if the titles are hidden.
// $photographus_hide_gallery_titles is specified in photographus_the_post_grid_panel()
// located in inc/front-page-panel-functions.php.
if ( true === $photographus_hide_gallery_titles ) {
	$photographus_gallery_grid_item_class = ' -hidden-title';
} else {
	$photographus_gallery_grid_item_class = '';
}

// Get the post thumbnail img element with responsive images.
$photographus_post_thumbnail_img_element = wp_get_attachment_image( $photographus_post_thumbnail_id, $photographus_size ); ?>
<article class="gallery-grid-item<?php echo esc_attr( $photographus_gallery_grid_item_class ); // phpcs:ignore ?>"
		style="max-width: <?php echo esc_attr( $photographus_post_thumbnail[1] ); ?>px;">
	<a href="<?php the_permalink(); ?>">
		<figure class="post-thumbnail" aria-hidden="true"
				style="width:<?php echo esc_attr( $photographus_post_thumbnail[1] ); ?>px; height:<?php echo esc_attr( $photographus_post_thumbnail[2] ); ?>px;">
			<?php
			// Display the post thumbnail which is visible
			// but hidden for screen readers.
			echo $photographus_post_thumbnail_img_element; // phpcs:ignore
			?>
		</figure>
		<div>
			<header class="entry-header">
				<div class="
				<?php
				// Outputs class string.
				// $entry_title_div_class_string is specified in photographus_the_post_grid_panel()
				// located in inc/front-page-panel-functions.php.
				echo esc_attr( $entry_title_div_class_string );
				?>
				">
					<?php
					// Displays the title.
					// $heading_element is specified in photographus_the_post_grid_panel()
					// located in inc/front-page-panel-functions.php.
					echo photographus_get_the_title( $heading_element, false ); // phpcs:ignore
					?>
				</div>
				<figure class="post-thumbnail screen-reader-text">
					<?php
					// Displays the post thumbnail which is visually hidden
					// but visible for screen readers.
					echo $photographus_post_thumbnail_img_element; // phpcs:ignore
					?>
				</figure>
			</header>
		</div>
	</a>
</article>
