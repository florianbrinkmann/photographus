<?php
/**
 * Handling automatic theme updates
 *
 * @version 1.0.0
 *
 * @package Photographia
 */

/**
 * Customizer settings for theme update
 *
 * @param WP_Customize_Manager $wp_customize The Customizer object.
 */
function photographia_update_customize_register( $wp_customize ) {
	$wp_customize->add_setting( 'photographia_upgrade_url', array(
		'type'              => 'option',
		'default'           => '',
		'sanitize_callback' => 'photographia_esc_update_url',
	) );

	if ( ! is_multisite() ) {
		$wp_customize->add_control( 'photographia_upgrade_url', array(
			'priority' => 1,
			'type'     => 'url',
			'section'  => 'photographia_options',
			'label'    => __( 'Paste your download link for »Photographia« to enable automatic theme updates.', 'photographia' ),
		) );
	}
}

add_action( 'customize_register', 'photographia_update_customize_register', 12 );

/**
 * Escape URL and check if it matches a valid download format
 *
 * @param $url
 *
 * @return string
 */
function photographia_esc_update_url( $url ) {
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
 * @param $transient
 *
 * @return mixed
 */
function photographia_theme_update( $transient ) {
	if ( empty( $transient->checked ) ) {
		return $transient;
	}

	$request = photographia_fetch_data_of_latest_version();
	if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) !== 200 ) {
		return $transient;
	} else {
		$response = wp_remote_retrieve_body( $request );
	}

	$data     = json_decode( $response );
	$theme_id = $data->theme_id;
	unset( $data->theme_id );
	if ( version_compare( $transient->checked['photographia'], $data->new_version, '<' ) ) {
		$transient->response['photographia']        = (array) $data;
		$transient->response['photographia']['url'] = __( 'https://florianbrinkmann.com/en/wordpress-themes/photographia/changelog/', 'photographia' );

		$theme_package = get_option( 'photographia_upgrade_url' );
		if ( ! empty ( $theme_package ) ) {
			$pattern = '/^https:\/\/florianbrinkmann\.com\/(en\/)?\?download_file=' . $theme_id . '|^https:\/\/(en\.)?florianbrinkmann\.de\/\?download_file=' . $theme_id . '/';
			preg_match( $pattern, $theme_package, $matches );
			if ( ! empty ( $matches ) ) {
				$transient->response['photographia']['package'] = $theme_package;
			} else {
			}
		}
	}

	return $transient;
}

add_filter( 'pre_set_site_transient_update_themes', 'photographia_theme_update' );

/**
 * Fetch data of latest theme version
 *
 * @return array|WP_Error
 */
function photographia_fetch_data_of_latest_version() {
	$request = wp_safe_remote_get( 'https://florianbrinkmann.com/wordpress-themes/photographia/upgrade-json/' );

	return $request;
}

/**
 * Remove upgrade URL option after switching the theme,
 * if the new theme is not photographia or a child theme of photographia
 */
function photographia_remove_upgrade_url() {
	$theme_object = wp_get_theme();
	$template     = $theme_object->template;
	if ( 'photographia' === $template ) {

	} else {
		delete_option( 'photographia_upgrade_url' );
	}
}

add_action( 'switch_theme', 'photographia_remove_upgrade_url', 10, 2 );
