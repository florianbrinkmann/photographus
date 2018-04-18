<?php
/**
 * Template part which is displayed if a query does not find anything.
 *
 * @version 1.0.1
 *
 * @package Photographus
 */

?>
<article <?php post_class( 'clearfix' ); ?>>
	<div class="entry-header">
		<div>
			<h1 class="entry-title"><?php _e( 'Nothing found, sorry…', 'photographus' ); ?></h1>
		</div>
	</div>
	<div class="entry-content">
		<p><?php _e( 'It seems that the content you requested cannot be found. Maybe searching helps?', 'photographus' ); ?></p>
		<?php
		// Display the search form.
		get_search_form(); ?>
	</div>
</article>
