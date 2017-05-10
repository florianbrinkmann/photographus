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
 * Get the post thumbnail img element with responsive images.
 */
$post_thumbnail_img_element = wp_get_attachment_image( $post_thumbnail_id, $size ); ?>
<article class="gallery-grid-item" style="max-width: <?php echo $post_thumbnail[1]; ?>px;">
	<a href="<?php the_permalink(); ?>">
		<figure class="post-thumbnail" aria-hidden="true" width="<?php echo $post_thumbnail[1]; ?>"
		        height="<?php echo $post_thumbnail[2]; ?>">
			<?php echo $post_thumbnail_img_element ?>
		</figure>
		<div>
			<header class="entry-header">
				<div class="<?php echo $entry_title_div_class_string; ?>">
					<?php echo photographus_get_the_title( $heading_element, false ); ?>
				</div>
				<figure class="post-thumbnail screen-reader-text">
					<?php echo $post_thumbnail_img_element ?>
				</figure>
			</header>
		</div>
	</a>
</article>
