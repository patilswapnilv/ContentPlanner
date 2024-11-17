<?php
/**
 * API Handler Class
 *
 * Manages requests to external APIs and handles authentication and caching.
 *
 * @package Content_Planner
 */

class Content_Planner_API_Handler {

    /**
     * Initialize API handler with necessary actions and hooks.
     */
    public static function init() {
        // Hook into init or plugins_loaded if any specific setup is required.
    }

    /**
     * Get the API key for a specific service from settings.
     *
     * @param string $service Service name, such as 'google_trends', 'search_console', 'analytics', 'openai'.
     * @return string|null API key for the requested service.
     */
    private static function get_api_key( $service ) {
        $options = get_option( 'content_planner_options' );
        return $options[ $service . '_api_key' ] ?? null;
    }

    /**
     * Send a request to a specified API endpoint.
     *
     * @param string $service Service name.
     * @param string $endpoint Endpoint URL or path.
     * @param array $params Optional. Parameters for the request.
     * @param string $method Optional. HTTP method (default is 'GET').
     * @return array|WP_Error Response data or WP_Error on failure.
     */
    public static function send_request( $service, $endpoint, $params = array(), $method = 'GET' ) {
        $api_key = self::get_api_key( $service );
        if ( ! $api_key ) {
            return new WP_Error( 'missing_api_key', __( 'API key is missing for ' . $service, 'content-planner' ) );
        }

        // Set up headers.
        $headers = array(
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type'  => 'application/json',
        );

        // Set up request arguments.
        $args = array(
            'method'  => $method,
            'headers' => $headers,
        );

        // Add parameters based on request method.
        if ( 'GET' === $method && ! empty( $params ) ) {
            $endpoint = add_query_arg( $params, $endpoint );
        } elseif ( 'POST' === $method ) {
            $args['body'] = json_encode( $params );
        }

        // Check if a cached response exists.
        $cache_key = md5( $service . $endpoint . serialize( $params ) );
        $cached_response = get_transient( $cache_key );
        if ( $cached_response ) {
            return $cached_response;
        }

        // Send the request.
        $response = wp_remote_request( $endpoint, $args );
        if ( is_wp_error( $response ) ) {
            return $response;
        }

        // Decode the response and handle errors.
        $data = json_decode( wp_remote_retrieve_body( $response ), true );
        if ( json_last_error() !== JSON_ERROR_NONE ) {
            return new WP_Error( 'invalid_response', __( 'Invalid JSON response', 'content-planner' ) );
        }

        // Cache the response to reduce redundant API calls.
        set_transient( $cache_key, $data, HOUR_IN_SECONDS );

        return $data;
    }

    /**
     * Request Google Trends data.
     *
     * @param array $params Parameters specific to Google Trends (e.g., keywords, location).
     * @return array|WP_Error Response data or WP_Error on failure.
     */
    public static function get_google_trends_data( $params = array() ) {
        $endpoint = 'https://trends.google.com/api_endpoint_here';
        return self::send_request( 'google_trends', $endpoint, $params );
    }

    /**
     * Request Google Search Console data.
     *
     * @param array $params Parameters for Search Console request.
     * @return array|WP_Error Response data or WP_Error on failure.
     */
    public static function get_search_console_data( $params = array() ) {
        $endpoint = 'https://www.googleapis.com/webmasters/v3/sites/site_url/searchAnalytics/query';
        return self::send_request( 'google_search_console', $endpoint, $params, 'POST' );
    }

    /**
     * Request Google Analytics data.
     *
     * @param array $params Parameters for Analytics request.
     * @return array|WP_Error Response data or WP_Error on failure.
     */
    public static function get_analytics_data( $params = array() ) {
        $endpoint = 'https://analyticsreporting.googleapis.com/v4/reports:batchGet';
        return self::send_request( 'google_analytics', $endpoint, $params, 'POST' );
    }

    /**
     * Request OpenAI content generation.
     *
     * @param string $prompt The prompt for generating content.
     * @param int $max_tokens Maximum tokens for the response.
     * @return array|WP_Error Response data or WP_Error on failure.
     */
    public static function generate_content( $prompt, $max_tokens = 100 ) {
        $endpoint = 'https://api.openai.com/v1/completions';
        $params = array(
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
            'max_tokens' => $max_tokens,
        );
        return self::send_request( 'openai', $endpoint, $params, 'POST' );
    }
}
