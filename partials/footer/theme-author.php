<?php
/**
 * Theme author link in footer.
 *
 * @version 1.0.0
 *
 * @package Photographus
 */

?>
<p class="theme-author">
	<?php
	printf(
		__( 'Theme: Photographus by %s', 'photographus' ),
		sprintf(
			'<a rel="nofollow" href="%s">Florian Brinkmann</a>',
			__( 'https://florianbrinkmann.com/en/', 'photographus' )
		)
	);
	?>
</p>
