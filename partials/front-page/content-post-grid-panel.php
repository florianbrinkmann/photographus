<?php
/**
 * Template part for displaying content of posts in the post grid.
 *
 * @version 1.0.0
 *
 * @package Photographus
 */

/**
 * Get the post thumbnail ID.
 */
$post_thumbnail_id = get_post_thumbnail_id();

/**
 * Filter images size of post grid images.
 *
 * @param string $size Images size.
 */
$size = apply_filters( 'photographus_post_grid_thumbnail_size', 'medium' );

/**
 * Get array with post thumbnail URL, width and height.
 */
$post_thumbnail = wp_get_attachment_image_src( $post_thumbnail_id, $size );

/**
 * Add class to gallery grid item if the titles are hidden.
 * $hide_gallery_titles is specified in photographus_the_post_grid_panel()
 * located in inc/front-page-panel-functions.php.
 */
if ( true === $hide_gallery_titles ) {
	$gallery_grid_item_class = ' -hidden-title';
} else {
	$gallery_grid_item_class = '';
}

/**
 * Get the post thumbnail img element with responsive images.
 */
$post_thumbnail_img_element = wp_get_attachment_image( $post_thumbnail_id, $size ); ?>
<article class="gallery-grid-item<?php echo $gallery_grid_item_class; ?>"
         style="max-width: <?php echo $post_thumbnail[1]; ?>px;">
	<a href="<?php the_permalink(); ?>">
		<figure class="post-thumbnail" aria-hidden="true"
		        style="width:<?php echo $post_thumbnail[1]; ?>px; height:<?php echo $post_thumbnail[2]; ?>px;">
			<?php
			/**
			 * Display the post thumbnail which is visible
			 * but hidden for screen readers.
			 */
			echo $post_thumbnail_img_element ?>
		</figure>
		<div>
			<header class="entry-header">
				<div class="<?php
				/**
				 * Outputs class string.
				 * $entry_title_div_class_string is specified in photographus_the_post_grid_panel()
				 * located in inc/front-page-panel-functions.php.
				 */
				echo $entry_title_div_class_string; ?>">
					<?php
					/**
					 * Displays the title.
					 * $heading_element is specified in photographus_the_post_grid_panel()
					 * located in inc/front-page-panel-functions.php.
					 */
					echo photographus_get_the_title( $heading_element, false ); ?>
				</div>
				<figure class="post-thumbnail screen-reader-text">
					<?php
					/**
					 * Displays the post thumbnail which is visually hidden
					 * but visible for screen readers.
					 */
					echo $post_thumbnail_img_element ?>
				</figure>
			</header>
		</div>
	</a>
</article>
