<?php
/**
 * Keyword Research Module
 *
 * Provides keyword discovery, trending keywords, and competitor analysis functionalities.
 *
 * @package Content_Planner
 */

class Content_Planner_Keyword_Research {

    /**
     * Initialize the module.
     */
    public static function init() {
        add_action( 'wp_ajax_content_planner_keyword_search', array( __CLASS__, 'handle_keyword_search' ) );
        add_action( 'wp_ajax_content_planner_competitor_analysis', array( __CLASS__, 'handle_competitor_analysis' ) );
    }

    /**
     * Fetch trending keywords using Google Trends API.
     *
     * @param string $keyword The primary keyword to search for trends.
     * @param string $location Optional. Location for the trends data.
     * @return array|WP_Error List of trending keywords or WP_Error on failure.
     */
    public static function get_trending_keywords( $keyword, $location = 'US' ) {
        $params = array(
            'keyword' => $keyword,
            'location' => $location,
        );
        $trending_keywords = Content_Planner_API_Handler::get_google_trends_data( $params );

        if ( is_wp_error( $trending_keywords ) ) {
            return $trending_keywords;
        }

        // Format the results as needed.
        return $trending_keywords['results'] ?? array();
    }

    /**
     * Handle AJAX request for keyword discovery.
     */
    public static function handle_keyword_search() {
        // Check AJAX nonce for security.
        check_ajax_referer( 'content_planner_nonce', 'nonce' );

        $keyword = isset( $_POST['keyword'] ) ? sanitize_text_field( $_POST['keyword'] ) : '';
        if ( empty( $keyword ) ) {
            wp_send_json_error( __( 'Keyword is required', 'content-planner' ) );
        }

        $location = isset( $_POST['location'] ) ? sanitize_text_field( $_POST['location'] ) : 'US';
        $trending_keywords = self::get_trending_keywords( $keyword, $location );

        if ( is_wp_error( $trending_keywords ) ) {
            wp_send_json_error( $trending_keywords->get_error_message() );
        }

        wp_send_json_success( $trending_keywords );
    }

    /**
     * Perform competitor analysis using Google Search Console data.
     *
     * @param string $competitor_url URL of the competitor site.
     * @return array|WP_Error List of keywords where competitor ranks but the user does not.
     */
    public static function get_competitor_keywords( $competitor_url ) {
        // Check if Search Console API key is available.
        $params = array(
            'siteUrl' => $competitor_url,
            'dimensions' => array( 'query' ),
        );
        $competitor_keywords = Content_Planner_API_Handler::get_search_console_data( $params );

        if ( is_wp_error( $competitor_keywords ) ) {
            return $competitor_keywords;
        }

        // Extract competitor keywords to identify keyword gaps.
        $keywords = array();
        foreach ( $competitor_keywords['rows'] as $row ) {
            $keywords[] = $row['keys'][0];
        }

        return $keywords;
    }

    /**
     * Handle AJAX request for competitor analysis.
     */
    public static function handle_competitor_analysis() {
        // Check AJAX nonce for security.
        check_ajax_referer( 'content_planner_nonce', 'nonce' );

        $competitor_url = isset( $_POST['competitor_url'] ) ? esc_url_raw( $_POST['competitor_url'] ) : '';
        if ( empty( $competitor_url ) ) {
            wp_send_json_error( __( 'Competitor URL is required', 'content-planner' ) );
        }

        $competitor_keywords = self::get_competitor_keywords( $competitor_url );

        if ( is_wp_error( $competitor_keywords ) ) {
            wp_send_json_error( $competitor_keywords->get_error_message() );
        }

        wp_send_json_success( $competitor_keywords );
    }
}
