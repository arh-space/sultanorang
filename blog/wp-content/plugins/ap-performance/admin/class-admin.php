<?php
/**
 * Admin functions, definitions and includes.
 *
 * @package     AP_Performance/Classes
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * AP_Performance_Admin Class.
 */
class AP_Performance_Admin {

    /**
     * Hook in methods.
     *
     * @since   1.0.0
     * @access  public
     */
    public function __construct() {
        add_filter( 'plugin_action_links_' . ap_performance()->basename, array( $this, 'plugin_action_links' ) );
        add_action( 'admin_menu', array( $this, 'add_options_page' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_init', array( $this, 'add_settings' ) );
        add_action( 'admin_head', array( $this, 'admin_head' ) );
    }

    /**
     * Add style to head.
     *
     * @since   1.0.0
     * @access  public
     */
    public function admin_head() {
        echo '<style>.ap-performance-max-width { max-width: 420px }</style>';
    }

    /**
     * Show action links on the plugin screen.
     *
     * @since   1.0.0
     * @access  public
     * @param   array $links Plugin Action links
     * @return  array $links Plugin Action links
     */
    public function plugin_action_links( $links ) {

        $links['settings'] = '<a href="' . esc_url( admin_url( 'options-general.php?page=ap-performance' ) ) . '" aria-label="'. esc_attr__( 'AP Performance Settings', 'ap-performance' ) .'">'. esc_attr__( 'Settings', 'ap-performance' ) .'</a>';

        return $links;
    }

    /**
     * Add options page under "Settings".
     *
     * @since   1.0.0
     * @access  public
     */
    public function add_options_page() {
        add_options_page(
            esc_html__( 'AP Performance Settings', 'ap-performance' ),
            esc_html__( 'AP Performance', 'ap-performance' ),
            'manage_options',
            'ap-performance', 
            array( $this, 'create_options_page' )
        );
    }

    /**
     * Options page callback.
     *
     * @since   1.0.0
     * @access  public
     */
    public function create_options_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'AP Performance Settings', 'ap-performance' ); ?></h1>
            <form method="post" action="options.php">
            <?php
                settings_fields( 'ap_performance_options' );
                do_settings_sections( 'ap-performance-settings' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register settings.
     *
     * @since   1.0.0
     * @access  public
     */
    public function register_settings() {

        // 'string', 'boolean', 'integer', and 'number'

        register_setting(
            'ap_performance_options',
            'ap_performance_defer_scripts',
            array(
                'type' => 'boolean',
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
                'default' => true,
            )
        );

        register_setting(
            'ap_performance_options',
            'ap_performance_remove_defer_jquery',
            array(
                'type' => 'boolean',
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
                'default' => false,
            )
        );

        register_setting(
            'ap_performance_options',
            'ap_performance_async_scripts_handles',
            array(
                'type' => 'string',
                'sanitize_callback' => 'sanitize_textarea_field',
                'default' => '',
            )
        );

        register_setting(
            'ap_performance_options',
            'ap_performance_disable_defer_scripts_handles',
            array(
                'type' => 'string',
                'sanitize_callback' => 'sanitize_textarea_field',
                'default' => '',
            )
        );

        register_setting(
            'ap_performance_options',
            'ap_performance_disable_jquery_migrate',
            array(
                'type' => 'boolean',
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
                'default' => false,
            )
        );

        register_setting(
            'ap_performance_options',
            'ap_performance_disable_emojis',
            array(
                'type' => 'boolean',
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
                'default' => false,
            )
        );

        register_setting(
            'ap_performance_options',
            'ap_performance_disable_wp_embed',
            array(
                'type' => 'boolean',
                'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
                'default' => false,
            )
        );

        register_setting(
            'ap_performance_options',
            'ap_performance_disable_scripts_handles',
            array(
                'type' => 'string',
                'sanitize_callback' => 'sanitize_textarea_field',
                'default' => '',
            )
        );

    }

    /**
     * Add settings sections and fields.
     *
     * @since   1.0.0
     * @access  public
     */
    public function add_settings() {

        // Render Blocking Scripts
        add_settings_section(
            'ap-performance-render-blocking-scripts-section',
            esc_html__( 'Render Blocking Scripts', 'ap-performance' ),
            '__return_false',
            'ap-performance-settings'
        );

        add_settings_field(
            'defer_scripts',
            esc_html__( 'Defer scripts', 'ap-performance' ),
            array( $this, 'defer_scripts_callback' ),
            'ap-performance-settings',
            'ap-performance-render-blocking-scripts-section'
        );

        add_settings_field(
            'remove_defer_jquery',
            esc_html__( "Don't defer jQuery", 'ap-performance' ),
            array( $this, 'ap_performance_remove_defer_jquery_callback' ),
            'ap-performance-settings',
            'ap-performance-render-blocking-scripts-section'
        );

        add_settings_field(
            'async_scripts_handles',
            esc_html__( 'Async scripts handles', 'ap-performance' ),
            array( $this, 'async_scripts_handles_callback' ),
            'ap-performance-settings',
            'ap-performance-render-blocking-scripts-section',
            array( 'label_for' => 'async_scripts_handles' )
        );

        add_settings_field(
            'disable_defer_scripts_handles',
            esc_html__( 'Disable defer scripts handles', 'ap-performance' ),
            array( $this, 'disable_defer_scripts_handles_callback' ),
            'ap-performance-settings',
            'ap-performance-render-blocking-scripts-section',
            array( 'label_for' => 'disable_defer_scripts_handles' )
        );

        // Other Scripts
        add_settings_section(
            'ap-performance-other-scripts-section',
            esc_html__( 'Other Scripts', 'ap-performance' ),
            '__return_false',
            'ap-performance-settings'
        );

        add_settings_field(
            'disable_jquery_migrate',
            esc_html__( 'Disable jQuery Migrate', 'ap-performance' ),
            array( $this, 'disable_jquery_migrate_callback' ),
            'ap-performance-settings',
            'ap-performance-other-scripts-section'
        );

        add_settings_field(
            'disable_emojis',
            esc_html__( 'Disable Emojis', 'ap-performance' ),
            array( $this, 'disable_emojis_callback' ),
            'ap-performance-settings',
            'ap-performance-other-scripts-section'
        );

        add_settings_field(
            'disable_wp_embed',
            esc_html__( 'Disable WP Embed', 'ap-performance' ),
            array( $this, 'disable_wp_embed_callback' ),
            'ap-performance-settings',
            'ap-performance-other-scripts-section'
        );

        add_settings_field(
            'disable_scripts_handles',
            esc_html__( 'Disable scripts handles', 'ap-performance' ),
            array( $this, 'disable_scripts_handles_callback' ),
            'ap-performance-settings',
            'ap-performance-other-scripts-section',
            array( 'label_for' => 'disable_scripts_handles' )
        );
    }

    /** 
     * Callback for setting ID defer_scripts.
     *
     * @since   1.0.0
     * @access  public
     */
    public function defer_scripts_callback() {
        $value = get_option( 'ap_performance_defer_scripts' );
        ?>
        <fieldset class="ap-performance-max-width">
            <legend class="screen-reader-text"><span><?php esc_html_e( 'Defer scripts', 'ap-performance' ); ?></span></legend>
            <label for="defer_scripts"><input name="ap_performance_defer_scripts" type="checkbox" id="defer_scripts" <?php echo checked( $value, 1 ); ?> value="1"><?php esc_html_e( 'Defer loading of scripts', 'ap-performance' ); ?></label>
            <p class="description ap-performance-max-width"><?php esc_html_e( "When scripts are deferred they aren't render blocking. The scripts are executed when the page has finished parsing.", 'ap-performance' ); ?></p>
        </fieldset>
        <?php
    }

    /** 
     * Callback for setting ID remove_defer_jquery.
     *
     * @since   1.0.0
     * @access  public
     */
    public function ap_performance_remove_defer_jquery_callback() {
        $value = get_option( 'ap_performance_remove_defer_jquery' );
        ?>
        <fieldset class="ap-performance-max-width">
            <legend class="screen-reader-text"><span><?php esc_html_e( "Don't defer jQuery", 'ap-performance' ); ?></span></legend>
            <label for="remove_defer_jquery"><input name="ap_performance_remove_defer_jquery" type="checkbox" id="remove_defer_jquery" <?php echo checked( $value, 1 ); ?> value="1"><?php esc_html_e( 'Remove jQuery from deferred scripts', 'ap-performance' ); ?></label>
            <p class="description ap-performance-max-width"><?php esc_html_e( "Some plugins might need jQuery not to be deferred. If you have javascripts problems with other plugins try enabling this option.", 'ap-performance' ); ?></p>
        </fieldset>
        <?php
    }

    /** 
     * Callback for setting ID async_scripts_handles.
     *
     * @since   1.0.0
     * @access  public
     */
    public function async_scripts_handles_callback() {
        $value = get_option( 'ap_performance_async_scripts_handles' );
        ?>
        <textarea class="ap-performance-max-width" id="async_scripts_handles" name="ap_performance_async_scripts_handles" rows="2" cols="90" placeholder="theme-scripts,theme-plugins"><?php echo esc_htmL( $value ); ?></textarea>
        <p class="description ap-performance-max-width"><?php esc_html_e( 'These scripts are executed asynchronously with the rest of the page.', 'ap-performance' ); ?></p>
        <p class="description ap-performance-max-width"><?php esc_html_e( 'Enter handles of scripts you want to load async separated by comma.', 'ap-performance' ); ?></p>
        <p class="description ap-performance-max-width"><?php esc_html_e( 'E.g. theme-scripts,theme-plugins', 'ap-performance' ); ?></p>
        <?php
    }

    /** 
     * Callback for setting ID disable_scripts_handles.
     *
     * @since   1.1.0
     * @access  public
     */
    public function disable_defer_scripts_handles_callback() {
        $value = get_option( 'ap_performance_disable_defer_scripts_handles' );
        ?>
        <textarea class="ap-performance-max-width" id="disable_defer_scripts_handles" name="ap_performance_disable_defer_scripts_handles" rows="2" cols="90" placeholder="theme-scripts,theme-plugins"><?php echo esc_htmL( $value ); ?></textarea>
        <p class="description ap-performance-max-width"><?php esc_html_e( 'Enter handles of scripts you want to disable from deferring.', 'ap-performance' ); ?></p>
        <p class="description ap-performance-max-width"><?php esc_html_e( 'E.g. theme-scripts,theme-plugins', 'ap-performance' ); ?></p>
        <?php
    }

    /** 
     * Callback for setting ID disable_emojis.
     *
     * @since   1.0.0
     * @access  public
     */
    public function disable_jquery_migrate_callback() {
        $value = get_option( 'ap_performance_disable_jquery_migrate' );
        ?>
        <fieldset class="ap-performance-max-width">
            <legend class="screen-reader-text"><span><?php esc_html_e( 'Disable jQuery Migrate', 'ap-performance' ); ?></span></legend>
            <label for="disable_jquery_migrate"><input name="ap_performance_disable_jquery_migrate" type="checkbox" id="disable_jquery_migrate" <?php echo checked( $value, 1 ); ?> value="1"><?php esc_html_e( 'Disable jQuery Migrate', 'ap-performance' ); ?></label>
            <p class="description ap-performance-max-width"><?php esc_html_e( "jQuery Migrate is needed only for old plugins and themes that use old jQuery functions. It should be safe to disable it.", 'ap-performance' ); ?></p>
        </fieldset>
        <?php
    }

    /** 
     * Callback for setting ID disable_emojis.
     *
     * @since   1.0.0
     * @access  public
     */
    public function disable_emojis_callback() {
        $value = get_option( 'ap_performance_disable_emojis' );
        ?>
        <fieldset class="ap-performance-max-width">
            <legend class="screen-reader-text"><span><?php esc_html_e( 'Disable Emojis', 'ap-performance' ); ?></span></legend>
            <label for="disable_emojis"><input name="ap_performance_disable_emojis" type="checkbox" id="disable_emojis" <?php echo checked( $value, 1 ); ?> value="1"><?php esc_html_e( 'Disable emojis scripts', 'ap-performance' ); ?></label>
            <p class="description ap-performance-max-width"><?php esc_html_e( "If you don't need emojis you can disable them from loading.", 'ap-performance' ); ?></p>
        </fieldset>
        <?php
    }

    /** 
     * Callback for setting ID disable_wp_embed.
     *
     * @since   1.0.0
     * @access  public
     */
    public function disable_wp_embed_callback() {
        $value = get_option( 'ap_performance_disable_wp_embed' );
        ?>
        <fieldset class="ap-performance-max-width">
            <legend class="screen-reader-text"><span><?php esc_html_e( 'Disable WP Embed', 'ap-performance' ); ?></span></legend>
            <label for="disable_wp_embed"><input name="ap_performance_disable_wp_embed" type="checkbox" id="disable_wp_embed" <?php echo checked( $value, 1 ); ?> value="1"><?php esc_html_e( 'Disable embed scripts', 'ap-performance' ); ?></label>
            <p class="description ap-performance-max-width"><?php esc_html_e( "WP Embed scripts are used if you want to embed other sites into yours as iframes.", 'ap-performance' ); ?></p>
        </fieldset>
        <?php
    }

    /** 
     * Callback for setting ID disable_scripts_handles.
     *
     * @since   1.0.0
     * @access  public
     */
    public function disable_scripts_handles_callback() {
        $value = get_option( 'ap_performance_disable_scripts_handles' );
        ?>
        <textarea class="ap-performance-max-width" id="disable_scripts_handles" name="ap_performance_disable_scripts_handles" rows="2" cols="90" placeholder="theme-scripts,theme-plugins"><?php echo esc_htmL( $value ); ?></textarea>
        <p class="description ap-performance-max-width"><?php esc_html_e( 'Enter handles of scripts you want to disable from loading.', 'ap-performance' ); ?></p>
        <p class="description ap-performance-max-width"><?php esc_html_e( 'E.g. theme-scripts,theme-plugins', 'ap-performance' ); ?></p>
        <?php
    }

    /**
     * Checkbox sanitization callback.
     *
     * @since   1.0.0
     * @access  public
     * @param   bool $checked Whether the checkbox is checked.
     * @return  bool Whether the checkbox is checked.
     */
    public function sanitize_checkbox( $checked ) {
        return $checked ? true : false;
    }
}

new AP_Performance_Admin();
