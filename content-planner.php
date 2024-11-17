<?php
/**
 * Plugin Name:     Content Planner
 * Plugin URI:      https://example.com/
 * Description:     A comprehensive content planning and SEO tool that integrates with various APIs to optimize keyword research, content generation, and performance tracking.
 * Version:         0.1.0
 * Author:          Your Name
 * Author URI:      https://example.com/
 * Text Domain:     content-planner
 * Domain Path:     /languages
 * Requires PHP:    7.0
 *
 * @package         Content_Planner
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

// Define plugin constants.
define( 'CONTENT_PLANNER_VERSION', '0.1.0' );
define( 'CONTENT_PLANNER_PATH', plugin_dir_path( __FILE__ ) );
define( 'CONTENT_PLANNER_URL', plugin_dir_url( __FILE__ ) );
define( 'CONTENT_PLANNER_BASENAME', plugin_basename( __FILE__ ) );

// Include the main plugin class.
require_once CONTENT_PLANNER_PATH . 'includes/class-content-planner.php';

// Activation and Deactivation Hooks
register_activation_hook( __FILE__, array( 'Content_Planner', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Content_Planner', 'deactivate' ) );

// Initialize the plugin.
add_action( 'plugins_loaded', array( 'Content_Planner', 'init' ) );

/**
 * Enqueue admin scripts and styles.
 */
function content_planner_enqueue_admin_scripts( $hook ) {
    if ( $hook !== 'toplevel_page_content-planner' ) {
        return;
    }

    wp_enqueue_script( 'content-planner-admin-js', CONTENT_PLANNER_URL . 'admin/js/content-planner-admin.js', array( 'jquery' ), CONTENT_PLANNER_VERSION, true );
    wp_enqueue_style( 'content-planner-admin-css', CONTENT_PLANNER_URL . 'admin/css/content-planner-admin.css', array(), CONTENT_PLANNER_VERSION );
}
add_action( 'admin_enqueue_scripts', 'content_planner_enqueue_admin_scripts' );
