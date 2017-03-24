<?php
/**
 * Class TestActiveTheme
 *
 * @see https://code.tutsplus.com/articles/the-beginners-guide-to-unit-testing-building-testable-themes--wp-26007
 *
 * @package Photographia
 */

/**
 * Include theme’s functions.php
 */
include_once( 'functions.php' );

/**
 * Sample test case.
 */
class TestActiveTheme extends WP_UnitTestCase {

	/**
	 * Return true if the currently active theme is Photographia
	 */
	function test_active_theme() {
		$this->assertTrue( 'Photographia' == wp_get_theme() );
	}

	/**
	 * Return false if the currently active theme is the default theme Twenty Seventeen
	 */
	function test_inactive_theme() {
		$this->assertFalse( 'Twenty Seventeen' == wp_get_theme() );
	}
}
