<?php
namespace Imjolwp\Admin;
use Imjolwp\Admin\Settings\Imjolwp_Ai_Automation_For_Wordpress_Settings;
use Imjolwp\Admin\Settings\Imjolwp_Ai_Automation_For_Wordpress_Dashboard;
use Imjolwp\Admin\Partials\Imjolwp_Ai_Automation_For_Wordpress_Admin_Display;
use Imjolwp\Admin\Settings\Imjolwp_Ai_Automation_For_Wordpress_Scheduled_Post_list;
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/coderjahidul/
 * @since      1.0.0
 *
 * @package    Imjolwp_Ai_Automation_For_Wordpress
 * @subpackage Imjolwp_Ai_Automation_For_Wordpress/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Imjolwp_Ai_Automation_For_Wordpress
 * @subpackage Imjolwp_Ai_Automation_For_Wordpress/admin
 * @author     Jahidul islam Sabuz <sobuz0349@gmail.com>
 */
class Imjolwp_Ai_Automation_For_Wordpress_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Hook to add the admin menu
		add_action('admin_menu', array($this, 'add_admin_menu'));

		// Register settings and fields
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Include the dashboard class file here
		require_once plugin_dir_path( __FILE__ ) . 'settings/Imjolwp_Ai_Automation_For_Wordpress_Dashboard.php';

		require_once plugin_dir_path( __FILE__ ) . 'settings/Imjolwp_Ai_Automation_For_Wordpress_Scheduled_Post_list.php';

		require_once plugin_dir_path( __FILE__ ) . 'partials/Imjolwp_Ai_Automation_For_Wordpress_Admin_Display.php';

		require_once plugin_dir_path( __FILE__ ) . 'settings/Imjolwp_Ai_Automation_For_Wordpress_Settings.php';
	}

	public function display_settings_page() {
		// Load the settings page
		$settings_page = new Imjolwp_Ai_Automation_For_Wordpress_Settings();
		$settings_page->display_settings_page();  // Ensure this method is defined in your Settings_page class to render the page
	}

	public function display_admin_dashboard_page() {
		// Load the dashboard page
		$dashboard_page = new Imjolwp_Ai_Automation_For_Wordpress_Dashboard();
		$dashboard_page->display_dashboard_page(); // Ensure this method is defined in your Dashboard_page class to render the page
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Imjolwp_Ai_Automation_For_Wordpress_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Imjolwp_Ai_Automation_For_Wordpress_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_style(
			$this->plugin_name, 
			plugins_url('admin/css/imjolwp-ai-automation-admin.css', dirname(__FILE__)), 
			array(), 
			$this->version, 
			'all'
		);
		wp_enqueue_style($this->plugin_name);

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Imjolwp_Ai_Automation_For_Wordpress_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Imjolwp_Ai_Automation_For_Wordpress_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script(
			$this->plugin_name, 
			plugins_url('admin/js/imjolwp-ai-automation-admin.js', dirname(__FILE__)), 
			array('jquery'), 
			$this->version, 
			true // Load in footer for better performance
		);
		wp_enqueue_script($this->plugin_name);

	}

	/**
	 * Add an admin menu for the plugin.
	 *
	 * @since 1.0.0
	 */
	public function add_admin_menu() {
		add_menu_page(
			'ImjolWP AI Automation',  // Page title
			'ImjolWP AI',             // Menu title
			'manage_options',         // Capability
			'imjolwp-ai-dashboard',   // Menu slug
			array($this, 'display_admin_dashboard_page'), // Callback function
			'dashicons-art',          // Dashicon icon
			25                        // Position
		);

		add_submenu_page(
			'imjolwp-ai-dashboard',
			'Post Scheduler List',
			'Post Scheduler List',
			'manage_options',
			'imjolwp-ai-scheduled-posts',
			array($this, 'imjolwp_ai_scheduled_posts_page')
		);

		// add_menu_page(
		// 	'Scheduled AI Posts',
		// 	'AI Scheduled Posts',
		// 	'manage_options',
		// 	'imjolwp-ai-scheduled-posts',
		// 	'imjolwp_ai_scheduled_posts_page',
		// 	'dashicons-schedule',
		// 	25
		// );

		add_submenu_page(
            'imjolwp-ai-dashboard',
            'AI Post Generator',
            'AI Post Generator',
            'manage_options',
            'ai-post-generator',
            array($this, 'ai_post_generator_page')
        );

		add_submenu_page(
			'imjolwp-ai-dashboard',
			'Settings',
			'Settings',
			'manage_options',
			'imjolwp-ai-settings',
			array($this, 'display_settings_page')
		);
	}

	/**
	 * Register settings and add fields for API URL and API Key.
	 *
	 * @since 1.0.0
	 */
	public function register_settings() {
		// Register settings group and individual fields
		register_setting(
			'imjolwp_ai_options_group', // Options group
			'imjolwp_ai_api_url',       // Option name for API URL
			'sanitize_text_field'       // Sanitize callback for API URL
		);

		register_setting(
			'imjolwp_ai_options_group', // Options group
			'imjolwp_ai_api_key',       // Option name for API Key
			'sanitize_text_field'       // Sanitize callback for API Key
		);

		// Add the section to settings page
		add_settings_section(
			'imjolwp_ai_settings_section',  // Section ID
			'API Configuration',            // Section Title
			null,                           // Callback for description (null for no description)
			'imjolwp-ai-settings'           // Settings page slug
		);

		// Add fields to the section
		add_settings_field(
			'imjolwp_ai_api_url_field',     // Field ID
			'API URL',                      // Field label
			array( $this, 'display_api_url_field' ), // Callback function to display the field
			'imjolwp-ai-settings',          // Settings page slug
			'imjolwp_ai_settings_section'   // Section ID
		);

		add_settings_field(
			'imjolwp_ai_api_key_field',     // Field ID
			'API Key',                      // Field label
			array( $this, 'display_api_key_field' ), // Callback function to display the field
			'imjolwp-ai-settings',          // Settings page slug
			'imjolwp_ai_settings_section'   // Section ID
		);
	}

	/**
	 * Display the input field for API URL.
	 *
	 * @since 1.0.0
	 */
	public function display_api_url_field() {
		$api_url = get_option( 'imjolwp_ai_api_url' ); // Get the current saved API URL
		echo "<label for='imjolwp_ai_api_url'><strong>Deepinfra API URL:</strong></label><br>";
		echo "<input type='text' id='imjolwp_ai_api_url' placeholder='Enter Deepinfra API URL' name='imjolwp_ai_api_url' value='" . esc_attr( $api_url ) . "' class='regular-text' />";
		// Example
		echo "<p>Example: https://api.deepinfra.com</p>";
	}

	/**
	 * Display the input field for API Key.
	 *
	 * @since 1.0.0
	 */
	public function display_api_key_field() {
		$api_key = get_option( 'imjolwp_ai_api_key' ); // Get the current saved API Key
		echo "<label for='imjolwp_ai_api_key'><strong>Deepinfra API Key:</strong></label><br>";
		echo "<input type='password' id='imjolwp_ai_api_key' placeholder='Enter Deepinfra API Key' name='imjolwp_ai_api_key' value='" . esc_attr( $api_key ) . "' class='regular-text' />";

		// Example
		echo "<p>Example: 1w23w4w56w78e9r0</p>";
	}




	public function register_ajax_handler() {
		add_action('wp_ajax_toggle_ai_feature', [$this, 'toggle_ai_feature']);
	}

	public function toggle_ai_feature() {
		check_ajax_referer('toggle_ai_feature_nonce');

		if (!current_user_can('manage_options')) {
			wp_die(esc_html__('Nonce verification failed.', 'imjolwp-ai-automation-pro'));
		}

		$feature = isset($_POST['feature']) ? sanitize_text_field(wp_unslash($_POST['feature'])) : '';
		$status = isset($_POST['status']) ? sanitize_text_field(wp_unslash($_POST['status'])) : '0';


		if (!empty($feature)) {
			update_option($feature, $status);
			wp_send_json_success(['message' => __('Setting updated.', 'imjolwp-ai-automation-pro')]);
		} else {
			wp_send_json_error(['message' => __('Invalid feature.', 'imjolwp-ai-automation-pro')]);
		}
	}

	// AI Post Generator Submenu Page
    public function ai_post_generator_page() {
        // Ensure the correct namespace is used when instantiating the class
        $ai_post_generator = new Imjolwp_Ai_Automation_For_Wordpress_Admin_Display();
        $ai_post_generator->display_settings_page();
    }

	public function imjolwp_ai_scheduled_posts_page(){
		$post_event_scheduled_list = new Imjolwp_Ai_Automation_For_Wordpress_Scheduled_Post_list();
		$post_event_scheduled_list->imjolwp_ai_scheduled_events_list();
	}
}
