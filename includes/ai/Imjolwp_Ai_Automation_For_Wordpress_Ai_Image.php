<?php
namespace Imjolwp\Ai;
use Imjolwp\Ai\Imjolwp_Ai_Automation_For_Wordpress_Ai_Curl;
class Imjolwp_Ai_Automation_For_Wordpress_Ai_Image {

    public function __construct() {
        require_once plugin_dir_path( __FILE__ ) . './Imjolwp_Ai_Automation_For_Wordpress_Ai_Curl.php';
    }

    /**
     * Generates an AI-based image URL from the post title.
     *
     * @param string $title The post title.
     * @param int $post_id The post ID.
     * @return string|null The generated image URL or null on failure.
     */
    public function generate_image( $title, $post_id ) {
        // Example AI API URL (Replace with your actual AI image API)
        $api_url = get_option( 'imjolwp_ai_api_url' );
        $api_key = get_option( 'imjolwp_ai_api_key' );
        $model = 'stabilityai/sd3.5';
        $endpoint = 'inference';
        $negative_prompt = "";
        $num_images = 1;
        $num_inference_steps = 35;
        $aspect_ratio = "1:1"; 
        $guidance_scale = 7;
        $seed = null;

        // Prepare the image prompt
        $image_prompt = "Generate a detailed image based on the following description: '$title'. Structure the response with high quality and cinematic lighting, ensuring clarity and realism.";

        // Additional Payload for image generation
        $additional_payload = array(
            'prompt' => $image_prompt,
            'negative_prompt' => $negative_prompt,
            'num_images' => $num_images,
            'num_inference_steps' => $num_inference_steps,
            'aspect_ratio' => $aspect_ratio,
            'guidance_scale' => $guidance_scale,
        );

        if (!is_null($seed)) {
            $additional_payload['seed'] = $seed;
        }

        // Instantiate the AI cURL class and make the request
        $curl = new Imjolwp_Ai_Automation_For_Wordpress_Ai_Curl();
        $response = $curl->make_image_generate_request( $endpoint, $api_url, $api_key, $additional_payload, $model);

        // No need for wp_remote_retrieve_body() here
        $data = json_decode($response, true);

        // Check if decoding was successful
        if (json_last_error() !== JSON_ERROR_NONE) {
            die(esc_html__("JSON decode error: ", "imjolwp-ai-automation") . esc_html(json_last_error_msg()));
        }


        // Check if the response contains an image
        if (isset($data['images']) && count($data['images']) > 0) {
            $base64_image = $data['images'][0]; // Get the first image
        } else {
            die("No image received from API.");
        }
        // Save the image to the WordPress Media Library
        $upload_dir = wp_upload_dir();
        $base64String = preg_replace('/^data:image\/\w+;base64,/', '', $base64_image);
        $image_data = base64_decode($base64String);
        $file_name = 'ai_image_' . time() . '.png'; // Change the extension if needed
        $file_path = $upload_dir['path'] . '/' . $file_name;
        

        file_put_contents($file_path, $image_data);

        // Create attachment
        $wp_filetype = wp_check_filetype($file_name, null );
        $attachment = array(
            'guid' => $upload_dir['url'] . '/' . $file_name,
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => sanitize_file_name($file_name),
            'post_content' => '',
            'post_status' => 'inherit'
        );

        $attachment_id = wp_insert_attachment($attachment, $file_path, $post_id);

        // Generate the attachment metadata
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attachment_metadata = wp_generate_attachment_metadata($attachment_id, $file_path);
        wp_update_attachment_metadata($attachment_id, $attachment_metadata);

        // Set the post's featured image
        set_post_thumbnail($post_id, $attachment_id);
    }
}
