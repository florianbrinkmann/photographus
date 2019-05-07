<?php
/**
 * Functions which print styles from or in the customizer.
 *
 * @version 1.0.1
 *
 * @package Photographus
 */

/**
 * Prints CSS inside header.
 */
function photographus_customizer_css() {
	// Check if header text should be displayed. Otherwise hide it.
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
		<?php
	}
}

/**
 * Prints styles for the customize controls.
 */
function photographus_customize_controls_styles() {
	$panel_section_description = __( 'You can show different contents on the front page in so-called »panels«. You can choose from the content types »Page«, »Post«, »Latest Posts«, and »Post Grid«.', 'photographus' );
	$header_image_description  = __( 'The header image is only displayed on the front page if you have panels with content.', 'photographus' );
	?>
	<style>
		#customize-control-header_image .customizer-section-intro::before {
			content: '<?php echo $header_image_description; // phpcs:ignore ?>';
			display: block;
		}

		#customize-control-photographus_panel_1_content_type::before {
			content: '<?php echo $panel_section_description; // phpcs:ignore ?>';
			display: block;
		}

		#sub-accordion-section-photographus_options [id*="_content_type"] > label::before {
			background: #ddd;
			content: '';
			display: block;
			height: 1px;
			left: 0;
			position: absolute;
			top: -.3em;
			width: 100%;
		}

		#sub-accordion-section-photographus_options [id*="_content_type"] > label {
			display: block;
			margin-top: 1em;
			position: relative;
		}
	</style>
	<?php
}

/**
 * Prints styles for the customize controls.
 */
function photographus_customize_preview_styles() {
	if ( is_customize_preview() ) {
		?>
		<style>
			span[class*="_content_type_partial"] {
				left: 40px;
			}
		</style>
		<?php
	}
}

/**
 * Prints styles inside the customizer view.
 */
function photographus_customizer_contols_script() {
	wp_enqueue_script( 'photographus-customize-controls-script', get_theme_file_uri( 'assets/js/customize-controls.js' ), [], filemtime( get_theme_file_path( 'assets/js/customize-controls.js' ) ), true );
}
