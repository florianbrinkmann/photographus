<?php
/**
 * Handling automatic theme updates.
 *
 * @version 1.0.0
 *
 * @package Photographus
 */

/**
 * Customizer settings for theme update.
 *
 * @param WP_Customize_Manager $wp_customize The Customizer object.
 */
function photographus_update_customize_register( $wp_customize ) {
	/**
	 * Only add setting and control if we are not on a multisite.
	 */
	if ( ! is_multisite() ) {
		/**
		 * Add setting for URL.
		 */
		$wp_customize->add_setting( 'photographus_upgrade_url', [
			'type'              => 'option',
			'default'           => '',
			'sanitize_callback' => 'photographus_esc_update_url',
		] );

		/**
		 * Add control for update URL.
		 */
		$wp_customize->add_control( 'photographus_upgrade_url', [
			'priority' => 1,
			'type'     => 'url',
			'section'  => 'photographus_options',
			'label'    => __( 'Paste your download link for »Photographus« to enable automatic theme updates.', 'photographus' ),
		] );
	}
}

/**
 * Escape URL and check if it matches a valid download format.
 *
 * @param string $url Update URL from customizer control.
 *
 * @return string Escaped update URL or empty string.
 */
function photographus_esc_update_url( $url ) {
	/**
	 * Escape the URL.
	 */
	$url = esc_url_raw( $url );

	/**
	 * Possible update URL patterns.
	 */
	$pattern = '/^https:\/\/florianbrinkmann\.com\/(en\/)?\?download_file=|^https:\/\/(en\.)?florianbrinkmann\.de\/\?download_file=/';

	/**
	 * Check if we have a match.
	 */
	preg_match( $pattern, $url, $matches );

	/**
	 * If match, return the URL. Otherwise an empty string.
	 */
	if ( ! empty ( $matches ) ) {
		return $url;
	} else {
		return '';
	}
}

/**
 * Checking for updates and updating the transient for theme updates.
 *
 * @param object $transient Transient object for theme updates.
 *
 * @return object Theme update transient.
 */
function photographus_theme_update( $transient ) {
	if ( empty( $transient->checked ) ) {
		return $transient;
	}

	/**
	 * Get data of upgrade json on florianbrinkmann.com.
	 */
	$request = photographus_fetch_data_of_latest_version();

	/**
	 * Check if request is not valid and return the $transient.
	 * Otherwise get the data body.
	 */
	if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) !== 200 ) {
		return $transient;
	} else {
		$response = wp_remote_retrieve_body( $request );
	}

	/**
	 * Decode json.
	 */
	$data = json_decode( $response );

	/**
	 * Get the theme ID and unset it from the data object.
	 */
	$theme_id = $data->theme_id;
	unset( $data->theme_id );

	/**
	 * Check if new version is available.
	 */
	if ( version_compare( $transient->checked['photographus'], $data->new_version, '<' ) ) {
		/**
		 * Add response array for Photographus in $transient object.
		 */
		$transient->response['photographus'] = (array) $data;

		/**
		 * Set the changelog URL.
		 */
		$transient->response['photographus']['url'] = __( 'https://florianbrinkmann.com/en/wordpress-themes/photographus/changelog/', 'photographus' );

		/**
		 * Get the update URL theme option.
		 */
		$theme_package = get_option( 'photographus_upgrade_url' );

		/**
		 * Check if we have a URL.
		 */
		if ( ! empty ( $theme_package ) ) {
			/**
			 * Upgrade URL pattern (this time with theme ID, not without like in the customizer).
			 */
			$pattern = '/^https:\/\/florianbrinkmann\.com\/(en\/)?\?download_file=' . $theme_id . '|^https:\/\/(en\.)?florianbrinkmann\.de\/\?download_file=' . $theme_id . '/';

			/**
			 * Check for match.
			 */
			preg_match( $pattern, $theme_package, $matches );

			/**
			 * If match, add the package. Otherwise do not.
			 */
			if ( ! empty ( $matches ) ) {
				$transient->response['photographus']['package'] = $theme_package;
			}
		}
	} // End if().

	return $transient;
}

/**
 * Fetch data of latest theme version.
 *
 * @return array|WP_Error Array with data of the latest theme version or WP_Error.
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
	/**
	 * Get theme object.
	 */
	$theme_object = wp_get_theme();

	/**
	 * Get template.
	 */
	$template = $theme_object->template;

	/**
	 * Check if template is »photographus«.
	 */
	if ( 'photographus' !== $template ) {
		delete_option( 'photographus_upgrade_url' );
	}
}
