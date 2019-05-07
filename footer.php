<?php
/**
 * Template file for displaying the footer
 *
 * @version 1.1.0
 *
 * @package photographus
 */

?>
<footer class="site-footer clearfix">
	<?php
	// Include file to display footer widget area.
	require locate_template( 'partials/footer/widget-area.php' );

	// Include file to display footer menu.
	require locate_template( 'partials/footer/nav.php' );

	// Include file to display theme author link.
	require locate_template( 'partials/footer/theme-author.php' );
	?>
</footer>
<?php wp_footer(); ?>
</div><!--.wrapper-->
</body>
</html>
