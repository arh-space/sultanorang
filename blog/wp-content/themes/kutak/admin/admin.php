<?php
/**
 * Admin functions, definitions and includes.
 *
 * @package     Kutak/Admin
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$admin_dir = get_template_directory() . '/admin/';
require_once( $admin_dir . 'includes/class-admin-notices.php' );
require_once( $admin_dir . 'includes/class-walker-nav-menu-edit.php' );
require_once( $admin_dir . 'includes/class-tgm-plugin-activation.php' );
require_once( $admin_dir . 'includes/customizer.php' );
require_once( $admin_dir . 'includes/nav-menu.php' );
require_once( $admin_dir . 'includes/one-click-demo-importer.php' );

/**
 * Register and Enqueue CSS and JS.
 *
 * @since   1.0
 * @access  public
 * @author  apalodi
 */
function apalodi_admin_enqueue_scripts() {

    $version = apalodi_get_theme_info( 'version' );
    $admin_assets_dir = get_template_directory_uri() . '/admin/assets/';

    // Register CSS 
    wp_register_style( 'kutak-admin', $admin_assets_dir . 'css/admin.css', array(), $version );

    // Register JS
    wp_register_script( 'kutak-admin', $admin_assets_dir . 'js/admin.js', array(), $version, false );

    // Enqueue CSS
    wp_enqueue_style( 'kutak-admin' );

    // Enqueue JS
    wp_enqueue_script( 'kutak-admin' );

    wp_enqueue_media();

}
add_action( 'admin_enqueue_scripts', 'apalodi_admin_enqueue_scripts' );

/**
 * Delete old related posts transients.
 *
 * @since   2.0.0
 * @access  public
 */
function apalodi_delete_old_related_posts_transients() {
    global $wpdb;

    $has_deleted = get_option( 'kutak_deleted_old_transients', false );

    if ( ! $has_deleted ) {
        $like = $wpdb->esc_like( '_transient_related_posts_ids_' ) . '%';

        $wpdb->query( 
            $wpdb->prepare(
                "
                DELETE FROM {$wpdb->options}
                WHERE option_name LIKE %s
                ",
                $like
            )
        );

        update_option( 'kutak_deleted_old_transients', true );
    }
}
add_action( 'admin_init', 'apalodi_delete_old_related_posts_transients' );

/**
 * Register the required plugins for this theme.
 *
 * @since   1.0
 * @access  public
 * @author  apalodi
 */
function apalodi_register_required_plugins() {

    $theme_dir = get_template_directory() . '/theme/';

    $plugins = array(
        array(
            'name' => esc_html__( 'One Click Demo Import', 'kutak' ),
            'slug' => 'one-click-demo-import',
        ),
        array(
            'name' => esc_html__( 'Contact Form 7', 'kutak' ),
            'slug' => 'contact-form-7',
        ),
        array(
            'name' => esc_html__( 'AP Ads', 'kutak' ),
            'slug' => 'ap-ads',
            'source' => 'ap-ads.zip',
            'version' => '1.0.0',
        ),
        array(
            'name' => esc_html__( 'AP Popular Posts', 'kutak' ),
            'slug' => 'ap-popular-posts',
            'source' => 'ap-popular-posts.zip',
            'version' => '1.0.1',
        ),
        array(
            'name' => esc_html__( 'AP Share Buttons', 'kutak' ),
            'slug' => 'ap-share-buttons',
            'source' => 'ap-share-buttons.zip',
            'version' => '1.0.1',
        ),
        array(
            'name' => esc_html__( 'AP Performance', 'kutak' ),
            'slug' => 'ap-performance',
            'source' => 'ap-performance.zip',
            'version' => '1.1.0',
        ),
        array(
            'name' => esc_html__( 'Envato Market', 'kutak' ),
            'slug' => 'envato-market',
            'source' => 'envato-market.zip',
            'version' => '2.0.1',
        ),
    );

    $config = array(
        'id'           => 'kutak',
        'default_path' => $theme_dir . 'plugins-bundled/',
        'menu'         => 'kutak-install-plugins',
        'capability'   => 'edit_theme_options',
    );

    tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'apalodi_register_required_plugins' );

/**
 * Save default option on theme activation.
 *
 * @since   2.2
 * @access  public
 */
 function apalodi_on_theme_activation() {    
    add_option( 'kutak_theme_activated', time() );
}
add_action( 'after_switch_theme', 'apalodi_on_theme_activation' );

/**
 * Delete theme option on theme deactivation.
 *
 * @since   2.2
 * @access  public
 */
function apalodi_on_theme_deactivation() {
    delete_option( 'kutak_theme_activated' );
}
add_action( 'switch_theme', 'apalodi_on_theme_deactivation' );

/**
 * Display notice for theme rating.
 *
 * @since   2.2
 * @access  public
 */
 function apalodi_theme_rating_admin_notice() {

    if ( Apalodi_Admin_Notices::is_dismissed( 'theme_rating' ) ) {
        return;
    }

    if ( false === ( $activated_on = get_option( 'kutak_theme_activated', false ) ) ) {
        $activated_on = time();
        add_option( 'kutak_theme_activated', $activated_on );
        return;
    }

    $check = strtotime( '-3 day' );

    if ( $check < $activated_on ) {
        return;
    }

    echo '<div data-apalodi-dismissible="theme_rating" data-expiration="0" class="notice notice-info is-dismissible"><p>'. wp_kses_post( sprintf( __( 'If you like the Kutak theme please consider going to %s and rate it by selecting 5 stars.', 'kutak' ), '<a href="https://themeforest.net/downloads" target="_blank">https://themeforest.net/downloads</a>' ) ) .'</p></div>';
}
add_action( 'admin_notices', 'apalodi_theme_rating_admin_notice' );
