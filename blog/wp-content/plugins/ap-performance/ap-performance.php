<?php
/**
 * Plugin Name: AP Performance
 * Description: Improve performance of your themes.
 * Version: 1.1.0
 * Author: APALODI
 * Author URI: https://apalodi.com
 * Tags: performance
 *
 * Text Domain: ap-performance
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main AP_Performance Class.
 *
 * @package     AP_Performance
 * @since       1.0.0
 */
final class AP_Performance {

    /**
     * Plugin version.
     *
     * @var string
     * @access  protected
     */
    protected $version = '1.1.0';

    /**
     * The single instance of the class.
     *
     * @var     AP_Performance
     * @since   1.0.0
     * @access  protected
     */
    protected static $_instance = null;

    /**
     * A dummy magic method to prevent class from being cloned.
     *
     * @since   1.0.0
     * @access  public
     */
    public function __clone() { _doing_it_wrong( __FUNCTION__, 'Cheatin&#8217; huh?', '1.0.0' ); }

    /**
     * A dummy magic method to prevent class from being unserialized.
     *
     * @since   1.0.0
     * @access  public
     */
    public function __wakeup() { _doing_it_wrong( __FUNCTION__, 'Cheatin&#8217; huh?', '1.0.0' ); }

    /**
     * Main instance.
     *
     * Ensures only one instance is loaded or can be loaded.
     *
     * @since   1.0.0
     * @access  public
     * @return  Main instance.
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor.
     *
     * @since   1.0.0
     * @access  public
     */
    public function __construct() {

        $this->file = __FILE__;
        $this->basename = plugin_basename( $this->file );

        $this->includes();
        $this->init_hooks();

        do_action( 'ap_performance_loaded' );
    }

    /**
     * Hook into actions and filters.
     *
     * @since   1.0.0
     * @access  private
     */
    private function init_hooks() {

        register_activation_hook( $this->file, array( 'AP_Performance_Data', 'install' ) );

        add_action( 'init', array( $this, 'init' ), 0 );

        add_filter( 'style_loader_tag', array( $this, 'customize_script_loader_tag' ) );
        add_filter( 'script_loader_tag', array( $this, 'customize_script_loader_tag' ) );

        if ( $this->is_request( 'frontend' ) ) {
            add_filter( 'script_loader_tag', array( $this, 'defer_scripts' ), 20, 2 );
            add_action( 'wp_enqueue_scripts', array( $this, 'disable_scripts' ), 99 );
            add_action( 'wp_default_scripts', array( $this, 'disable_jquery_migrate' ) );
        }
    }

    /**
     * Include required files.
     *
     * @since   1.0.0
     * @access  public
     */
    public function includes() {

        $dir = $this->get_plugin_path();

        require_once( $dir . 'includes/class-data.php' );

        if ( is_admin() ) {
            require_once( $dir . 'admin/class-admin.php' );
        }
    }

    /**
     * Init when WordPress Initialises.
     *
     * @since   1.0.0
     * @access  public
     */
    public function init() {
        
        // Before init action.
        do_action( 'ap_performance_before_init' );

        // Set up localisation.
        $this->load_plugin_textdomain();

        $this->disable_emojis();

        // Init action.
        do_action( 'ap_performance_init' );
    }

    /**
     * Remove type tag from scripts and styles.
     *
     * @since   1.0
     * @access  public
     */
    public function customize_script_loader_tag( $tag ) {
        return preg_replace( "/ type=['\"]text\/(javascript|css)['\"]/", '', $tag );
    }

    /**
     * Deffer loading of scripts.
     *
     * @since   1.0.0
     * @access  public
     */
    public function defer_scripts( $tag, $handle ) {

        $defer_scripts = get_option( 'ap_performance_defer_scripts', true );

        if ( ! $defer_scripts ) {
            return $tag;
        }

        $remove_defer_jquery = get_option( 'ap_performance_remove_defer_jquery', false );
        $async_scripts_handles = get_option( 'ap_performance_async_scripts_handles', '' );
        $async_handles = explode( ',', $async_scripts_handles );
        $async_handles = array_map( 'sanitize_text_field', $async_handles );

        $disable_scripts_handles = get_option( 'ap_performance_disable_defer_scripts_handles', '' );
        $disabled_scripts = explode( ',', $disable_scripts_handles );
        $disabled_scripts = array_map( 'sanitize_text_field', $disabled_scripts );        

        if ( $remove_defer_jquery ) {
            $disabled_scripts = array_merge( $disabled_scripts, array( 'jquery-core', 'jquery-migrate' ) );
        }

        if ( ! in_array( $handle, $disabled_scripts ) ) {
            if ( in_array( $handle, $async_handles ) ) {
                $tag = str_replace( " src=", " async src=", $tag );
            } else {
                $tag = str_replace( " src=", " defer src=", $tag );
            }
        }

        return $tag;
    }

    /**
     * Disable scripts from loading.
     *
     * @since   1.0.0
     * @access  public
     */
    public function disable_scripts() {

        $disable_wp_embed = get_option( 'ap_performance_disable_wp_embed', false );
        $disable_scripts_handles = get_option( 'ap_performance_disable_scripts_handles', '' );
        $disabled_scripts = explode( ',', $disable_scripts_handles );
        $disabled_scripts = array_map( 'sanitize_text_field', $disabled_scripts );

        if ( $disable_wp_embed ) {
            wp_deregister_script( 'wp-embed' );
        }

        if ( $disabled_scripts ) {
            foreach ( $disabled_scripts as $key => $handle ) {
                wp_deregister_script( $handle );
            }
        }
    }

    /**
     * Disable jquery migrate.
     *
     * @since   1.0.0
     * @access  public
     * @param   object $scripts
     */
    public function disable_jquery_migrate( $scripts ) {
        
        $disable_jquery_migrate = get_option( 'ap_performance_disable_jquery_migrate', false );

        if ( isset( $scripts->registered['jquery'] ) && $disable_jquery_migrate ) {
            $script = $scripts->registered['jquery'];

            if ( $script->deps ) {
                $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
            }
        }
     }

    /**
     * Disable emojis.
     *
     * @since   1.0.0
     * @access  public
     */
    public function disable_emojis() {

        $disable_emojis = get_option( 'ap_performance_disable_emojis', false );

        if ( $disable_emojis ) {
            remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
            remove_action( 'wp_print_styles', 'print_emoji_styles' );
        }
    }

    /**
     * Load Localisation files.
     *
     * @since   1.0.0
     * @access  public
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain( 'ap-performance', false, plugin_basename( dirname( $this->file ) ) . '/languages' );
    }

    /**
     * What type of request is this?
     *
     * @since   1.0.0
     * @param   string $type admin, ajax, cron or frontend.
     * @return  bool
     */
    private function is_request( $type ) {
        switch ( $type ) {
            case 'admin':
                return is_admin();
            case 'ajax':
                return defined( 'DOING_AJAX' );
            case 'cron':
                return defined( 'DOING_CRON' );
            case 'frontend':
                return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' ) && ! defined( 'REST_REQUEST' );
        }
    }

    /**
     * Get the plugin url.
     *
     * @since   1.0.0
     * @access  public
     * @return  string
     */
    public function get_plugin_url() {
        return plugin_dir_url( $this->file );
    }

    /**
     * Get the plugin path.
     *
     * @since   1.0.0
     * @access  public
     * @return  string
     */
    public function get_plugin_path() {
        return plugin_dir_path( $this->file );
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since   1.0.0
     * @access  public
     * @return  string
     */
    public function get_version() {
        return $this->version;
    }
}

/**
 * The main function responsible for returning the one true instance
 * to functions everywhere.
 *
 * @since   1.0.0
 * @access  public
 * @return  object The one true instance
 */
function ap_performance() {
    return AP_Performance::instance();
}

// let's start
ap_performance();
