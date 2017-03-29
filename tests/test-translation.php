<?php
/**
 * Class Test_Translation
 *
 * Tests translation functions from functions.php
 *
 * @package Photographia
 */

/**
 * Include functions.php
 */
require_once 'functions.php';

/**
 * Tests translation functions from functions.php
 */
class Test_Translation extends WP_UnitTestCase {

	/**
	 * Checks photographia_is_login_page() function
	 */
	function test_photographia_is_login_page() {

		/**
		 * Check if photographia_is_login_page() returns true if $GLOBALS['pagenow']
		 * is wp-login.php
		 */
		$GLOBALS['pagenow'] = 'wp-login.php';
		$this->assertTrue( photographia_is_login_page() );

		/**
		 * Check if photographia_is_login_page() returns false if $GLOBALS['pagenow']
		 * is wp-comments-post.php
		 */
		$GLOBALS['pagenow'] = 'wp-comments-post.php';
		$this->assertFalse( photographia_is_login_page() );

		/**
		 * Check if photographia_is_login_page() returns true if $GLOBALS['pagenow']
		 * is wp-register.php
		 */
		$GLOBALS['pagenow'] = 'wp-register.php';
		$this->assertTrue( photographia_is_login_page() );
	}

	/**
	 * Checks photographia_is_wp_comments_post() function
	 */
	function test_photographia_is_wp_comments_post() {

		/**
		 * Check if photographia_is_wp_comments_post() returns true if $GLOBALS['pagenow']
		 * is wp-comments-post.php
		 */
		$GLOBALS['pagenow'] = 'wp-comments-post.php';
		$this->assertTrue( photographia_is_wp_comments_post() );

		/**
		 * Check if photographia_is_wp_comments_post() returns false if $GLOBALS['pagenow']
		 * is wp-login.php
		 */
		$GLOBALS['pagenow'] = 'wp-login.php';
		$this->assertFalse( photographia_is_wp_comments_post() );
	}
}
