<?php
/**
 * Template tags, used in template files
 *
 * @version 1.0.0
 *
 * @package Photographia
 */

if ( ! function_exists( 'photographia_get_custom_logo' ) ) {
	/**
	 * Wrap inside function_exists() to preserve back compat with WordPress versions older than 4.5
	 *
	 * @return string
	 */
	function photographia_get_custom_logo() {
		if ( function_exists( 'get_custom_logo' ) ) {
			if ( has_custom_logo() ) {
				return get_custom_logo();
			}
		}
	}
}
