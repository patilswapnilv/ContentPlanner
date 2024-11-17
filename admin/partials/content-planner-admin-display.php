<?php
/**
 * Content Planner Admin Display
 *
 * Provides the HTML structure for the keyword research and competitor analysis UI in the dashboard.
 *
 * @package Content_Planner
 */

// Security check to ensure this file is accessed through WordPress.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Nonce for AJAX security.
$ajax_nonce = wp_create_nonce( 'content_planner_nonce' );
?>

<div class="wrap content-planner-dashboard">
    <h1><?php esc_html_e( 'Content Planner Dashboard', 'content-planner' ); ?></h1>

    <div id="content-planner-keyword-research">
        <h2><?php esc_html_e( 'Keyword Research', 'content-planner' ); ?></h2>
        <form id="keyword-research-form">
            <label for="keyword"><?php esc_html_e( 'Enter a Keyword', 'content-planner' ); ?></label>
            <input type="text" id="keyword" name="keyword" required>
            <label for="location"><?php esc_html_e( 'Location (Optional)', 'content-planner' ); ?></label>
            <input type="text" id="location" name="location" placeholder="e.g., US">
            <button type="button" id="keyword-search-button" class="button button-primary"><?php esc_html_e( 'Search', 'content-planner' ); ?></button>
        </form>
        <div id="keyword-research-results"></div>
    </div>

    <hr>

    <div id="content-planner-competitor-analysis">
        <h2><?php esc_html_e( 'Competitor Analysis', 'content-planner' ); ?></h2>
        <form id="competitor-analysis-form">
            <label for="competitor-url"><?php esc_html_e( 'Enter Competitor URL', 'content-planner' ); ?></label>
            <input type="url" id="competitor-url" name="competitor-url" required placeholder="https://competitor.com">
            <button type="button" id="competitor-analysis-button" class="button button-primary"><?php esc_html_e( 'Analyze', 'content-planner' ); ?></button>
        </form>
        <div id="competitor-analysis-results"></div>
    </div>

    <script>
        // Pass nonce to JavaScript for security
        const contentPlannerNonce = "<?php echo esc_js( $ajax_nonce ); ?>";
    </script>
</div>
<div id="content-planner-content-planning">
    <h2><?php esc_html_e( 'Content Gap Analysis', 'content-planner' ); ?></h2>
    <button type="button" id="gap-analysis-button" class="button button-primary"><?php esc_html_e( 'Perform Gap Analysis', 'content-planner' ); ?></button>
    <div id="gap-analysis-results"></div>

    <h2><?php esc_html_e( 'SERP Analysis', 'content-planner' ); ?></h2>
    <form id="serp-analysis-form">
        <label for="serp-keyword"><?php esc_html_e( 'Enter Keyword', 'content-planner' ); ?></label>
        <input type="text" id="serp-keyword" name="serp-keyword" required>
        <button type="button" id="serp-analysis-button" class="button button-primary"><?php esc_html_e( 'Analyze', 'content-planner' ); ?></button>
    </form>
    <div id="serp-analysis-results"></div>
</div>
<div id="content-planner-content-generation">
    <h2><?php esc_html_e( 'Content Draft Generation', 'content-planner' ); ?></h2>
    <form id="content-draft-form">
        <label for="content-keyword"><?php esc_html_e( 'Enter Keyword', 'content-planner' ); ?></label>
        <input type="text" id="content-keyword" name="content-keyword" required>
        <label for="tone"><?php esc_html_e( 'Tone', 'content-planner' ); ?></label>
        <select id="tone" name="tone">
            <option value="neutral"><?php esc_html_e( 'Neutral', 'content-planner' ); ?></option>
            <option value="formal"><?php esc_html_e( 'Formal', 'content-planner' ); ?></option>
            <option value="casual"><?php esc_html_e( 'Casual', 'content-planner' ); ?></option>
        </select>
        <button type="button" id="generate-draft-button" class="button button-primary"><?php esc_html_e( 'Generate Draft', 'content-planner' ); ?></button>
    </form>
    <div id="content-draft-results"></div>

    <h2><?php esc_html_e( 'SEO Metadata Generation', 'content-planner' ); ?></h2>
    <form id="metadata-form">
        <label for="metadata-keyword"><?php esc_html_e( 'Enter Keyword', 'content-planner' ); ?></label>
        <input type="text" id="metadata-keyword" name="metadata-keyword" required>
        <button type="button" id="generate-metadata-button" class="button button-primary"><?php esc_html_e( 'Generate Metadata', 'content-planner' ); ?></button>
    </form>
    <div id="metadata-results"></div>
</div>
<div id="content-planner-on-page-seo">
    <h2><?php esc_html_e( 'SEO Checklist', 'content-planner' ); ?></h2>
    <form id="seo-checklist-form">
        <label for="post-id"><?php esc_html_e( 'Post ID', 'content-planner' ); ?></label>
        <input type="number" id="post-id" name="post-id" required>
        <button type="button" id="seo-checklist-button" class="button button-primary"><?php esc_html_e( 'Analyze', 'content-planner' ); ?></button>
    </form>
    <div id="seo-checklist-results"></div>

    <h2><?php esc_html_e( 'Internal Linking Suggestions', 'content-planner' ); ?></h2>
    <form id="internal-links-form">
        <label for="post-id-links"><?php esc_html_e( 'Post ID', 'content-planner' ); ?></label>
        <input type="number" id="post-id-links" name="post-id-links" required>
        <button type="button" id="internal-links-button" class="button button-primary"><?php esc_html_e( 'Get Suggestions', 'content-planner' ); ?></button>
    </form>
    <div id="internal-links-results"></div>
</div>
<div id="content-planner-performance-tracking">
    <h2><?php esc_html_e( 'Keyword Tracking', 'content-planner' ); ?></h2>
    <form id="keyword-tracking-form">
        <label for="keyword"><?php esc_html_e( 'Enter Keyword', 'content-planner' ); ?></label>
        <input type="text" id="keyword" name="keyword" required>
        <button type="button" id="keyword-tracking-button" class="button button-primary"><?php esc_html_e( 'Track', 'content-planner' ); ?></button>
    </form>
    <div id="keyword-tracking-results"></div>

    <h2><?php esc_html_e( 'Content Performance', 'content-planner' ); ?></h2>
    <form id="content-performance-form">
        <label for="content-url"><?php esc_html_e( 'Enter Content URL', 'content-planner' ); ?></label>
        <input type="url" id="content-url" name="content-url" required>
        <button type="button" id="content-performance-button" class="button button-primary"><?php esc_html_e( 'Analyze', 'content-planner' ); ?></button>
    </form>
    <div id="content-performance-results"></div>
</div>
<div id="content-planner-competitor-benchmarking">
    <h2><?php esc_html_e( 'Competitor Keyword Analysis', 'content-planner' ); ?></h2>
    <form id="competitor-keyword-form">
        <label for="competitor-url"><?php esc_html_e( 'Enter Competitor URL', 'content-planner' ); ?></label>
        <input type="url" id="competitor-url" name="competitor-url" required>
        <button type="button" id="competitor-keyword-button" class="button button-primary"><?php esc_html_e( 'Analyze', 'content-planner' ); ?></button>
    </form>
    <div id="competitor-keyword-results"></div>

    <h2><?php esc_html_e( 'Backlink Opportunities', 'content-planner' ); ?></h2>
    <form id="backlink-opportunity-form">
        <label for="competitor-url-backlink"><?php esc_html_e( 'Enter Competitor URL', 'content-planner' ); ?></label>
        <input type="url" id="competitor-url-backlink" name="competitor-url-backlink" required>
        <button type="button" id="backlink-opportunity-button" class="button button-primary"><?php esc_html_e( 'Find Opportunities', 'content-planner' ); ?></button>
    </form>
    <div id="backlink-opportunity-results"></div>
</div>
