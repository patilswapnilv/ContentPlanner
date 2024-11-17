<?php
/**
 * On-Page SEO Optimization Module
 *
 * Provides functionalities for SEO checklist, schema recommendations, and internal linking suggestions.
 *
 * @package Content_Planner
 */

class Content_Planner_On_Page_SEO {

    /**
     * Initialize the on-page SEO module.
     */
    public static function init() {
        add_action( 'wp_ajax_content_planner_seo_checklist', array( __CLASS__, 'handle_seo_checklist' ) );
        add_action( 'wp_ajax_content_planner_internal_links', array( __CLASS__, 'handle_internal_links' ) );
    }

    /**
     * Perform SEO checklist analysis for a given post.
     *
     * @param int $post_id The ID of the post to analyze.
     * @return array|WP_Error Checklist analysis results or WP_Error on failure.
     */
    public static function perform_seo_checklist( $post_id ) {
        $post = get_post( $post_id );
        if ( ! $post ) {
            return new WP_Error( 'invalid_post', __( 'Invalid post ID', 'content-planner' ) );
        }

        $content = $post->post_content;
        $title = $post->post_title;

        $checklist = array();

        // Check for keyword in title.
        $keyword = get_post_meta( $post_id, '_target_keyword', true );
        if ( $keyword ) {
            $checklist['keyword_in_title'] = strpos( strtolower( $title ), strtolower( $keyword ) ) !== false;
        } else {
            $checklist['keyword_in_title'] = false;
        }

        // Check for keyword in headings.
        $checklist['keyword_in_headings'] = preg_match( '/<h[1-6]>.*' . preg_quote( $keyword, '/' ) . '.*<\/h[1-6]>/', $content );

        // Check for keyword density.
        $word_count = str_word_count( strip_tags( $content ) );
        $keyword_count = substr_count( strtolower( strip_tags( $content ) ), strtolower( $keyword ) );
        $checklist['keyword_density'] = $word_count > 0 ? round( ( $keyword_count / $word_count ) * 100, 2 ) : 0;

        // Check for internal links.
        $checklist['has_internal_links'] = preg_match( '/<a href=".*?' . home_url() . '.*?">/', $content );

        // Check for alt text in images.
        $checklist['has_alt_text'] = preg_match( '/<img[^>]*alt="[^"]+"[^>]*>/', $content );

        return $checklist;
    }

    /**
     * Handle AJAX request for SEO checklist.
     */
    public static function handle_seo_checklist() {
        check_ajax_referer( 'content_planner_nonce', 'nonce' );

        $post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
        if ( ! $post_id ) {
            wp_send_json_error( __( 'Post ID is required', 'content-planner' ) );
        }

        $checklist = self::perform_seo_checklist( $post_id );
        if ( is_wp_error( $checklist ) ) {
            wp_send_json_error( $checklist->get_error_message() );
        }

        wp_send_json_success( $checklist );
    }

    /**
     * Suggest internal links for a given post.
     *
     * @param int $post_id The ID of the post to analyze.
     * @return array Suggested internal links.
     */
    public static function suggest_internal_links( $post_id ) {
        $post = get_post( $post_id );
        if ( ! $post ) {
            return new WP_Error( 'invalid_post', __( 'Invalid post ID', 'content-planner' ) );
        }

        $keyword = get_post_meta( $post_id, '_target_keyword', true );
        if ( ! $keyword ) {
            return array();
        }

        // Query posts containing the keyword in title or content.
        $query = new WP_Query( array(
            's'              => $keyword,
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => 5,
            'post__not_in'   => array( $post_id ),
        ) );

        $links = array();
        foreach ( $query->posts as $result ) {
            $links[] = array(
                'title' => $result->post_title,
                'url'   => get_permalink( $result->ID ),
            );
        }

        return $links;
    }

    /**
     * Handle AJAX request for internal links.
     */
    public static function handle_internal_links() {
        check_ajax_referer( 'content_planner_nonce', 'nonce' );

        $post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
        if ( ! $post_id ) {
            wp_send_json_error( __( 'Post ID is required', 'content-planner' ) );
        }

        $links = self::suggest_internal_links( $post_id );
        if ( is_wp_error( $links ) ) {
            wp_send_json_error( $links->get_error_message() );
        }

        wp_send_json_success( $links );
    }
}
