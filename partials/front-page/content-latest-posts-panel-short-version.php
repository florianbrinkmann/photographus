<?php
/**
 * Template part for displaying content of latest posts front page panel in short version (just title and meta).
 *
 * @version 1.0.0
 *
 * @package Photographia
 */

?>
<article>
	<header class="entry-header">
		<div>
			<?php echo photographia_get_the_title( $heading_element, true );
			echo photographia_get_the_entry_header_meta(); ?>
		</div>
	</header>
</article>

