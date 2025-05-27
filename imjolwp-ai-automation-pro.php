<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/coderjahidul/
 * @since             1.0.0
 * @package           Imjolwp_Ai_Automation_For_Wordpress
 *
 * @wordpress-plugin
 * Plugin Name:       ImjolWP - AI Automation Pro
 * Plugin URI:        https://github.com/coderjahidul/imjolwp-ai-automation-pro
 * Description:       ImjolWP is an AI-powered automation plugin that generates post titles, descriptions, images, summaries, audio, and videos using AI. It supports Elementor, automated scheduling, and a queue system to streamline content creation effortlessly.
 * Version:           1.0.0
 * Author:            Jahidul islam Sabuz
 * Author URI:        https://github.com/coderjahidul//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       imjolwp-ai-automation-pro
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'IMJOLWP_AI_AUTOMATION_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 */
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}
use Imjolwp\Imjolwp_Ai_Automation_For_Wordpress;
use Imjolwp\Imjolwp_Ai_Automation_For_Wordpress_Activator;
use Imjolwp\Imjolwp_Ai_Automation_For_Wordpress_Deactivator;


/**
 * The code that runs during plugin activation.
 */
function imjolwp_ai_automation_for_wordpress_activate() {
	Imjolwp_Ai_Automation_For_Wordpress_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deimjolwp_ai_automation_for_wordpress_activate() {
	Imjolwp_Ai_Automation_For_Wordpress_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'imjolwp_ai_automation_for_wordpress_activate' );
register_deactivation_hook( __FILE__, 'deimjolwp_ai_automation_for_wordpress_activate' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function imjolwp_ai_automation_run() {

	$plugin = new Imjolwp_Ai_Automation_For_Wordpress();
	$plugin->run();

}
imjolwp_ai_automation_run();

// Generate focus keywords function
function generate_focus_keywords($text) {
	$text = strtolower(wp_strip_all_tags($text)); // Convert to lowercase & remove HTML tags
    $text = preg_replace('/[^a-z0-9\s]/', '', $text); // Remove special characters
    $words = explode(' ', $text); // Split into words

    // Common words to ignore
    $stop_words = ['i', 'the', 'and', 'for', 'with', 'a', 'to', 'is', 'on', 'by', 'at', 'it', 'in', 'of', 'as', 'this', 'that'];

    // Filter out common words and select unique keywords
    $keywords = array_diff($words, $stop_words);
    $keywords = array_unique($keywords);

    return implode(' ', array_slice($keywords, 0, 5)); // Limit to 5 keywords
}


add_action('plugins_loaded', function() {
    new \Imjolwp\Automation\Imjolwp_Ai_Automation_For_Wordpress_Automation();
});

// Function to append data to a log file
function put_program_logs( $data ) {

    // Ensure the directory for logs exists
    $directory = __DIR__ . '/program_logs/';
    if ( ! file_exists( $directory ) ) {
        // Use wp_mkdir_p instead of mkdir
        if ( ! wp_mkdir_p( $directory ) ) {
            return "Failed to create directory.";
        }
    }

    // Construct the log file path
    $file_name = $directory . 'program_logs.log';

    // Append the current datetime to the log entry
    $current_datetime = gmdate( 'Y-m-d H:i:s' ); // Use gmdate instead of date
    $data             = $data . ' - ' . $current_datetime;

    // Write the log entry to the file
    if ( file_put_contents( $file_name, $data . "\n\n", FILE_APPEND | LOCK_EX ) !== false ) {
        return "Data appended to file successfully.";
    } else {
        return "Failed to append data to file.";
    }
}

// Function to insert audio before the post content
function insert_audio_before_post_content($content) {
    if (is_single()) {
        global $post;
        $attachment_id = get_post_meta($post->ID, '_audio_attachment_id', true);
        
        if ($attachment_id) {
            $audio_shortcode = wp_audio_shortcode(array('src' => wp_get_attachment_url($attachment_id)));
            $content = $audio_shortcode . $content;
        }
    }
    return $content;
}
add_filter('the_content', 'insert_audio_before_post_content');

// Function to insert video before the post content
function insert_video_before_post_content($content) {
    if (is_single()) {
        global $post;
        $attachment_id = get_post_meta($post->ID, '_video_attachment_id', true);
        
        if ($attachment_id) {
            $video_shortcode = wp_video_shortcode(array('src' => wp_get_attachment_url($attachment_id)));
            $content = $video_shortcode . $content;
        }
    }
    return $content;
}
add_filter('the_content', 'insert_video_before_post_content');

