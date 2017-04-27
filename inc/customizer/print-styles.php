<?php
/**
 * Functions which print styles from or in the customizer.
 *
 * @version 1.0.0
 *
 * @package Photographia
 */

/**
 * Prints CSS inside header
 */
function photographia_customizer_css() {
	/**
	 * Check if Header text should be displayed. Otherwise hide it.
	 */
	if ( display_header_text() ) {
		return;
	} else { ?>
		<style type="text/css">
			.site-title,
			.site-description {
				clip: rect(1px, 1px, 1px, 1px);
				height: 1px;
				overflow: hidden;
				position: absolute !important;
				width: 1px;
				word-wrap: normal !important;
			}
		</style>
	<?php }
}

add_action( 'wp_head', 'photographia_customizer_css' );

/**
 * Prints styles inside the customizer view.
 */
function photographia_customizer_styles() { ?>
	<style>
		#sub-accordion-section-photographia_options [id*="_content_type"]::before {
			background: #ddd;
			content: '';
			display: block;
			height: 1px;
			left: 0;
			position: absolute;
			top: -.3em;
			width: 100%;
		}

		#sub-accordion-section-photographia_options [id*="_content_type"] {
			margin-top: 1em;
			position: relative;
		}
	</style>
<?php }

add_action( 'customize_controls_print_styles', 'photographia_customizer_styles', 999 );
