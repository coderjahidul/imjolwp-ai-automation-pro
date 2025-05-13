<?php 
namespace Imjolwp\Ai;
use Imjolwp\Ai\Imjolwp_Ai_Automation_For_Wordpress_Ai_Curl;
class Imjolwp_Ai_Automation_For_Wordpress_Ai_Audio {
    public function __construct() {
        require_once plugin_dir_path( __FILE__ ) . './Imjolwp_Ai_Automation_For_Wordpress_Ai_Curl.php';
    }

    /**
     * Summary of generate_post_audio
     * @param mixed $main_content
     * @param mixed $post_id
     * @return void
     */
    public function generate_post_audio($main_content, $post_id) {
        $api_url = get_option( 'imjolwp_ai_api_url' );
        $api_key = get_option( 'imjolwp_ai_api_key' );
        $endpoint = 'inference';
        $model = 'hexgrad/Kokoro-82M';

        $additional_payload = array(
            "text" => $main_content,
            "audio_format" => "mp3",
            "voice" => "af_alloy"
        );

        // Instantiate the AI cURL class and make the request
        $curl = new Imjolwp_Ai_Automation_For_Wordpress_Ai_Curl();
        $response = $curl->make_audio_generate_request($endpoint, $api_url, $api_key, $additional_payload, $model);

        $data = json_decode( $response, true );
        if ( isset( $data["audio"] ) ) {
        
            // Extract Base64 string (removing the "data:audio/wav;base64," prefix)
            $base64Audio = explode(',', $data['audio'])[1];
        
            // Decode Base64 into binary audio data
            $audioData = base64_decode($base64Audio);
        
            // Define the filename for the audio file
            $filename = "audio_" . $post_id . ".mp3";
        
            // Get WordPress upload directory
            $upload_dir = wp_upload_dir();
            $file_path = $upload_dir["path"] . '/' . $filename;
            $file_url = $upload_dir["url"] . '/' . $filename;
        
            // Save the audio file
            file_put_contents($file_path, $audioData);
        
            // Check if file exists before proceeding
            if ( file_exists( $file_path ) ) {
                // Prepare an array of file data
                $filetype = wp_check_filetype( $file_path, null );
        
                // Set attachment data
                $attachment = array(
                    'guid'           => $file_url,
                    'post_mime_type' => $filetype['type'],
                    'post_title'     => sanitize_file_name( $filename ),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );
        
                // Insert attachment to WordPress media library
                $attachment_id = wp_insert_attachment( $attachment, $file_path, $post_id );
        
                // Generate attachment metadata and update it
                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                require_once( ABSPATH . 'wp-admin/includes/media.php' );
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
        
                $attach_data = wp_generate_attachment_metadata( $attachment_id, $file_path );
                wp_update_attachment_metadata( $attachment_id, $attach_data );
        
                // Attach audio file to the post
                update_post_meta( $post_id, '_audio_attachment_id', $attachment_id );

                return $attachment_id;
            }
        }
        
    }

}
