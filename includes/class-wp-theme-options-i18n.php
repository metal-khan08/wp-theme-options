<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       //allmarketingsolutions.co.uk
 * @since      1.0.0
 *
 * @package    Wp_Theme_Options
 * @subpackage Wp_Theme_Options/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Theme_Options
 * @subpackage Wp_Theme_Options/includes
 * @author     All Marketing Solutions <help@allmarketingsolutions.co.uk>
 */
class Wp_Theme_Options_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-theme-options',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
