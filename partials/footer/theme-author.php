<?php
/**
 * Theme author link in footer.
 *
 * @version 1.1.0
 *
 * @package photographus
 */

?>
<p class="theme-author">
	<?php
	// phpcs:disable
	printf( /* translators: s=linked name of theme author */
		__( 'Theme: Photographus by %s', 'photographus' ),
		sprintf(
			'<a rel="nofollow" href="%s">Florian Brinkmann</a>',
			__( 'https://florianbrinkmann.com/en/', 'photographus' )
		)
	);
	// phpcs:enable
	?>
</p>
