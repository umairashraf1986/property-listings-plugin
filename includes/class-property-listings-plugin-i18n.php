<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/umairashraf1986
 * @since      1.0.0
 *
 * @package    Property_Listings_Plugin
 * @subpackage Property_Listings_Plugin/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Property_Listings_Plugin
 * @subpackage Property_Listings_Plugin/includes
 * @author     Umair Ashraf <umair.ashraf1986@gmail.com>
 */
class Property_Listings_Plugin_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'property-listings-plugin',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
