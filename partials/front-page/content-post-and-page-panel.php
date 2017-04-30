<?php
/**
 * Template part for displaying content of post or page front page panels.
 *
 * @version 1.0.0
 *
 * @package Photographia
 */

?>
<section id="frontpage-section-<?php echo $panel_number; ?>" class="clearfix frontpage-section">
	<article>
		<?php the_title( '<h2 class="frontpage-section-title">', '</h2>' ) ?>
		<div class="entry-content <?php echo photographia_get_post_type_template_class(); ?>">
			<?php echo photographia_get_the_post_thumbnail(); ?>
			<div>
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
		</div>
	</article>
</section>
