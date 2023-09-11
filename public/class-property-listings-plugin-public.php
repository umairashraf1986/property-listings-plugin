<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/umairashraf1986
 * @since      1.0.0
 *
 * @package    Property_Listings_Plugin
 * @subpackage Property_Listings_Plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Property_Listings_Plugin
 * @subpackage Property_Listings_Plugin/public
 * @author     Umair Ashraf <umair.ashraf1986@gmail.com>
 */
class Property_Listings_Plugin_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Property_Listings_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Property_Listings_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/property-listings-plugin-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Property_Listings_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Property_Listings_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/property-listings-plugin-public.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * Register Custom Post Type 'Property'.
     *
     * @since    1.0.0
     */
	public function custom_register_property_post_type() {
        // Set UI labels for Custom Post Type
        $labels = array(
            'name'                => _x( 'Property', 'Post Type General Name', 'property-listings-plugin' ),
            'singular_name'       => _x( 'Property', 'Post Type Singular Name', 'property-listings-plugin' ),
            'menu_name'           => __( 'Property', 'property-listings-plugin' ),
            'parent_item_colon'   => __( 'Parent Property', 'property-listings-plugin' ),
            'all_items'           => __( 'All Property', 'property-listings-plugin' ),
            'view_item'           => __( 'View Property', 'property-listings-plugin' ),
            'add_new_item'        => __( 'Add New Property', 'property-listings-plugin' ),
            'add_new'             => __( 'Add New', 'property-listings-plugin' ),
            'edit_item'           => __( 'Edit Property', 'property-listings-plugin' ),
            'update_item'         => __( 'Update Property', 'property-listings-plugin' ),
            'search_items'        => __( 'Search Property', 'property-listings-plugin' ),
            'not_found'           => __( 'Not Found', 'property-listings-plugin' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'property-listings-plugin' ),
        );

        // Set other options for Custom Post Type

        $args = array(
            'label'               => __( 'Property', 'property-listings-plugin' ),
            'description'         => __( 'Custom post type to add properties', 'property-listings-plugin' ),
            'labels'              => $labels,
            // Features this CPT supports in Post Editor
            'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
            // You can associate this CPT with a taxonomy or custom taxonomy.
            'taxonomies'          => array( '' ),
            /* A hierarchical CPT is like Pages and can have
            * Parent and child items. A non-hierarchical CPT
            * is like Posts.
            */
            'menu_icon'           => 'dashicons-admin-home',
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'show_in_rest'        => true,

        );

        // Registering your Custom Post Type
        register_post_type( 'property', $args );
    }

    /**
     * Register CRON job to send email of new property listings every midnight.
     *
     * @since    1.0.0
     */
    public function custom_schedule_property_email() {
        date_default_timezone_set('Asia/Karachi');
        if (!wp_next_scheduled('custom_send_property_email')) {
            wp_schedule_event(strtotime('midnight'), 'daily', 'custom_send_property_email');
        }
    }

    /**
     * Send email to members every midnight with new properties created in the previous day.
     *
     * @since    1.0.0
     */
    public function custom_send_property_email() {
        global $wpdb;

        // Get properties listed on the previous day
        $yesterday_midnight = strtotime('yesterday midnight');
        $today_midnight = strtotime('today midnight');
        $args = array(
            'post_type' => 'property',
            'post_status' => 'publish',
            'date_query' => array(
                'after' => date('Y-m-d H:i:s', $yesterday_midnight),
                'before' => date('Y-m-d H:i:s', $today_midnight),
            ),
        );
        $new_properties = new WP_Query($args);

        if ($new_properties->have_posts()) {
            $subject = 'New Property Listings';
            $message = 'New properties listed on the previous day:' . PHP_EOL;

            while ($new_properties->have_posts()) {
                $new_properties->the_post();
                $property_title = get_the_title();
                $property_link = get_permalink();

                $message .= "- $property_title: $property_link" . PHP_EOL;
            }

            // Send email to all users in BCC
            $users = get_users();
            $bcc = array();
            foreach ($users as $user) {
                $bcc[] = $user->user_email;
            }

            $headers = array(
                'Content-Type: text/html; charset=UTF-8',
                'Bcc: ' . implode(',', $bcc), // Add all users to BCC
            );

            wp_mail('', $subject, $message, $headers); // Leave the 'to' field empty to send BCC
        }

        wp_reset_postdata();
    }

    /**
     * Register Metaboxes for Property Post Type.
     *
     * @since    1.0.0
     */
    public function custom_register_property_metaboxes() {
        add_meta_box(
            'property_details_metabox',
            'Property Details',
            array($this, 'custom_property_details_metabox_callback'),
            'property',
            'normal',
            'default'
        );
    }

    /**
     * Callback function for Property Details Metabox.
     *
     * @since    1.0.0
     */
    public function custom_property_details_metabox_callback($post) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'property_data';

        // Retrieve existing values from the 'property_data' table
        $property_id = $post->ID;
        $property_data = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM $table_name WHERE property_id = %d", $property_id)
        );

        // Set default values
        $bedrooms = '';
        $bathrooms = '';
        $area = '';

        if ($property_data) {
            $bedrooms = $property_data->bedrooms;
            $bathrooms = $property_data->bathrooms;
            $area = $property_data->area;
        }
        ?>

        <label for="bedrooms">Number of Bedrooms:</label>
        <input type="number" id="bedrooms" name="bedrooms" value="<?php echo esc_attr($bedrooms); ?>"><br>

        <label for="bathrooms">Number of Bathrooms:</label>
        <input type="number" id="bathrooms" name="bathrooms" value="<?php echo esc_attr($bathrooms); ?>"><br>

        <label for="area">Area (sq. ft.):</label>
        <input type="text" id="area" name="area" value="<?php echo esc_attr($area); ?>"><br>

        <?php
    }

    /**
     * Save Metabox Data into 'property_data' table.
     *
     * @since    1.0.0
     */
    public function custom_save_property_metabox_data($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if ($post_id && 'property' === get_post_type($post_id)) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'property_data';

            $bedrooms = sanitize_text_field($_POST['bedrooms']);
            $bathrooms = sanitize_text_field($_POST['bathrooms']);
            $area = sanitize_text_field($_POST['area']);

            // Check if data already exists for this property
            $existing_data = $wpdb->get_row(
                $wpdb->prepare("SELECT * FROM $table_name WHERE property_id = %d", $post_id)
            );

            if ($existing_data) {
                // Update existing data
                $wpdb->update(
                    $table_name,
                    array(
                        'bedrooms' => $bedrooms,
                        'bathrooms' => $bathrooms,
                        'area' => $area,
                    ),
                    array('property_id' => $post_id)
                );
            } else {
                // Insert new data
                $wpdb->insert(
                    $table_name,
                    array(
                        'property_id' => $post_id,
                        'bedrooms' => $bedrooms,
                        'bathrooms' => $bathrooms,
                        'area' => $area,
                    )
                );
            }
        }
    }

}
