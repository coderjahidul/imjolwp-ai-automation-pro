<?php
namespace Imjolwp\Ai;
use Imjolwp\Ai\Imjolwp_Ai_Automation_For_Wordpress_Ai_Curl;
/**
 * Generates AI-powered description for posts.
 *
 * @since 1.0.0
 */
class Imjolwp_Ai_Automation_For_Wordpress_Ai_Description {

    public function __construct() {
        require_once plugin_dir_path( __FILE__ ) . './Imjolwp_Ai_Automation_For_Wordpress_Ai_Curl.php';
    }

    /**
     * Generate AI-powered description for a post.
     *
     * @param string $title The title of the post.
     * @param int $word_count The desired word count for the description.
     * @param string $language The language for the description.
     * @param string $focus_keywords The focus keywords for the description.
     *
     * @return string The AI-generated description.
     */
    public function generate_description( $title, $word_count, $language, $focus_keywords) {

        // Get the API URL and API Key from the plugin settings
        $api_url = get_option( 'imjolwp_ai_api_url' );
        $api_key = get_option( 'imjolwp_ai_api_key' );
        $model = 'meta-llama/Llama-3.3-70B-Instruct';
        $endpoint = 'openai/chat/completions';
        $max_tokens = 1000;

        $temperature = 0.7;
        $top_p = 0.9;
        $top_k = 0;
        $presence_penalty = 0;
        $frequency_penalty = 0;
        $response_format = 'none';
        $seed = null;

        // Prepare the blog prompt
        $blog_prompt = "Generate a detailed blog post about: '$title'. 
        Structure the response using proper HTML tags, ensuring readability and SEO optimization, and focus on the following keywords: '$focus_keywords'.

        <p><strong>Introduction:</strong> Provide a concise introduction (100-200 words) to the topic, incorporating the focus keywords naturally within the introduction.</p>

        <h2>Main Content</h2>
        <p>Break the content into 5-15 sections, each between 100-200 words, with relevant subheadings that guide the reader through the topic. Ensure the content is informative, engaging, and covers different aspects of the topic in a logical flow. The total word count for the main content should be at least $word_count words. Incorporate the focus keywords naturally throughout the sections to improve SEO without keyword stuffing.</p>

        <h2>Conclusion</h2>
        <p>Summarize the key takeaways of the post in 50-100 words. Provide a final thought or call to action if applicable, ensuring to include the focus keywords.</p>

        <p><strong>Meta Description:</strong> Write a brief, SEO-friendly summary of the post in 150 characters that will appear in search results. Ensure the meta description includes at least one focus keyword.</p>

        <p><strong>Tags:</strong> Provide a comma-separated list of tags (minimum 3) that are relevant to the topic of the blog post, including the focus keywords if appropriate.</p>
        <p><strong>Language:</strong> Please generate the post in the following language: '$language'.</p>
        ";

        // Additional Payload for the API request
        $additional_payload = array(
            'model' => $model,
            'messages' => array(
                array(
                    'role' => 'user',
                    'content' => $blog_prompt
                )
            ),
            'max_tokens' => $max_tokens,
            'temperature' => $temperature,
            'top_p' => $top_p,
            'top_k' => $top_k,
            'presence_penalty' => $presence_penalty,
            'frequency_penalty' => $frequency_penalty,
            'response_format' => $response_format !== 'none' ? $response_format : null
        );

        if (!is_null($seed)) {
            $additional_payload['seed'] = $seed;
        }

        // Instantiate the AI cURL class and make the request
        $curl = new Imjolwp_Ai_Automation_For_Wordpress_Ai_Curl();
        $response = $curl->make_request( $endpoint, $api_url, $api_key, $additional_payload );

        if ( is_wp_error( $response ) ) {
            // Handle error (optional)
            return 'Error generating description';
        }
        // response decode
        $response = json_decode( $response, true );
        // Get the generated description
        $content = $response['choices'][0]['message']['content'] ?? 'Content not found';

        // Return the generated description
        return ! empty( $content ) ? $content : 'Content not found';
    }
}
