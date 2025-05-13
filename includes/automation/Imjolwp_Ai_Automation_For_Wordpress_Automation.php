<?php
/**
 * AI Content Automation Class
 *
 * @package Imjolwp\Automation
 */

namespace Imjolwp\Automation;
use Imjolwp\Ai\Imjolwp_Ai_Automation_For_Wordpress_Ai_Description;
use Imjolwp\Ai\Imjolwp_Ai_Automation_For_Wordpress_Ai_Image;
use Imjolwp\Ai\Imjolwp_Ai_Automation_For_Wordpress_Ai_Audio;
use Imjolwp\Ai\Imjolwp_Ai_Automation_For_Wordpress_Ai_Video;

class Imjolwp_Ai_Automation_For_Wordpress_Automation {
    public function __construct() {
        add_action('ai_content_generate_event', [$this, 'generate_scheduled_content'], 10, 9);
        require_once plugin_dir_path( __FILE__ ) . '../ai/Imjolwp_Ai_Automation_For_Wordpress_Ai_Description.php';
        require_once plugin_dir_path( __FILE__ ) . '../ai/Imjolwp_Ai_Automation_For_Wordpress_Ai_Image.php';
        require_once plugin_dir_path( __FILE__ ) . '../ai/Imjolwp_Ai_Automation_For_Wordpress_Ai_Audio.php';
        require_once plugin_dir_path( __FILE__ ) . '../ai/Imjolwp_Ai_Automation_For_Wordpress_Ai_Video.php';
    }

    /**
     * Schedule AI content generation.
     *
     * @param string $title
     * @param int $word_count
     * @param string $language
     * @param string $focus_keywords
     * @param string $post_status
     * @param string $post_type
     * @param int $author_id
     * @param string $schedule_time
     */

    public function schedule_ai_content_generation($title, $word_count, $language, $focus_keywords, $post_status, $post_type, $author_id, $schedule_time) {
        // Schedule the task - 6 hours from now
        $timestamp = strtotime($schedule_time) - 6 * 60 * 60;
        if ($timestamp) {
            wp_schedule_single_event($timestamp, 'ai_content_generate_event', [$title, $word_count, $language, $focus_keywords, $post_status, $post_type, $author_id]);
            echo '<div class="updated"><p>AI Content Generation Scheduled!</p></div>';
        }
    }

    /**
     * Generate AI-generated content and create a WordPress post.
     *
     * @param string $title
     * @param int $word_count
     * @param string $language
     * @param string $focus_keywords
     * @param string $post_status
     * @param string $post_type
     * @param int $author_id
     */

    public function generate_scheduled_content($title, $word_count, $language, $focus_keywords, $post_status, $post_type, $author_id) {
        // Call the generate_description function
        $generated_content = new Imjolwp_Ai_Automation_For_Wordpress_Ai_Description();
        $generated_content = $generated_content->generate_description($title, $word_count, $language, $focus_keywords);

        if(get_option('ai_post_description') == 1){
            // Extract content between <p><strong>Introduction:</strong></p>
            preg_match('/<p><strong>Introduction:<\/strong>(.*?)<\/p>/s', $generated_content, $matches);

            // Store the extracted content in $introduction
            $main_content = "<p><strong>Introduction:</strong> " . (isset($matches[1]) ? trim($matches[1]) : '') . "</p>";

            // Extract content between <h2>Main Content</h2> and <h2>Conclusion</h2>
            preg_match('/<h2>Main Content<\/h2>(.*?)<h2>Conclusion<\/h2>/s', $generated_content, $matches);

            // Store the extracted content in $main_content
            $main_content .= isset($matches[1]) ? trim($matches[1]) : '';
        }else{
            $main_content = '';
        }

        // Generate AI Title if enabled.
        if(get_option('ai_post_title') == 1){
            // Call the Post Title function
            preg_match('/<h1>(.*?)<\/h1>/', $generated_content, $matches);
            $title = isset($matches[1]) ? trim($matches[1]) : 'Default Title';
        }else{
            $title = $title;
        }

        // Generate AI Excerpt if enabled.
        if(get_option('ai_post_seo_meta_description') == 1){
            // Extract content between <p><strong>Meta Description:</strong></p>
            preg_match('/<p><strong>Meta Description:<\/strong>(.*?)<\/p>/s', $generated_content, $matches);
            $post_meta_description = isset($matches[1]) ? trim($matches[1]) : '';
        }else {
            $post_meta_description = '';
        }

        // Save the AI-generated content as a post
        $post_id = wp_insert_post([
            'post_title'   => $title,
            'post_content' => $main_content,
            'post_status'  => $post_status,
            'post_type'    => $post_type,
            'post_author'  => $author_id
        ]);

        // Generate Focus Keywords
        if ($post_id) {
            // Generate focus keywords
            $focus_keywords = generate_focus_keywords($focus_keywords);

            // Save Focus Keywords in Yoast SEO
            update_post_meta($post_id, '_yoast_wpseo_focuskw', $focus_keywords);
        }

        // Generate Meta Description
        if ($post_id) {
            // Save Yoast SEO meta description
            update_post_meta($post_id, '_yoast_wpseo_metadesc', $post_meta_description);
        
            // Save Yoast SEO Facebook description
            update_post_meta($post_id, '_yoast_wpseo_opengraph-description', $post_meta_description);
        
            // Save Yoast SEO Twitter description
            update_post_meta($post_id, '_yoast_wpseo_twitter-description', $post_meta_description);
        }

        // enable tags Generate using ai
        if(get_option('ai_post_tags') == 1){
            // Call the post_tags_function
            preg_match('/<strong>Tags:<\/strong>(.*)/', $generated_content, $matches);
            // Apply str_replace to modify the tags part
            if (isset($matches[1])) {
                // Split the tags into an array using a comma as the delimiter
                $tags_array = explode(', ', $matches[1]);

                // Rebuild the modified tags part in the HTML content
                str_replace($matches[1], implode(', ', $tags_array), $generated_content);
            }
        }else{
            $tags_array = null;
        }

        // Set audio file
        if(get_option('ai_post_audio') == 1) {
            $set_post_audio = new Imjolwp_Ai_Automation_For_Wordpress_Ai_Audio();
            $set_post_audio->generate_post_audio($main_content, $post_id);
        }else{
            $set_post_audio = null;
        }

        // Set video file
        if(get_option('ai_post_video') == 1) {
            $set_post_video = new Imjolwp_Ai_Automation_For_Wordpress_Ai_Video();
            $set_post_video->generate_post_video($main_content, $post_id);
        }else{
            $set_post_video = null;
        }

        // Set post tags (this is handled separately)
        if (get_option('ai_post_tags') == 1 && !empty($tags_array)) {
            wp_set_post_tags($post_id, $tags_array);
        }

        // Set featured image
        if(get_option('ai_post_image') == 1) {
            $set_featured_image = new Imjolwp_Ai_Automation_For_Wordpress_Ai_Image();
            $set_featured_image->generate_image($title, $post_id);
        }

        if (is_wp_error($post_id)) {
            // Log error if post creation fails
            error_log('AI Content Generation Error: ' . $post_id->get_error_message());
        } else {
            // Log success
            error_log('AI Content Successfully Generated: Post ID ' . $post_id);

            // Optionally, send an email notification
            $admin_email = get_option('admin_email');
            $post_edit_link = get_edit_post_link($post_id);
            $message = "A new AI-generated post has been created.\n\nTitle: {$title}\n\nView/Edit: {$post_edit_link}";

            wp_mail($admin_email, 'New AI-Generated Post Created', $message);
        }
    }
    
}

