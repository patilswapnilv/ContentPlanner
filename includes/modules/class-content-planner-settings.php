<?php
/**
 * Settings Class
 *
 * Manages the plugin's settings page where users configure API keys and other options.
 *
 * @package Content_Planner
 */

class Content_Planner_Settings {

    /**
     * Initialize the settings page by adding actions.
     */
    public static function init() {
        add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
    }

    /**
     * Register settings, sections, and fields for the settings page.
     */
    public static function register_settings() {
        // Register setting to store API keys and options.
        register_setting( 'content_planner_settings', 'content_planner_options', array( __CLASS__, 'sanitize_options' ) );

        // Add settings section for API keys.
        add_settings_section(
            'content_planner_api_section',
            __( 'API Configuration', 'content-planner' ),
            array( __CLASS__, 'api_section_callback' ),
            'content_planner'
        );

        // Add fields for each API key.
        self::add_settings_field( 'google_trends_api_key', __( 'Google Trends API Key', 'content-planner' ) );
        self::add_settings_field( 'google_search_console_api_key', __( 'Google Search Console API Key', 'content-planner' ) );
        self::add_settings_field( 'google_analytics_api_key', __( 'Google Analytics API Key', 'content-planner' ) );
        self::add_settings_field( 'openai_api_key', __( 'OpenAI API Key', 'content-planner' ) );
    }

    /**
     * Helper function to add individual settings fields.
     */
    private static function add_settings_field( $id, $label ) {
        add_settings_field(
            $id,
            $label,
            array( __CLASS__, 'render_input_field' ),
            'content_planner',
            'content_planner_api_section',
            array( 'id' => $id )
        );
    }

    /**
     * Callback to display section description for API Configuration.
     */
    public static function api_section_callback() {
        echo '<p>' . esc_html__( 'Enter your API keys to enable features like keyword research, content generation, and analytics.', 'content-planner' ) . '</p>';
    }

    /**
     * Render input fields for API keys.
     *
     * @param array $args Field arguments.
     */
    public static function render_input_field( $args ) {
        $options = get_option( 'content_planner_options' );
        $value = isset( $options[ $args['id'] ] ) ? esc_attr( $options[ $args['id'] ] ) : '';
        ?>
        <input type="text" name="content_planner_options[<?php echo esc_attr( $args['id'] ); ?>]" value="<?php echo $value; ?>" style="width: 100%; max-width: 400px;">
        <?php
    }

    /**
     * Sanitize and validate options before saving.
     *
     * @param array $input Input values.
     * @return array Sanitized values.
     */
    public static function sanitize_options( $input ) {
        $sanitized = array();
        foreach ( $input as $key => $value ) {
            $sanitized[ $key ] = sanitize_text_field( $value );
        }
        return $sanitized;
    }

    /**
     * Display the settings page content.
     */
    public static function display_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Content Planner Settings', 'content-planner' ); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields( 'content_planner_settings' );
                do_settings_sections( 'content_planner' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
