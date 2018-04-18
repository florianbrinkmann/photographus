<?php
/**
 * All add_action() calls.
 *
 * @version 1.0.1
 *
 * @package Photographus
 */

// Load the translation.
add_action( 'after_setup_theme', 'photographus_load_translation' );

// Set the content width.
add_action( 'after_setup_theme', 'photographus_set_content_width' );

// Run add_theme_support() calls.
add_action( 'after_setup_theme', 'photographus_add_theme_support' );

// Add editor stylesheet.
add_action( 'after_setup_theme', 'photographus_add_editor_style' );

// Register the menu locations.
add_action( 'init', 'photographus_register_menus' );

// Register the widget areas.
add_action( 'widgets_init', 'photographus_register_sidebars' );

// Enqueue the scripts and styles.
add_action( 'wp_enqueue_scripts', 'photographus_scripts_styles' );

// Refresh cache of latest posts front page panel.
add_action( 'wp_update_comment_count', 'photographus_refresh_latest_posts_cache', 10, 3 );

// Refresh cache of front page panels on post update.
add_action( 'transition_post_status', 'photographus_cache_update_on_post_update', 10, 3 );

// Register customizer controls and settings.
add_action( 'customize_register', 'photographus_customize_register', 11 );

// Print CSS which depends on customizer settings.
add_action( 'wp_head', 'photographus_customizer_css' );

// Include CSS into the customizer.
add_action( 'customize_controls_print_styles', 'photographus_customize_controls_styles', 999 );

// Include CSS into the customizer preview.
add_action( 'wp_head', 'photographus_customize_preview_styles', 999 );

// Include scripts into the customizer.
add_action( 'customize_controls_enqueue_scripts', 'photographus_customizer_contols_script', 999 );

// Register customizer control and setting for the theme update URL.
add_action( 'customize_register', 'photographus_update_customize_register', 12 );

// Remove upgrade URL after switching away from Photographus.
add_action( 'switch_theme', 'photographus_remove_upgrade_url', 10, 2 );
