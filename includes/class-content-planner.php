<?php
/**
 * Main Plugin Class
 *
 * Manages the initialization and core functionality of the plugin.
 *
 * @package Content_Planner
 */

class Content_Planner {

    /**
     * Initialize the plugin.
     */
    public static function init() {
        self::load_dependencies();
    }

    /**
     * Load dependencies.
     */
    private static function load_dependencies() {
        // Load API handler and modules.
        require_once CONTENT_PLANNER_PATH . 'includes/class-content-planner-api-handler.php';
        require_once CONTENT_PLANNER_PATH . 'includes/modules/class-content-planner-keyword-research.php';
        require_once CONTENT_PLANNER_PATH . 'includes/modules/class-content-planner-content-planning.php';
        require_once CONTENT_PLANNER_PATH . 'includes/modules/class-content-planner-content-generation.php';
        require_once CONTENT_PLANNER_PATH . 'includes/modules/class-content-planner-on-page-seo.php';
        require_once CONTENT_PLANNER_PATH . 'includes/modules/class-content-planner-competitor-benchmarking.php';

        // Initialize modules.
        Content_Planner_Keyword_Research::init();
        Content_Planner_Content_Planning::init();
        Content_Planner_Content_Generation::init();
        Content_Planner_On_Page_SEO::init();
        Content_Planner_Competitor_Benchmarking::init();
    }

    /**
     * Plugin activation hook.
     */
    public static function activate() {
        // Activation logic here.
    }

    /**
     * Plugin deactivation hook.
     */
    public static function deactivate() {
        // Deactivation logic here.
    }
}
