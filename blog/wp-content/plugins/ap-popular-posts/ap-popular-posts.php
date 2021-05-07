<?php
/**
 * Plugin Name: AP Popular Posts
 * Description: Popular posts plugin.
 * Version: 1.0.1
 * Author: APALODI
 * Author URI: https://apalodi.com
 * Tags: popular, posts
 *
 * Text Domain: ap-popular-posts
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main AP_Popular_Posts Class.
 *
 * @package     AP_Popular_Posts
 * @since       1.0.0
 */
final class AP_Popular_Posts {

    /**
     * Plugin version.
     *
     * @var string
     * @access  protected
     */
    protected $version = '1.0.1';

    /**
     * The single instance of the class.
     *
     * @var     AP_Popular_Posts
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

        do_action( 'ap_popular_posts_loaded' );
    }

    /**
     * Hook into actions and filters.
     *
     * @since   1.0.0
     * @access  private
     */
    private function init_hooks() {

        register_activation_hook( $this->file, array( 'AP_Popular_Posts_Data', 'install' ) );

        add_action( 'after_setup_theme', array( $this, 'include_template_functions' ), 11 );
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

        require_once( $dir . 'includes/core-functions.php' );
        require_once( $dir . 'includes/conditional-functions.php' );

        require_once( $dir . 'includes/class-data.php' );
        require_once( $dir . 'includes/class-ajax.php' );
        require_once( $dir . 'includes/class-views.php' );
        require_once( $dir . 'includes/class-widget.php' );
        require_once( $dir . 'includes/class-frontend-scripts.php' );

        if ( is_admin() ) {
            require_once( $dir . 'admin/class-admin.php' );
        }

        // Load class instances.
        $this->ajax = new AP_Popular_Posts_AJAX();
        $this->views = new AP_Popular_Posts_Views();
    }

    /**
     * Include template functions.
     *
     * This makes them pluggable by plugins and themes.
     *
     * @since   1.0.0
     * @access  public
     */
    public function include_template_functions() {

        $dir = $this->get_plugin_path();

        require_once( $dir . 'includes/template-tags.php' );
    }

    /**
     * Init when WordPress Initialises.
     *
     * @since   1.0.0
     * @access  public
     */
    public function init() {
        
        // Before init action.
        do_action( 'ap_popular_posts_before_init' );

        // Set up localisation.
        $this->load_plugin_textdomain();

        // Init action.
        do_action( 'ap_popular_posts_init' );
    }

    /**
     * Register widgets.
     *
     * @since   1.0.0
     * @access  public
     */
    public function register_widgets() {
        register_widget( 'AP_Popular_Posts_Widget' );        
    }

    /**
     * Load Localisation files.
     *
     * @since   1.0.0
     * @access  public
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain( 'ap-popular-posts', false, plugin_basename( dirname( $this->file ) ) . '/languages' );
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
     * Get the template path.
     *
     * @since   1.0.0
     * @access  public
     * @return  string
     */
    public function get_template_path() {
        return trailingslashit( apply_filters( 'ap_popular_posts_template_path', 'ap-popular-posts' ) );
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
function ap_popular_posts() {
    return AP_Popular_Posts::instance();
}

// let's start
ap_popular_posts();
