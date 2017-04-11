<?php
/**
 * Template part for displaying content of normal posts.
 *
 * @version 1.0.0
 *
 * @package Photographia
 */

?>
<article <?php post_class( 'clearfix' ); ?>>
	<?php photographia_the_entry_header( 'h2' ); ?>
	<div class="entry-content">
		<?php
		/**
		 * Displays the post content.
		 */
		photographia_the_content();

		/**
		 * Display pagination if the post is paginated.
		 */
		photographia_wp_link_pages(); ?>
	</div>
	<footer class="entry-footer">
		<?php
		/**
		 * Displays the footer meta: categories, tags, comment count and trackback count.
		 */
		photographia_the_entry_footer_meta(); ?>
	</footer>
	<?php
	/**
	 * Closing div tag if we have a post with the template
	 * large portrait featured image.
	 *
	 * Tag was opened in photographia_the_entry_header()
	 */
	$post_type_template = photographia_get_post_type_template();
	if ( 'large-portrait-featured-image' === $post_type_template ) { ?>
		</div>
	<?php } ?>
</article>
