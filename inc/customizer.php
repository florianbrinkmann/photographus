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
	 * Front page panels. Inspired by https://core.trac.wordpress.org/browser/tags/4.7.3/src/wp-content/themes/twentyseventeen/inc/customizer.php#L88
	 */

	/**
	 * Filter number of front page sections in Photographia.
	 *
	 * @param int $num_sections Number of front page sections.
	 */
	$num_sections = apply_filters( 'photographia_front_page_sections', 4 );

	/**
	 * Create a setting and control for each of the sections available in the theme.
	 */
	for ( $i = 1; $i < ( 1 + $num_sections ); $i ++ ) {
		/**
		 * Create setting for saving the content type choice.
		 */
		$wp_customize->add_setting( "photographia_panel_{$i}_content_type", [
			'default'           => 'page',
			'sanitize_callback' => 'photographia_sanitize_select',
		] );

		/**
		 * Create control for content choice.
		 */
		$wp_customize->add_control( "photographia_panel_{$i}_content_type", [
			'label'   => "Panel $i",
			'type'    => 'select',
			'section' => 'photographia_options',
			'choices' => [
				'page'         => __( 'Page', 'photographia' ),
				'post'         => __( 'Post', 'photographia' ),
				'latest-posts' => __( 'Latest Posts', 'photographia' ),
				'post-grid'    => __( 'Post Grid', 'photographia' ),
			],
		] );

		/**
		 * Create setting for page.
		 */
		$wp_customize->add_setting( "photographia_panel_{$i}_page", [
			'default'           => false,
			'sanitize_callback' => 'absint',
		] );

		/**
		 * Create control for page.
		 */
		$wp_customize->add_control( "photographia_panel_{$i}_page", [
			'label'           => 'Select page',
			'section'         => 'photographia_options',
			'type'            => 'dropdown-pages',
			'allow_addition'  => true,
			'active_callback' => 'photographia_is_page_panel',
			'input_attrs'     => [
				'data-number' => $i,
			],
		] );
	}

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
 * @link https://github.com/WPTRT/code-examples/blob/master/customizer/sanitization-callbacks.php
 *
 * @param bool $checked Whether the checkbox is checked.
 *
 * @return bool Whether the checkbox is checked.
 */
function photographia_sanitize_checkbox( $checked ) {
	/**
	 * Boolean check.
	 */
	return ( ( isset( $checked ) && true === $checked ) ? true : false );
}

/**
 * Select sanitization callback example.
 *
 * - Sanitization: select
 * - Control: select, radio
 *
 * Sanitization callback for 'select' and 'radio' type controls. This callback sanitizes `$input`
 * as a slug, and then validates `$input` against the choices defined for the control.
 *
 * @link https://github.com/WPTRT/code-examples/blob/master/customizer/sanitization-callbacks.php
 *
 * @see  sanitize_key()               https://developer.wordpress.org/reference/functions/sanitize_key/
 * @see  $wp_customize->get_control()
 *       https://developer.wordpress.org/reference/classes/wp_customize_manager/get_control/
 *
 * @param string               $input   Slug to sanitize.
 * @param WP_Customize_Setting $setting Setting instance.
 *
 * @return string Sanitized slug if it is a valid choice; otherwise, the setting default.
 */
function photographia_sanitize_select( $input, $setting ) {
	/**
	 * Ensure input is a slug.
	 */
	$input = sanitize_key( $input );

	/**
	 * Get list of choices from the control associated with the setting.
	 */
	$choices = $setting->manager->get_control( $setting->id )->choices;

	/**
	 * If the input is a valid key, return it; otherwise, return the default.
	 */
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Check if we need to display the dropdown-pages control for the panel.
 *
 * @param WP_Customize_Control $control Control object.
 *
 * @return bool
 */
function photographia_is_page_panel( $control ) {
	/**
	 * Get the panel number from the input_attrs array, defined in the add_control() call.
	 */
	$panel_number = $control->input_attrs['data-number'];

	/**
	 * Get the value of the content type control.
	 */
	$content_type = $control->manager->get_setting( "photographia_panel_{$panel_number}_content_type" )->value();

	/**
	 * Return true if the value is page.
	 */
	if ( 'page' === $content_type ) {
		return true;
	} else {
		return false;
	}
}

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
