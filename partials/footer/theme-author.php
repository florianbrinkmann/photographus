<?php
/**
 * Theme author link in footer.
 *
 * @version 1.0.0
 *
 * @package Photographia
 */

?>
<p class="theme-author">
	<?php printf(
		__( 'Theme: Photographia by %s', 'photographia' ),
		sprintf(
			'<a rel="nofollow" href="%s">Florian Brinkmann</a>',
			__( 'https://florianbrinkmann.com/en/', 'photographia' )
		)
	); ?>
</p>
