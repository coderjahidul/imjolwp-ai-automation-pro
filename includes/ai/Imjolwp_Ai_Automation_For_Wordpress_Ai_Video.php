<?php 
namespace Imjolwp\Ai;
use Imjolwp\Ai\Imjolwp_Ai_Automation_For_Wordpress_Ai_Curl;
/**
 * Generates AI-powered description for posts.
 *
 * @since 1.0.0
 */

Class Imjolwp_Ai_Automation_For_Wordpress_Ai_Video{
    public function __construct(){
        require_once plugin_dir_path( __FILE__ ) . './Imjolwp_Ai_Automation_For_Wordpress_Ai_Curl.php';
    }

    /**
     * Summary of generate_post_video
     * @param mixed $main_content
     * @param mixed $post_id
     * @return void
     */
    public function generate_post_video($main_content, $post_id){
        $api_url = get_option( 'imjolwp_ai_api_url' );
        $api_key = get_option( 'imjolwp_ai_api_key' );
        $endpoint = 'inference';
        $model = 'Wan-AI/Wan2.1-T2V-1.3B';

        $additional_payload = array(
            "prompt" => $main_content,
            "num_frames" => 24,
            "fps" => 8,
            "guidance_scale" => 12.5,
            "seed" => 42,
            "height" => 512,
            "width" => 512,
            "negative_prompt" => "",
            "duration" => 3,
            "output_format" => "mp4"
        );

        // Instantiate the AI cURL class and make the request
        $curl = new Imjolwp_Ai_Automation_For_Wordpress_Ai_Curl();
        $response = $curl->make_video_generate_request($endpoint, $api_url, $api_key, $additional_payload, $model);

        $data = json_decode( $response, true );
        if(isset($data["video_url"])){
            
            // Extract Base64 string (removing the "data:video/wav;base64," prefix)
            $base64Video = explode(',', $data['video_url'])[1];

            // Decode Base64 into binary video data
            $videoData = base64_decode($base64Video);

            // Generate a unique filename for the video file
            $filename = 'video_' . $post_id . '.mp4';

            // Save the video data to a file in the uploads directory
            $upload_dir = wp_upload_dir();
            $file_path = $upload_dir['path'] . '/' . $filename;
            $file_url = $upload_dir['url'] . '/' . $filename;
            file_put_contents($file_path, $videoData);

            // Check if the file was saved
            if (!file_exists($file_path)) {
                return false;
            }

            // Get file type
            $filetype = wp_check_filetype($file_path, null);

            // Create the attachment array
            $attachment = array(
                'guid'           => $file_url,
                'post_mime_type' => $filetype['type'],
                'post_title'     => sanitize_file_name($filename),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );

            // Include necessary WordPress files for media handling
            require_once ABSPATH . 'wp-admin/includes/image.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';

            // Insert attachment to media library
            $attachment_id = wp_insert_attachment($attachment, $file_path, $post_id);

            // Generate attachment metadata
            $attach_data = wp_generate_attachment_metadata($attachment_id, $file_path);
            wp_update_attachment_metadata($attachment_id, $attach_data);

            // Optionally save video attachment ID in post meta
            update_post_meta($post_id, '_video_attachment_id', $attachment_id);

            return $attachment_id;
        }

    }
}