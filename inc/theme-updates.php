<?php
/**
 * Handling automatic theme updates
 *
 * @version 1.0.0
 *
 * @package Photographus
 */

add_action( 'customize_register', 'photographus_update_customize_register', 12 );

add_filter( 'pre_set_site_transient_update_themes', 'photographus_theme_update' );

add_action( 'switch_theme', 'photographus_remove_upgrade_url', 10, 2 );


/**
 * Customizer settings for theme update
 *
 * @param WP_Customize_Manager $wp_customize The Customizer object.
 */
function photographus_update_customize_register( $wp_customize ) {
	$wp_customize->add_setting( 'photographus_upgrade_url', [
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'photographus_esc_update_url',
	] );

	if ( ! is_multisite() ) {
		$wp_customize->add_control( 'photographus_upgrade_url', [
			'priority' => 1,
			'type'     => 'url',
			'section'  => 'photographus_options',
			'label'    => __( 'Paste your download link for »Photographus« to enable automatic theme updates.', 'photographus' ),
		] );
	}
}

/**
 * Escape URL and check if it matches a valid download format
 *
 * @param string $url Update URL from customizer control.
 *
 * @return string
 */
function photographus_esc_update_url( $url ) {
	$url     = esc_url_raw( $url );
	$pattern = '/^https:\/\/florianbrinkmann\.com\/(en\/)?\?download_file=|^https:\/\/(en\.)?florianbrinkmann\.de\/\?download_file=/';
	preg_match( $pattern, $url, $matches );

	if ( ! empty ( $matches ) ) {
		return $url;
	} else {
		return '';
	}
}

/**
 * Checking for updates and updating the transient for theme updates
 *
 * @param object $transient Transient object for theme updates.
 *
 * @return object
 */
function photographus_theme_update( $transient ) {
	if ( empty( $transient->checked ) ) {
		return $transient;
	}

	$request = photographus_fetch_data_of_latest_version();
	if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) !== 200 ) {
		return $transient;
	} else {
		$response = wp_remote_retrieve_body( $request );
	}

	$data     = json_decode( $response );
	$theme_id = $data->theme_id;
	unset( $data->theme_id );
	if ( version_compare( $transient->checked['photographus'], $data->new_version, '<' ) ) {
		$transient->response['photographus']        = (array) $data;
		$transient->response['photographus']['url'] = __( 'https://florianbrinkmann.com/en/wordpress-themes/photographus/changelog/', 'photographus' );

		$theme_package = get_option( 'photographus_upgrade_url' );
		if ( ! empty ( $theme_package ) ) {
			$pattern = '/^https:\/\/florianbrinkmann\.com\/(en\/)?\?download_file=' . $theme_id . '|^https:\/\/(en\.)?florianbrinkmann\.de\/\?download_file=' . $theme_id . '/';
			preg_match( $pattern, $theme_package, $matches );
			if ( ! empty ( $matches ) ) {
				$transient->response['photographus']['package'] = $theme_package;
			} else {
			}
		}
	} // End if().

	return $transient;
}

/**
 * Fetch data of latest theme version
 *
 * @return array|WP_Error
 */
function photographus_fetch_data_of_latest_version() {
	$request = wp_safe_remote_get( 'https://florianbrinkmann.com/wordpress-themes/photographus/upgrade-json/' );

	return $request;
}

/**
 * Remove upgrade URL option after switching the theme,
 * if the new theme is not Photographus or a child theme of Photographus.
 */
function photographus_remove_upgrade_url() {
	$theme_object = wp_get_theme();
	$template     = $theme_object->template;
	if ( 'photographus' === $template ) {

	} else {
		delete_option( 'photographus_upgrade_url' );
	}
}
