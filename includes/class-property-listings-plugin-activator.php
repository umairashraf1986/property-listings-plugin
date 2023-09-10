<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/umairashraf1986
 * @since      1.0.0
 *
 * @package    Property_Listings_Plugin
 * @subpackage Property_Listings_Plugin/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Property_Listings_Plugin
 * @subpackage Property_Listings_Plugin/includes
 * @author     Umair Ashraf <umair.ashraf1986@gmail.com>
 */
class Property_Listings_Plugin_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'property_data';

        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            property_id mediumint(9) NOT NULL,
            bedrooms mediumint(9),
            bathrooms mediumint(9),
            area mediumint(9),
            PRIMARY KEY  (id)
        )";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }

        flush_rewrite_rules();
	}

}
