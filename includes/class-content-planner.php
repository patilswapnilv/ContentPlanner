<?php
/**
 * Main Plugin Class
 *
 * @package Content_Planner
 */

class Content_Planner {

    /**
     * Initialize the plugin.
     */
    public static function init() {
        // Load dependencies.
        self::load_dependencies();

        // Set up localization.
        add_action( 'plugins_loaded', array( __CLASS__, 'load_textdomain' ) );

        // Initialize modules.
        self::initialize_modules();

        // Add admin menu.
        add_action( 'admin_menu', array( __CLASS__, 'add_admin_menu' ) );
    }

    /**
     * Load dependencies and necessary files.
     */
    private static function load_dependencies() {
        // Load config file.
        require_once CONTENT_PLANNER_PATH . 'config.php';

        // Load settings class.
        require_once CONTENT_PLANNER_PATH . 'includes/class-content-planner-settings.php';

        // Load API handler.
        require_once CONTENT_PLANNER_PATH . 'includes/class-content-planner-api-handler.php';

        // Load individual modules.
        require_once CONTENT_PLANNER_PATH . 'includes/modules/class-content-planner-keyword-research.php';
        require_once CONTENT_PLANNER_PATH . 'includes/modules/class-content-planner-content-planning.php';
        require_once CONTENT_PLANNER_PATH . 'includes/modules/class-content-planner-content-generation.php';
        require_once CONTENT_PLANNER_PATH . 'includes/modules/class-content-planner-on-page-seo.php';
        require_once CONTENT_PLANNER_PATH . 'includes/modules/class-content-planner-performance-tracking.php';
        require_once CONTENT_PLANNER_PATH . 'includes/modules/class-content-planner-competitor-benchmarking.php';
    }

    /**
     * Load the plugin's textdomain for internationalization.
     */
    public static function load_textdomain() {
        load_plugin_textdomain( 'content-planner', false, dirname( CONTENT_PLANNER_BASENAME ) . '/languages' );
    }

    /**
     * Initialize each module.
     */
    private static function initialize_modules() {
        Content_Planner_Keyword_Research::init();
        Content_Planner_Content_Planning::init();
        Content_Planner_Content_Generation::init();
        Content_Planner_On_Page_SEO::init();
        Content_Planner_Performance_Tracking::init();
        Content_Planner_Competitor_Benchmarking::init();
    }

    /**
     * Add admin menu for the plugin settings page.
     */
    public static function add_admin_menu() {
        add_menu_page(
            __( 'Content Planner', 'content-planner' ),
            __( 'Content Planner', 'content-planner' ),
            'manage_options',
            'content-planner',
            array( 'Content_Planner_Settings', 'display_settings_page' ),
            'dashicons-chart-bar'
        );
    }

    /**
     * Run activation tasks.
     */
    public static function activate() {
        // Set default options if not present.
        add_option( 'content_planner_version', CONTENT_PLANNER_VERSION );
    }

    /**
     * Run deactivation tasks.
     */
    public static function deactivate() {
        // Remove plugin options if necessary.
        delete_option( 'content_planner_version' );
    }
}
