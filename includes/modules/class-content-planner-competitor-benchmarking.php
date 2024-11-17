<?php
/**
 * Competitor Benchmarking Module
 *
 * Provides functionalities for competitor keyword analysis, SERP monitoring, and backlink opportunities.
 *
 * @package Content_Planner
 */

class Content_Planner_Competitor_Benchmarking {

    /**
     * Initialize the competitor benchmarking module.
     */
    public static function init() {
        add_action( 'wp_ajax_content_planner_competitor_keywords', array( __CLASS__, 'handle_competitor_keywords' ) );
        add_action( 'wp_ajax_content_planner_backlink_opportunities', array( __CLASS__, 'handle_backlink_opportunities' ) );
    }

    /**
     * Analyze competitor keywords using Google Search Console.
     *
     * @param string $competitor_url The competitor's URL to analyze.
     * @return array|WP_Error List of competitor keywords or WP_Error on failure.
     */
    public static function get_competitor_keywords( $competitor_url ) {
        $params = array(
            'siteUrl' => $competitor_url,
            'dimensions' => array( 'query' ),
        );

        $keywords = Content_Planner_API_Handler::get_search_console_data( $params );

        if ( is_wp_error( $keywords ) ) {
            return $keywords;
        }

        $result = array();
        foreach ( $keywords['rows'] as $row ) {
            $result[] = array(
                'query'       => $row['keys'][0],
                'impressions' => $row['impressions'],
                'position'    => $row['position'],
            );
        }

        return $result;
    }

    /**
     * Handle AJAX request for competitor keyword analysis.
     */
    public static function handle_competitor_keywords() {
        check_ajax_referer( 'content_planner_nonce', 'nonce' );

        $competitor_url = isset( $_POST['competitor_url'] ) ? esc_url_raw( $_POST['competitor_url'] ) : '';
        if ( empty( $competitor_url ) ) {
            wp_send_json_error( __( 'Competitor URL is required', 'content-planner' ) );
        }

        $keywords = self::get_competitor_keywords( $competitor_url );
        if ( is_wp_error( $keywords ) ) {
            wp_send_json_error( $keywords->get_error_message() );
        }

        wp_send_json_success( $keywords );
    }

    /**
     * Find backlink opportunities by analyzing competitor backlinks.
     *
     * @param string $competitor_url The competitor's URL.
     * @return array List of websites linking to the competitor.
     */
    public static function get_backlink_opportunities( $competitor_url ) {
        // Placeholder: Replace with actual backlink analysis via an external API.
        $backlinks = array(
            array(
                'domain' => 'example.com',
                'url'    => 'https://example.com/link-to-competitor',
            ),
            array(
                'domain' => 'another-example.com',
                'url'    => 'https://another-example.com/link-to-competitor',
            ),
        );

        return $backlinks;
    }

    /**
     * Handle AJAX request for backlink opportunities.
     */
    public static function handle_backlink_opportunities() {
        check_ajax_referer( 'content_planner_nonce', 'nonce' );

        $competitor_url = isset( $_POST['competitor_url'] ) ? esc_url_raw( $_POST['competitor_url'] ) : '';
        if ( empty( $competitor_url ) ) {
            wp_send_json_error( __( 'Competitor URL is required', 'content-planner' ) );
        }

        $backlinks = self::get_backlink_opportunities( $competitor_url );
        if ( is_wp_error( $backlinks ) ) {
            wp_send_json_error( $backlinks->get_error_message() );
        }

        wp_send_json_success( $backlinks );
    }
}
