<?php
/**
 * Plugin Name: AP Share Buttons
 * Description: Share buttons plugin.
 * Version: 1.0.1
 * Author: APALODI
 * Author URI: https://apalodi.com
 * Tags: share, sharing
 *
 * Text Domain: ap-share-buttons
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main AP_Share_Buttons Class.
 *
 * @package     AP_Share_Buttons
 * @since       1.0.0
 */
final class AP_Share_Buttons {

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
     * @var     AP_Share_Buttons
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

        do_action( 'ap_share_buttons_loaded' );
    }

    /**
     * Hook into actions and filters.
     *
     * @since   1.0.0
     * @access  private
     */
    private function init_hooks() {

        register_activation_hook( $this->file, array( 'AP_Share_Buttons_Data', 'install' ) );

        add_action( 'after_setup_theme', array( $this, 'include_template_functions' ), 11 );
        add_action( 'init', array( $this, 'init' ), 0 );

        add_filter( 'kses_allowed_protocols', array( $this, 'kses_allowed_protocols' ) );
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

        require_once( $dir . 'includes/class-data.php' );
        require_once( $dir . 'includes/class-open-graph.php' );

        require_once( $dir . 'admin/class-customizer.php' );
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
        do_action( 'ap_share_buttons_before_init' );

        // Set up localisation.
        $this->load_plugin_textdomain();

        // Init action.
        do_action( 'ap_share_buttons_init' );
    }

    /**
     * Load Localisation files.
     *
     * @since   1.0.0
     * @access  public
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain( 'ap-share-buttons', false, plugin_basename( dirname( $this->file ) ) . '/languages' );
    }

    /**
     * Get the share buttons choices.
     *
     * @since   1.0.0
     * @access  public
     * @return  array $choices
     */
    public function get_choices() {

        $choices = array(
            'facebook' => 'Facebook', 
            'twitter' => 'Twitter',
            'facebook-messenger' => 'Facebook Messenger (on mobile)',
            'whatsapp' => 'WhatsApp (on mobile)',
            'viber' => 'Viber (on mobile)',
            'google-plus' => 'Google Plus', 
            'pinterest' => 'Pinterest', 
            'tumblr' => 'Tumblr', 
            'reddit' => 'Reddit', 
            'linkedin' => 'Linkedin', 
            'vk' => 'VK', 
            'mail' => 'Mail',
            'print' => 'Print',
        );

        return apply_filters( 'ap_share_buttons_choices', $choices );
    }

    /**
     * Add new protocols for wp_kses.
     *
     * @since   1.0.0
     * @access  public
     * @param   array $protocols
     * @return  array $protocols
     */
    public function kses_allowed_protocols( $protocols ) {

        $protocols[] = 'fb-messenger';
        $protocols[] = 'whatsapp';
        $protocols[] = 'viber';

        return $protocols;
    }

    /**
     * Get the query url for share icons.
     *
     * @since   1.0.0
     * @access  public
     * @param   string $social What social url to build
     * @param   int $post_id If outside loop add post ID
     * @return  string $url Url query
     */
    public function get_link( $social, $post_id = null ) {

        $url = '';
        $post = get_post( $post_id ); // used to get the raw title, get_the_title() returns the title with filters

        if ( ! $post ) {
            return false;
        }

        $permalink = rawurlencode( get_the_permalink() );
        $title = rawurlencode( $post->post_title );

        switch ( $social ) {
            case 'facebook':
                $url = add_query_arg( 
                    array(
                        'u' => $permalink,
                    ),
                    'https://www.facebook.com/sharer/sharer.php'
                );
                break;
            case 'twitter':
                $url = add_query_arg( 
                    array(
                        'url' => $permalink,
                        'text' => $title
                    ),
                    'https://twitter.com/intent/tweet'
                );
                break;
            case 'google-plus':
                $url = add_query_arg( 
                    array(
                        'url' => $permalink
                    ),
                    'https://plus.google.com/share'
                );
                break;
            case 'facebook-messenger':
                $url = add_query_arg( 
                    array(
                        'link' => $permalink,
                    ),
                    'fb-messenger://share'
                );
                break;
            case 'whatsapp':
                $url = add_query_arg( 
                    array(
                        'text' => $permalink,
                    ),
                    'whatsapp://send'
                );
                break;
            case 'viber':
                $url = add_query_arg( 
                    array(
                        'text' => $permalink,
                    ),
                    'viber://forward'
                );
                break;
            case 'pinterest':
                $url = add_query_arg( 
                    array(
                        'url' => $permalink,
                        'description' => $title
                    ),
                    'https://pinterest.com/pin/create/button'
                );
                break;
            case 'tumblr':
                $url = add_query_arg( 
                    array(
                        'url' => $permalink,
                        'name' => $title
                    ),
                    'https://www.tumblr.com/share/link'
                );
                break;
            case 'linkedin':
                $url = add_query_arg( 
                    array(
                        'mini' => 'true',
                        'url' => $permalink,
                        'title' => $title
                    ),
                    'https://www.linkedin.com/shareArticle'
                );
                break;
            case 'reddit':
                $url = add_query_arg( 
                    array(
                        'url' => $permalink,
                        'title' => $title
                    ),
                    'https://www.reddit.com/submit'
                );
                break;
            case 'vk':
                $url = add_query_arg( 
                    array(
                        'url' => $permalink,
                        'title' => $title
                    ),
                    'https://vk.com/share.php'
                );
                break;
            case 'mail':
                $url = add_query_arg( 
                    array(
                        'subject' => $title,
                        'body' => $permalink
                    ),
                    'mailto:'
                );
                break;
            
            default:
                break;
        }

        return apply_filters( 'ap_share_buttons_link', $url, $social, $permalink, $title );
    }

    /**
     * Get the onlick attribute for share icons.
     *
     * @since   1.0.0
     * @access  public
     * @param   string $social What social onclick to build
     * @return  string $onclick Onclick attribute
     */
    public function get_onclick( $social ) {

        switch ( $social ) {
            case 'facebook-messenger':
            case 'whatsapp':
            case 'viber':
            case 'mail':
                $onclick = '';
                break;
            case 'print':
                $onclick = 'window.print(); return false;';
                break;
            default:
                $onclick = "window.open(this.href, '_blank', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=500,top=10,left=10'); return false;";
                break;
        }

        return apply_filters( 'ap_share_buttons_onclick', $onclick, $social );
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
        return trailingslashit( apply_filters( 'ap_share_buttons_template_path', 'ap-share-buttons' ) );
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
function ap_share_buttons() {
    return AP_Share_Buttons::instance();
}

// let's start
ap_share_buttons();
