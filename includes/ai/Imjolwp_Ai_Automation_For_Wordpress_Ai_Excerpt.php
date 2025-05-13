<?php
namespace Imjolwp\Ai;
use Imjolwp\Ai\Imjolwp_Ai_Automation_For_Wordpress_Ai_Curl;
/**
 * Generates AI-powered description for posts.
 *
 * @since 1.0.0
 */
class Imjolwp_Ai_Automation_For_Wordpress_Ai_Excerpt {

    /**
     * Generate AI-powered excerpt for a post.
     *
     * @param string $description The description of the post.
     *
     * @return string The AI-generated excerpt.
     */
    public function generate_excerpt( $description ) {

        // Get the API URL and API Key from the plugin settings
        $api_url = get_option( 'imjolwp_ai_api_url' );
        $api_key = get_option( 'imjolwp_ai_api_key' );
        $model = 'meta-llama/Llama-3.3-70B-Instruct';
        $endpoint = 'openai/chat/completions';
        $max_tokens = 50;

        // Prepare the data to send in the request
        $data = [
            'text' => $description
        ];

        // Instantiate the AI cURL class and make the request
        $curl = new Imjolwp_Ai_Automation_For_Wordpress_Ai_Curl();
        $response = $curl->make_request( $endpoint, $model, $data, $api_url, $api_key, $max_tokens );

        $response = "This is post Excerpt";

        if ( is_wp_error( $response ) ) {
            // Handle error (optional)
            return 'Error generating excerpt';
        }

        // Return the generated excerpt
        return ! empty( $response ) ? $response : 'Default excerpt';
    }
}
