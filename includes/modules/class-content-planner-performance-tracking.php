<?php
/**
 * Performance Tracking Module
 *
 * Tracks keyword rankings, content performance metrics, and user engagement.
 *
 * @package Content_Planner
 */

class Content_Planner_Performance_Tracking {

    /**
     * Initialize the performance tracking module.
     */
    public static function init() {
        add_action( 'wp_ajax_content_planner_keyword_tracking', array( __CLASS__, 'handle_keyword_tracking' ) );
        add_action( 'wp_ajax_content_planner_content_performance', array( __CLASS__, 'handle_content_performance' ) );
    }

    /**
     * Get keyword ranking data from Google Search Console.
     *
     * @param string $keyword The keyword to track.
     * @return array|WP_Error Ranking data or WP_Error on failure.
     */
    public static function get_keyword_rankings( $keyword ) {
        $params = array(
            'dimensions' => array( 'query' ),
            'dimensionFilterGroups' => array(
                array(
                    'filters' => array(
                        array(
                            'dimension' => 'query',
                            'operator'  => 'equals',
                            'expression' => $keyword,
                        ),
                    ),
                ),
            ),
        );

        $ranking_data = Content_Planner_API_Handler::get_search_console_data( $params );

        if ( is_wp_error( $ranking_data ) ) {
            return $ranking_data;
        }

        return $ranking_data['rows'] ?? array();
    }

    /**
     * Handle AJAX request for keyword tracking.
     */
    public static function handle_keyword_tracking() {
        check_ajax_referer( 'content_planner_nonce', 'nonce' );

        $keyword = isset( $_POST['keyword'] ) ? sanitize_text_field( $_POST['keyword'] ) : '';
        if ( empty( $keyword ) ) {
            wp_send_json_error( __( 'Keyword is required', 'content-planner' ) );
        }

        $rankings = self::get_keyword_rankings( $keyword );
        if ( is_wp_error( $rankings ) ) {
            wp_send_json_error( $rankings->get_error_message() );
        }

        wp_send_json_success( $rankings );
    }

    /**
     * Get content performance metrics from Google Analytics.
     *
     * @param string $url The URL of the content to analyze.
     * @return array|WP_Error Performance metrics or WP_Error on failure.
     */
    public static function get_content_performance( $url ) {
        $params = array(
            'metrics' => array(
                array( 'expression' => 'ga:pageviews' ),
                array( 'expression' => 'ga:avgTimeOnPage' ),
                array( 'expression' => 'ga:bounceRate' ),
            ),
            'dimensions' => array( 'ga:pagePath' ),
            'filtersExpression' => 'ga:pagePath==' . parse_url( $url, PHP_URL_PATH ),
        );

        $analytics_data = Content_Planner_API_Handler::get_analytics_data( $params );

        if ( is_wp_error( $analytics_data ) ) {
            return $analytics_data;
        }

        return $analytics_data['reports'][0]['data']['rows'] ?? array();
    }

    /**
     * Handle AJAX request for content performance metrics.
     */
    public static function handle_content_performance() {
        check_ajax_referer( 'content_planner_nonce', 'nonce' );

        $url = isset( $_POST['url'] ) ? esc_url_raw( $_POST['url'] ) : '';
        if ( empty( $url ) ) {
            wp_send_json_error( __( 'URL is required', 'content-planner' ) );
        }

        $performance = self::get_content_performance( $url );
        if ( is_wp_error( $performance ) ) {
            wp_send_json_error( $performance->get_error_message() );
        }

        wp_send_json_success( $performance );
    }
}
