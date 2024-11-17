<?php
/**
 * Content Generation Module
 *
 * Provides functionalities for AI-generated content drafts, content expansion, and SEO metadata.
 *
 * @package Content_Planner
 */

class Content_Planner_Content_Generation {

    /**
     * Initialize the content generation module.
     */
    public static function init() {
        add_action( 'wp_ajax_content_planner_generate_draft', array( __CLASS__, 'handle_generate_draft' ) );
        add_action( 'wp_ajax_content_planner_generate_metadata', array( __CLASS__, 'handle_generate_metadata' ) );
    }

    /**
     * Generate content draft based on a keyword or outline.
     *
     * @param string $keyword The main keyword or outline for the draft.
     * @param string $tone Optional. The tone of the content ('formal', 'casual', etc.).
     * @param int $length Optional. Maximum length for the draft.
     * @return string|WP_Error Generated draft content or WP_Error on failure.
     */
    public static function generate_draft( $keyword, $tone = 'neutral', $length = 500 ) {
        $prompt = "Write a $tone blog post about '$keyword'. Length: approximately $length words.";
        $response = Content_Planner_API_Handler::generate_content( $prompt, $length );

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        return $response['choices'][0]['text'] ?? '';
    }

    /**
     * Expand existing content by generating additional sections or supporting points.
     *
     * @param string $content The content to expand.
     * @param int $additional_length The length of additional content.
     * @return string|WP_Error Expanded content or WP_Error on failure.
     */
    public static function expand_content( $content, $additional_length = 200 ) {
        $prompt = "Expand the following content with additional details: $content";
        $response = Content_Planner_API_Handler::generate_content( $prompt, $additional_length );

        if ( is_wp_error( $response ) ) {
            return $response;
        }

        return $response['choices'][0]['text'] ?? '';
    }

    /**
     * Generate SEO metadata based on a keyword.
     *
     * @param string $keyword The keyword to base the metadata on.
     * @return array|WP_Error Array with SEO title, meta description, and alt text, or WP_Error on failure.
     */
    public static function generate_metadata( $keyword ) {
        $title_prompt = "Generate an SEO-friendly title for a blog post about '$keyword'.";
        $meta_prompt = "Write a meta description for a blog post about '$keyword' within 155 characters.";
        $alt_text_prompt = "Generate an image alt text for an image related to '$keyword'.";

        $title = Content_Planner_API_Handler::generate_content( $title_prompt, 20 );
        $meta_description = Content_Planner_API_Handler::generate_content( $meta_prompt, 30 );
        $alt_text = Content_Planner_API_Handler::generate_content( $alt_text_prompt, 15 );

        if ( is_wp_error( $title ) || is_wp_error( $meta_description ) || is_wp_error( $alt_text ) ) {
            return new WP_Error( 'metadata_error', __( 'Error generating metadata', 'content-planner' ) );
        }

        return array(
            'title'           => $title['choices'][0]['text'] ?? '',
            'meta_description' => $meta_description['choices'][0]['text'] ?? '',
            'alt_text'        => $alt_text['choices'][0]['text'] ?? '',
        );
    }

    /**
     * Handle AJAX request for generating a content draft.
     */
    public static function handle_generate_draft() {
        // Security check.
        check_ajax_referer( 'content_planner_nonce', 'nonce' );

        $keyword = isset( $_POST['keyword'] ) ? sanitize_text_field( $_POST['keyword'] ) : '';
        $tone = isset( $_POST['tone'] ) ? sanitize_text_field( $_POST['tone'] ) : 'neutral';

        if ( empty( $keyword ) ) {
            wp_send_json_error( __( 'Keyword is required', 'content-planner' ) );
        }

        $draft = self::generate_draft( $keyword, $tone );
        if ( is_wp_error( $draft ) ) {
            wp_send_json_error( $draft->get_error_message() );
        }

        wp_send_json_success( $draft );
    }

    /**
     * Handle AJAX request for generating SEO metadata.
     */
    public static function handle_generate_metadata() {
        // Security check.
        check_ajax_referer( 'content_planner_nonce', 'nonce' );

        $keyword = isset( $_POST['keyword'] ) ? sanitize_text_field( $_POST['keyword'] ) : '';
        if ( empty( $keyword ) ) {
            wp_send_json_error( __( 'Keyword is required', 'content-planner' ) );
        }

        $metadata = self::generate_metadata( $keyword );
        if ( is_wp_error( $metadata ) ) {
            wp_send_json_error( $metadata->get_error_message() );
        }

        wp_send_json_success( $metadata );
    }
}
