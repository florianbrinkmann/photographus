<?php
/**
 * Customizer functions
 *
 * @version 1.0.0
 *
 * @package Photographia
 */

/**
 * Customizer settings
 *
 * @param WP_Customize_Manager $wp_customize The Customizer object.
 */
function photographia_customize_register( $wp_customize ) {
	$wp_customize->remove_control( 'header_textcolor' );

	$wp_customize->add_section( 'photographia_options', [
		'title' => __( 'Theme options', 'photographia' ),
	] );

	$wp_customize->add_setting( 'photographia_header_layout', [
		'default'           => 0,
		'sanitize_callback' => 'photographia_sanitize_checkbox',
	] );

	$wp_customize->add_control( 'photographia_header_layout', [
		'type'    => 'checkbox',
		'section' => 'photographia_options',
		'label'   => __( 'Alternative header layout', 'photographia' ),
	] );

	/**
	 * Change transport to refresh
	 */
	$wp_customize->get_setting( 'custom_logo' )->transport = 'refresh';
}

add_action( 'customize_register', 'photographia_customize_register', 11 );

/**
 * Checkbox sanitization callback example.
 *
 * Sanitization callback for 'checkbox' type controls. This callback sanitizes `$checked`
 * as a boolean value, either TRUE or FALSE.
 *
 * @source https://github.com/WPTRT/code-examples/blob/master/customizer/sanitization-callbacks.php
 * @param bool $checked Whether the checkbox is checked.
 *
 * @return bool Whether the checkbox is checked.
 */
function photographia_sanitize_checkbox( $checked ) {
	// Boolean check.
	return ( ( isset( $checked ) && true === $checked ) ? true : false );
}

/**
 * Prints CSS inside header
 *
 * @return void
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
