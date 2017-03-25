<?php
/**
 * Class SampleTest
 *
 * @package Photographia
 */

/**
 * Autoload the PHPunit
 */
$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = '/tmp/wordpress-tests-lib';
}
require_once $_tests_dir . '/includes/testcase.php';

/**
 * Sample test case.
 */
class SampleTest extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */
	function test_sample() {
		// Replace this with some actual testing code.
		$this->assertTrue( true );
	}
}
