<?php
/**
 * Admin Display Class for AI Post Generator
 * 
 * @package Imjolwp_Ai_Automation
 * @subpackage Admin
 */
namespace Imjolwp\Admin\Partials;

use Imjolwp\Automation\Imjolwp_Ai_Automation_For_Wordpress_Automation;
use Imjolwp\Automation\Imjolwp_Ai_Automation_For_Wordpress_Queue;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Class Imjolwp_Ai_Automation_For_Wordpress_Admin_Display
 * Handles admin settings display for AI post generation.
 */

class Imjolwp_Ai_Automation_For_Wordpress_Admin_Display {

    /**
     * Display the AI Post Generator settings page.
     */

    public function display_settings_page() {
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline">AI Post Generator</h1>

            <form method="post" action="" class="ai-content-form">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="post_title">Post Title</label></th>
                        <td><input type="text" id="post_title" name="post_title" class="regular-text" placeholder="Enter post title"></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="focus_keywords">Focus Keywords</label></th>
                        <td><input type="text" id="focus_keywords" name="focus_keywords" class="regular-text" placeholder="Enter Focus Keywords Separated by commas"></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="min_word_count">Minimum Word Count</label></th>
                        <td><input type="number" id="min_word_count" name="min_word_count" class="small-text" value="500"></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="language">Language</label></th>
                        <td>
                            <select id="language" name="language" class="regular-select">
                                <option value="en">English</option>
                                <option value="es">Spanish</option>
                                <option value="fr">French</option>
                                <option value="de">German</option>
                                <option value="bn">বাংলা (Bangla)</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="post_status">Post Status</label></th>
                        <td>
                            <select id="post_status" name="post_status" class="regular-select">
                                <option value="draft">Draft</option>
                                <option value="publish">Published</option>
                                <option value="pending">Pending Review</option>
                                <option value="private">Private</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="post_types">Post Type</label></th>
                        <td>
                            <select id="post_types" name="post_types" class="regular-select">
                            <?php
                                $post_types = get_post_types(['public' => true], 'objects');
                                foreach ($post_types as $post_type) {
                                    echo '<option value="' . esc_attr($post_type->name) . '">' . esc_html($post_type->labels->singular_name) . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="schedule_automation">Schedule Automation</label></th>
                        <td>
                            <input type="checkbox" id="schedule_automation" name="schedule_automation" value="1">
                            <label for="schedule_automation">Enable Scheduled Post Generation</label>
                            <br><br>
                            <label for="schedule_time">Select Time:</label>
                            <input type="datetime-local" id="schedule_time" name="schedule_time">
                        </td>
                    </tr>

                </table>

                <?php submit_button('Generate Content', 'primary', 'generate_content'); ?>
            </form>

            <?php
            // Handle form submission securely.
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_title']) && !empty($_POST['post_title'])) {
                $title = sanitize_text_field($_POST['post_title']);
                // Get related words separated by commas
                $focus_keywords = sanitize_text_field($_POST['focus_keywords']);
                $word_count = intval($_POST['min_word_count']);
                $language = sanitize_text_field($_POST['language']);
                $post_status = sanitize_text_field($_POST['post_status']);
                $post_type = sanitize_text_field($_POST['post_types']);
                $schedule_automation = isset($_POST['schedule_automation']) ? true : false;
                $schedule_time = isset($_POST['schedule_time']) ? sanitize_text_field($_POST['schedule_time']) : '';
                $author_id = get_current_user_id();

                // If schedule automation is enabled, schedule the AI content generation.
                if ($schedule_automation && !empty($schedule_time)) {
                    $automation = new Imjolwp_Ai_Automation_For_Wordpress_Automation();
                    $automation->schedule_ai_content_generation($title, $word_count, $language, $focus_keywords, $post_status, $post_type, $author_id, $schedule_time);
                } else {
                    $queue = new Imjolwp_Ai_Automation_For_Wordpress_Queue();
                    $queue->queue_ai_content_generation($title, $word_count, $language, $focus_keywords, $post_status, $post_type, $author_id);
                }
            }
            ?>

        </div>

        <?php
    }
}
