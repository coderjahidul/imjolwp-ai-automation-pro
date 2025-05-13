<?php
namespace Imjolwp\Ai;

/**
 * Handles HTTP requests for AI-related tasks.
 *
 * @since 1.0.0
 */
class Imjolwp_Ai_Automation_For_Wordpress_Ai_Curl {

    /**
     * Make a HTTP request to the AI API with given endpoint and parameters.
     *
     * @param string $endpoint The API endpoint.
     * @param string $api_url The AI API URL.
     * @param string $api_key The API Key.
     * @param array $additional_payload Additional payload to include in the request.
     *
     * @return mixed The response from the API or false on failure.
     */
    public function make_request($endpoint, $api_url, $api_key, $additional_payload) {
        $response = wp_remote_post( 
            $api_url . '/v1/' . $endpoint, 
            array(
                'method'    => 'POST',
                'body'      => json_encode($additional_payload),
                'headers'   => array(
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $api_key,
                ),
                'timeout'   => 45,  // Adjust the timeout as necessary
                'data_format' => 'body',
            )
        );

        // Check for errors in the response
        if ( is_wp_error( $response ) ) {
            return false;
        }

        // Return the response body
        return wp_remote_retrieve_body( $response );
    }

    /**
     * Make a HTTP request to the AI API for image generation.
     *
     * @param string $endpoint The API endpoint.
     * @param string $api_url The AI API URL.
     * @param string $api_key The API Key.
     * @param array $additional_payload Additional payload to include in the request.
     * @param string $model The model for image generation.
     *
     * @return mixed The response from the API or false on failure.
     */
    public function make_image_generate_request($endpoint, $api_url, $api_key, $additional_payload, $model) {
        $response = wp_remote_post( 
            $api_url . '/v1/' . $endpoint . '/' . $model, 
            array(
                'method'    => 'POST',
                'body'      => json_encode($additional_payload),
                'headers'   => array(
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $api_key,
                ),
                'timeout'   => 45,  // Adjust the timeout as necessary
                'data_format' => 'body',
            )
        );

        // Check for errors in the response
        if ( is_wp_error( $response ) ) {
            return false;
        }

        // Return the response body
        return wp_remote_retrieve_body( $response );
    }

    /**
     * Make a HTTP request to the AI API for audio generation.
     *
     * @param string $endpoint The API endpoint.
     * @param string $api_url The AI API URL.
     * @param string $api_key The API Key.
     * @param array $additional_payload Additional payload to include in the request.
     * @param string $model The model for audio generation.
     *
     * @return mixed The response from the API or false on failure.
     */
    public function make_audio_generate_request($endpoint, $api_url, $api_key, $additional_payload, $model) {
        $response = wp_remote_post(
            $api_url . '/v1/' . $endpoint . '/' . $model,
            array(
                'method'    => 'POST',
                'body'      => json_encode($additional_payload),
                'headers'   => array(
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $api_key,
                ),
                'timeout'   => 45,  // Adjust the timeout as necessary
                'data_format' => 'body',
            )
        );
    
        // Check for errors in the response
        if (is_wp_error($response)) {
            return false;
        }
    
        // Return the response body
        return wp_remote_retrieve_body($response);
    }
    
}
