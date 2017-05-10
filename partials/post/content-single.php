<?php
/**
 * Template part for displaying content of normal posts on single view.
 *
 * @version 1.0.0
 *
 * @package Photographus
 */

?>
<article <?php post_class( 'clearfix' ); ?>>
	<?php photographus_the_entry_header( 'h1', false ); ?>
	<div class="entry-content">
		<?php
		/**
		 * Displays the post content.
		 */
		photographus_the_content();

		/**
		 * Display pagination if the post is paginated.
		 */
		photographus_wp_link_pages(); ?>
	</div>
	<footer class="entry-footer">
		<?php
		/**
		 * Displays the footer meta: categories, tags, comment count and trackback count.
		 */
		photographus_the_entry_footer_meta(); ?>
	</footer>
	<?php
	/**
	 * Closing div tag if we have a post with the template
	 * large portrait featured image.
	 *
	 * Tag was opened in photographus_the_entry_header()
	 */
	$post_type_template = photographus_get_post_type_template();
	if ( 'large-portrait-featured-image' === $post_type_template ) { ?>
		</div>
	<?php } ?>
</article>
