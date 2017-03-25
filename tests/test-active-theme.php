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

// backward compatibility
if ( ! class_exists( '\PHPUnit\Framework\TestCase' ) &&
     class_exists( '\PHPUnit_Framework_TestCase' )
) {
	class_alias( '\PHPUnit_Framework_TestCase', '\PHPUnit\Framework\TestCase' );
}

/**
 * Sample test case.
 */
class TestActiveTheme extends WP_UnitTestCase {

	/**
	 * Trying to fix the error on GitLab CI
	 */
	function setUp() {

		parent::setUp();
		switch_theme( 'Photographia' );

	} // end setup

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
