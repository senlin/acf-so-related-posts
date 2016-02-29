<?php
/**
 * Plugin Name: ACF SO Related Posts
 * Plugin URI: http://so-wp.com/?p=63
 * Description: The ACF SO Related Posts plugin puts you in control on what really is related content. No more front end database queries that slow your site down, the work is all done on the back end.
 * Version: 0.0.1
 * Author: SO WP
 * Author URI: http://so-wp.com
 * Text Domain: acf-so-related-posts
 * Domain Path: /languages
 *
 * Copyright 2016 Piet Bos (piet@so-wp.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 */

/**
 * Prevent direct access to files, exit if that happens
 *
 * @since 1.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Let's make sure ACF Pro is installed & activated
 * If not, we give notice and kill the activation of ACF SO Related Posts.
 * Also works if ACF Pro is deactivated.
 *
 * @source: various existing ACF addons
 *
 * @since 1.0
 */
add_action( 'admin_init', 'acf_pro_or_die' );

if ( ! function_exists( 'acf_pro_or_die' ) ) {
	
	function acf_pro_or_die(){
		
		if ( ! function_exists( 'acf_register_repeater_field' ) && ! class_exists( 'acf' ) ) {
			
			deactivate_plugins( plugin_basename( __FILE__ ) );   
				
				if ( isset( $_GET['activate'] ) ) {
					unset( $_GET['activate'] );
				}
			
			add_action( 'admin_notices', 'acfsorp_dependent_plugin_notice' );
		
		}
	
	}

}

function acfsorp_dependent_plugin_notice(){ 
	
	printf( '<div class="error"><p>' . __( 'ACF SO Related Posts requires <a href="%1$s" target="_blank" style="%2$s">Advanced Custom Fields 5 Pro</a> to be installed &amp; activated.', 'acf-so-related-posts' ) . '</p></div>',
		'http://www.advancedcustomfields.com/pro/',
		'font-weight:bold;'
	);
	
}

/**
 *
 * @since 1.0
 */
class ACFSORP_Load {

	function __construct() {

		global $acfsorp;

		/* Set up an empty class for the global $sorp object. */
		$acfsorp = new stdClass;

		/* Set the init. */
		add_action( 'admin_init', array( $this, 'init' ), 1 );

		/* Set the constants needed by the plugin. */
		add_action( 'plugins_loaded', array( $this, 'constants' ), 2 );

		/* Internationalize the text strings used. */
		add_action( 'plugins_loaded', array( $this, 'i18n' ), 3 );

		/* Load the functions files. */
		add_action( 'plugins_loaded', array( $this, 'includes' ), 4 );

		/* Load the admin files. */
		add_action( 'plugins_loaded', array( $this, 'admin' ), 5 );

	}

	/**
	 * Init plugin options to white list our options
	 *
	 * @since 1.3.0
	 */
	function init() {
		
		register_setting( 'acfsorp_plugin_options', 'acfsorp_options', 'acfsorp_validate_options' );
		
	}

	/**
	 * Defines constants used by the plugin.
	 *
	 * @since 1.3.0
	 */
	function constants() {

		/* Set the version number of the plugin. */
		define( 'ACFSORP_VERSION', '1.0.0' );

		/* Set constant path to the plugin directory. */
		define( 'ACFSORP_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		/* Set constant path to the plugin URL. */
		define( 'ACFSORP_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

		/* Set the constant path to the inc directory. */
		define( 'ACFSORP_INCLUDES', ACFSORP_DIR . trailingslashit( 'inc' ) );

		/* Set the constant path to the admin directory. */
		define( 'ACFSORP_ADMIN', ACFSORP_DIR . trailingslashit( 'admin' ) );

	}

	/**
	 * Loads the translation file.
	 *
	 * @since 1.0
	 */
	function i18n() {

		/* Load the translation of the plugin. */
		load_plugin_textdomain( 'acf-so-related-posts', false, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Loads the initial files needed by the plugin.
	 *
	 * @since 1.3.0
	 */
	function includes() {

		/* Load the plugin functions file. */
		require_once( ACFSORP_INCLUDES . 'functions.php' );
	}

	/**
	 * Loads the admin functions and files.
	 *
	 * @since 1.3.0
	 */
	function admin() {

		/* Only load files if in the WordPress admin. */
		if ( is_admin() ) {

			/* Load the main admin file. */
			require_once( ACFSORP_ADMIN . 'settings.php' );

		}
	}
}

$acfsorp_load = new ACFSORP_Load();

/**
 * Include Aqua Resizer for dynamically resizing images
 *
 * @since 1.0.0
 */
require dirname( __FILE__ ) . '/inc/aq_resizer.php';

/**
 * Register activation/deactivation hooks
 *
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'acfsorp_add_defaults' ); 
register_uninstall_hook( __FILE__, 'acfsorp_delete_plugin_options' );

add_action( 'admin_menu', 'acfsorp_add_options_page' );

function acfsorp_add_options_page() {
	// Add the admin menu and page and save the returned hook suffix
	$hook = add_options_page( 'ACF SO Related Posts Settings', 'ACF SO Related Posts', 'manage_options', __FILE__, 'acfsorp_render_form' );
	// Use the hook suffix to compose the hook and register an action executed when plugin's options page is loaded
	add_action( 'admin_print_styles-' . $hook , 'acfsorp_load_settings_style' );
}


/**
 * Define default option settings
 * @since 1.3.0
 * @modified 1.3.6
 */
function acfsorp_add_defaults() {
	
	$tmp = get_option( 'acfsorp_options' );
	
	if ( ( $tmp['acfsorp_reset'] == '1' ) || ( ! is_array( $tmp ) ) ) {
		
		$arr = array(
			'acfsorp_title' => __( 'Related Posts', 'acf-so-related-posts' ),
			'acfsorp_showthumbs' => '',
			'acfsorp_styling' => '',
			'acfsorp_reset' => ''
		);
		
		update_option( 'acfsorp_options', $arr );
	}
}

/**
 * Delete options table entries ONLY when plugin deactivated AND deleted
 *
 * @since 1.0.0
 */
function acfsorp_delete_plugin_options() {
	
	delete_option( 'acfsorp_options' );
	
}

/**
 * Register and enqueue the settings stylesheet
 *
 * @since 1.0.0
 */
function acfsorp_load_settings_style() {

	wp_register_style( 'custom_acfsorp_settings_css', ACFSORP_URI . 'css/settings.css', false, ACFSORP_VERSION );

	wp_enqueue_style( 'custom_acfsorp_settings_css' );

}

/**
 * Set-up Action and Filter Hooks
 *
 * @since 1.0.0
 */
add_filter( 'plugin_action_links', 'acfsorp_plugin_action_links', 10, 2 );

add_filter ( 'the_content', 'acfsorp_output', 5 );

add_action( 'wp_head', 'acfsorp_styling' );

/**
 * Sanitize and validate input. Accepts an array, return a sanitized array
 *
 * @since 1.0.0
 */
function acfsorp_validate_options($input) {
	// strip html from textboxes
	$input['acfsorp_title'] =  wp_filter_nohtml_kses( $input['acfsorp_title'] ); // Sanitize input (strip html tags, and escape characters)
	$valid_input['acfsorp_showthumbs'] = ( isset( $input['acfsorp_showthumbs'] ) && true == $input['acfsorp_showthumbs'] ? true : false );
	$input['acfsorp_styling'] =  wp_filter_nohtml_kses( $input['acfsorp_styling'] );
	return $input;
}

/**
 * Display a Settings link on the main Plugins page
 *
 * @since 1.0.0
 */
function acfsorp_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		$acfsorp_links = '<a href="' . get_admin_url() . 'options-general.php?page=acf-so-related-posts/acf-so-related-posts.php">' . __( 'Settings', 'acf-so-related-posts' ) . '</a>';
		// make the 'Settings' link appear first
		array_unshift( $links, $acfsorp_links );
	}

	return $links;
}



/*** The End ***/