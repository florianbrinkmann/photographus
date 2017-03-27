<?php
/**
 * Class Test_Active_Theme
 *
 * Checks if the currently active theme is Photographia
 *
 * @package Photographia
 */

/**
 * Test active theme test case.
 */
class Test_Active_Theme extends WP_UnitTestCase {

	/**
	 * Checks if the currently active theme is Photographia
	 */
	function test_current_theme() {

		/**
		 * Get stylesheet name of current theme. Should be photographia
		 */
		$stylesheet = get_option( 'stylesheet' );

		/**
		 * Check if value of $stylesheet is photographia
		 */
		$this->assertEquals( $stylesheet, 'photographia' );
	}
}