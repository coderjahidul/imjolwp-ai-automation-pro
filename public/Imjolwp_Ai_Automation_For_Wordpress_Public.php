<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/coderjahidul/
 * @since      1.0.0
 *
 * @package    Imjolwp_Ai_Automation_For_Wordpress
 * @subpackage Imjolwp_Ai_Automation_For_Wordpress/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Imjolwp_Ai_Automation_For_Wordpress
 * @subpackage Imjolwp_Ai_Automation_For_Wordpress/public
 * @author     Jahidul islam Sabuz <sobuz0349@gmail.com>
 */
namespace Imjolwp\Public;
class Imjolwp_Ai_Automation_For_Wordpress_Public {

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
		 * defined in Imjolwp_Ai_Automation_For_Wordpress_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Imjolwp_Ai_Automation_For_Wordpress_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


		wp_register_style(
			$this->plugin_name, 
			plugins_url('public/css/imjolwp-ai-automation-public.css', dirname(__FILE__)), 
			array(), 
			$this->version, 
			'all'
		);
		wp_enqueue_style($this->plugin_name);

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
		 * defined in Imjolwp_Ai_Automation_For_Wordpress_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Imjolwp_Ai_Automation_For_Wordpress_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script(
			$this->plugin_name, 
			plugins_url('public/js/imjolwp-ai-automation-public.js', dirname(__FILE__)), 
			array('jquery'), 
			$this->version, 
			true // Load in footer for better performance
		);
		wp_enqueue_script($this->plugin_name);

	}

}
