<?php
/**
 * Plugin Name: AP Ads
 * Description: Add ads to your site.
 * Version: 1.0.0
 * Author: APALODI
 * Author URI: https://apalodi.com
 * Tags: ads
 *
 * Text Domain: ap-ads
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main AP_Ads Class.
 *
 * @package     AP_Ads
 * @since       1.0.0
 */
final class AP_Ads {

    /**
     * Plugin version.
     *
     * @var string
     * @access  protected
     */
    protected $version = '1.0.0';

    /**
     * The single instance of the class.
     *
     * @var     AP_Ads
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

        $this->includes();
        $this->init_hooks();

        do_action( 'ap_ads_loaded' );
    }

    /**
     * Hook into actions and filters.
     *
     * @since   1.0.0
     * @access  private
     */
    private function init_hooks() {
        add_action( 'init', array( $this, 'init' ), 0 );
        add_action( 'widgets_init', array( $this, 'register_widgets' ) );
    }

    /**
     * Include required files.
     *
     * @since   1.0.0
     * @access  public
     */
    public function includes() {

        $dir = $this->get_plugin_path();

        require_once( $dir . 'includes/class-generate-widget-options.php' );
        require_once( $dir . 'includes/class-widget-ads.php' );

        $this->option = new AP_Ads_Generate_Widget_Options();
    }

    /**
     * Init when WordPress Initialises.
     *
     * @since   1.0.0
     * @access  public
     */
    public function init() {

        // Before init action.
        do_action( 'ap_ads_before_init' );

        // Set up localisation.
        $this->load_plugin_textdomain();

        // Init action.
        do_action( 'ap_ads_init' );
    }

    /**
     * Register widgets.
     *
     * @since   1.0.0
     * @access  public
     */
    public function register_widgets() {
        register_widget( 'AP_Ads_Widget' );
    }

    /**
     * Load Localisation files.
     *
     * @since   1.0.0
     * @access  public
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain( 'ap-ads', false, plugin_basename( dirname( $this->file ) ) . '/languages' );
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
function ap_ads() {
    return AP_Ads::instance();
}

// let's start
ap_ads();
