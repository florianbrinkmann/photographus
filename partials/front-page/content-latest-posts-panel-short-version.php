<?php
/**
 * Template part for displaying content of latest posts
 * front page panel in short version (just title and meta).
 *
 * @version 1.1.0
 *
 * @package photographus
 */

?>
<article>
	<header class="entry-header -inverted-link-style">
		<div>
			<?php
			// Displays the post title.
			echo photographus_get_the_title( $heading_element, true ); // phpcs:ignore

			// Displays the entry meta.
			echo photographus_get_the_entry_header_meta(); // phpcs:ignore
			?>
		</div>
	</header>
</article>

