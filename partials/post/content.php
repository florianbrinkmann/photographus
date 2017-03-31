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
	<header class="entry-header">
		<div>
			<?php
			/**
			 * Display the post title as a h2 headline, linked to the post.
			 */
			photographia_the_title( 'h2' );

			/**
			 * Display a featured label if this is a sticky post.
			 */
			photographia_the_sticky_label();

			/**
			 * Display a featured label if this is a sticky post.
			 */
			photographia_the_entry_header_meta(); ?>
		</div>
	</header>
	<div class="entry-content">
		<?php
		/**
		 * Displays the post content.
		 */
		photographia_the_content(); ?>
	</div>
	<footer class="entry-footer">
		<?php
		/**
		 * Displays the footer meta: categories, tags, comment count and trackback count.
		 */
		photographia_the_entry_footer_meta(); ?>
	</footer>
</article>
