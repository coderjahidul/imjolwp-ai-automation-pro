<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/coderjahidul/
 * @since      1.0.0
 *
 * @package    Imjolwp_Ai_Automation_For_Wordpress
 * @subpackage Imjolwp_Ai_Automation_For_Wordpress/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Imjolwp_Ai_Automation_For_Wordpress
 * @subpackage Imjolwp_Ai_Automation_For_Wordpress/includes
 * @author     Jahidul islam Sabuz <sobuz0349@gmail.com>
 */
namespace Imjolwp;
class Imjolwp_Ai_Automation_For_Wordpress_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'imjolwp-ai-automation-pro',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
