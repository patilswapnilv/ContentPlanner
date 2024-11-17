<?php
/**
 * Settings Class
 *
 * Provides a settings panel for API keys and options.
 *
 * @package Content_Planner
 */

class Content_Planner_Settings {

    /**
     * Initialize settings page.
     */
    public static function init() {
        add_action( 'admin_menu', array( __CLASS__, 'add_settings_page' ) );
        add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
    }

    /**
     * Add a settings page under the "Settings" menu.
     */
    public static function add_settings_page() {
        add_options_page(
            __( 'Content Planner Settings', 'content-planner' ),
            __( 'Content Planner', 'content-planner' ),
            'manage_options',
            'content-planner-settings',
            array( __CLASS__, 'display_settings_page' )
        );
    }

    /**
     * Register settings and fields.
     */
    public static function register_settings() {
        register_setting( 'content_planner_settings', 'content_planner_options', array( __CLASS__, 'sanitize_options' ) );

        add_settings_section(
            'content_planner_api_section',
            __( 'API Configuration', 'content-planner' ),
            null,
            'content_planner_settings'
        );

        self::add_settings_field( 'google_api_key', __( 'Google API Key', 'content-planner' ) );
        self::add_settings_field( 'openai_api_key', __( 'OpenAI API Key', 'content-planner' ) );
    }

    /**
     * Add individual settings fields.
     */
    private static function add_settings_field( $id, $label ) {
        add_settings_field(
            $id,
            $label,
            function() use ( $id ) {
                $options = get_option( 'content_planner_options', array() );
                $value = $options[ $id ] ?? '';
                $constant = strtoupper( $id );
                $readonly = defined( $constant ) ? 'readonly' : '';
                $message = defined( $constant ) ? __( 'This key is defined in wp-config.php and cannot be changed here.', 'content-planner' ) : '';
                echo "<input type='text' id='$id' name='content_planner_options[$id]' value='$value' $readonly>";
                if ( $message ) echo "<p class='description'>$message</p>";
            },
            'content_planner_settings',
            'content_planner_api_section'
        );
    }

    /**
     * Sanitize options before saving.
     */
    public static function sanitize_options( $input ) {
        $sanitized = array();
        foreach ( $input as $key => $value ) {
            $constant = strtoupper( $key );
            if ( ! defined( $constant ) ) {
                $sanitized[ $key ] = sanitize_text_field( $value );
            }
        }
        return $sanitized;
    }

    /**
     * Display the settings page.
     */
    public static function display_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Content Planner Settings', 'content-planner' ); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields( 'content_planner_settings' );
                do_settings_sections( 'content_planner_settings' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}

Content_Planner_Settings::init();
