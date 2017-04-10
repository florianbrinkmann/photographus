<?php
/**
 * Template part for displaying content of normal posts on single view.
 *
 * @version 1.0.0
 *
 * @package Photographia
 */

?>
<article <?php post_class( 'clearfix' ); ?>>
	<?php photographia_the_entry_header( 'h1', false ); ?>
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
</article>
