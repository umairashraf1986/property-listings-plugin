<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://github.com/umairashraf1986
 * @since      1.0.0
 *
 * @package    Property_Listings_Plugin
 * @subpackage Property_Listings_Plugin/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Property_Listings_Plugin
 * @subpackage Property_Listings_Plugin/includes
 * @author     Umair Ashraf <umair.ashraf1986@gmail.com>
 */
class Property_Listings_Plugin_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
        flush_rewrite_rules();

        // Clear midnight scheduled properties listings email event
        wp_clear_scheduled_hook( 'custom_send_property_email_hook' );
	}

}
