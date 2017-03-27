<?php
/**
 * Class Test_Theme_Support_Features
 *
 * Checks for theme feature support (added with add_theme_support() in functions.php)
 *
 * @package Photographia
 */

/**
 * Test theme support features test case.
 */
class Test_Theme_Support_Features extends WP_UnitTestCase {

	/**
	 * Check if photographia supports custom header
	 */
	function test_theme_support_custom_header() {
		$this->assertTrue( current_theme_supports( 'custom-header' ) );
	}

	/**
	 * Check if photographia supports automatic feed links
	 */
	function test_theme_support_automatic_feed_links() {
		$this->assertTrue( current_theme_supports( 'automatic-feed-links' ) );
	}

	/**
	 * Check if photographia supports title tag
	 */
	function test_theme_support_title_tag() {
		$this->assertTrue( current_theme_supports( 'title-tag' ) );
	}

	/**
	 * Check if photographia supports post formats
	 */
	function test_theme_support_post_formats() {
		$this->assertTrue( current_theme_supports( 'post-formats' ) );
	}

	/**
	 * Check if photographia supports html5
	 */
	function test_theme_support_html5() {
		$this->assertTrue( current_theme_supports( 'html5' ) );
	}

	/**
	 * Check if photographia supports post thumbnails
	 */
	function test_theme_support_post_thumbnails() {
		$this->assertTrue( current_theme_supports( 'post-thumbnails' ) );
	}

	/**
	 * Check if photographia supports custom logo
	 */
	function test_theme_support_custom_logo() {
		$this->assertTrue( current_theme_supports( 'custom-logo' ) );
	}
}
