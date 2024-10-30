<?php
/*
Plugin Name: LP Estimated Reading Time
Plugin URI:  http://layerpoint.com/estimated-reading-time
Description: Displays an estimated reading time of your blog posts
Version:     1.0
Author:      LayerPoint
Author URI:  http://layerpoint.com/
Domain Path: /languages
Text Domain: layerpoint-ert
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Copyright © 2016

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/

// Script accessed directly - abort!
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

// Text domain for plugin
define ( 'LP_ERT_i18n', 'layerpoint-ert' );

class Lp_Estimated_Reading_Time {

	function __construct() {

		// Load text domain for plugin
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

		// Enqueue scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'load_public_scripts' ) );

		// Register shortcode
		add_action( 'init', array( $this, 'estimated_reading_time_shortcode_register' ) );

		// Add filter to set estimated reading time
		$show_reading_time = get_option( 'lp_ert_enabled', true );
		if ( $show_reading_time ) {
			add_filter( 'the_content', array( $this, 'estimated_reading_time_filter' ) );
		}

		// Load admin page
		if ( is_admin() ) {
			require_once plugin_dir_path( __FILE__ ) . '/admin/options-page.php';
		}

	}

	// load text domain for this plugin
	function load_textdomain() {
		load_plugin_textdomain( LP_ERT_i18n, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	// adds estimated reading time to top of content
	function estimated_reading_time_filter( $content ) {

		$reading_time = $this->estimated_reading_time_shortcode();

		if ( ! empty( $reading_time ) ) {
			$content = $reading_time . $content;
		}

		return $content;
	}

	// estimated reading time shortcode
	function estimated_reading_time_shortcode() {

		$result 			= false;
		$words_per_minute	= get_option( 'lp_ert_words_per_minute', 150 );
		$show_in_homepage 	= get_option( 'lp_ert_show_in_homepage', false );
		$show_in_archive	= get_option( 'lp_ert_show_in_archive', false );
		$css_class			= get_option( 'lp_ert_css_class' );
		$before_text		= get_option( 'lp_ert_before_text', '<span class="lp-ert__icon dashicons dashicons-clock"></span>' );
		$after_text			= get_option( 'lp_ert_after_text', __( 'min read', LP_ERT_i18n )  );

		if ( ! is_numeric( $words_per_minute ) || 0 >= $words_per_minute ) {
			$words_per_minute = 150;
		}

		if ( ! $show_in_homepage && ( is_home() || is_front_page() ) ) {
			return $result;
		}

		if ( ! $show_in_archive && is_archive() ) {
			return $result;
		}

		GLOBAL $post;

		$content 			= strip_tags( $post->post_content );
		$content_words 		= str_word_count( $content );
		$estimated_minutes 	= floor( $content_words / $words_per_minute );
		$result 			= sprintf( '<div class="lp-ert %1$s">%2$s %3$s %4$s</div>', $css_class, $before_text, $estimated_minutes, $after_text );

		return $result;
	}

	// adds estimated reading time shortcode
	function estimated_reading_time_shortcode_register() {
		add_shortcode( 'lp_ert', array( $this, 'estimated_reading_time_shortcode' ) );
	}

	// regsiters and load public scripts
	function load_public_scripts() {
		// Default Dashicons
		wp_enqueue_style( 'dashicons' );
		// Our styles
		wp_enqueue_style( 'lp-ert-css', plugins_url( 'css/style.css', __FILE__ ) );
	}

}

// Initialize LP Estimated Reading Time Plugin
new Lp_Estimated_Reading_Time();