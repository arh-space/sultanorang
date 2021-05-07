<?php
/**
 * Admin Notices
 *
 * @package     Kutak/Admin/Classes
 * @since       2.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Apalodi_Admin_Notices' ) ) :

class Apalodi_Admin_Notices {

    /**
     * Init hooks.
     */
    public static function init() {
        add_action( 'admin_enqueue_scripts', array( __CLASS__, 'load_script' ) );
        add_action( 'wp_ajax_apalodi_dismiss_admin_notice', array( __CLASS__, 'dismiss_admin_notice' ) );
    }

    /**
     * Enqueue javascript and variables.
     *
     * @since   2.0.0
     * @access  public
     */
    public static function load_script() {

        if ( is_customize_preview() ) {
            return;
        }

        $version = apalodi_get_theme_info( 'version' );
        $admin_assets_dir = get_template_directory_uri() . '/admin/assets/';

        // Register JS
        wp_register_script( 'apalodi-dismissible-notices', $admin_assets_dir . 'js/dismiss-notice.js', array( 'jquery', 'common' ), $version, true );

        // Enqueue JS
        wp_enqueue_script( 'apalodi-dismissible-notices' );

        // Localize scripts
        wp_localize_script(
            'apalodi-dismissible-notices',
            'apalodi_dismissible_notices',
            array(
                'nonce' => wp_create_nonce( 'apalodi-dismissible-notice' ),
            )
        );
    }

    /**
     * Handles Ajax request to persist notices dismissal.
     *
     * @since   2.0.0
     * @access  public
     */
    public static function dismiss_admin_notice() {

        $notice = sanitize_text_field( $_POST['notice'] );
        $expiration = sanitize_text_field( $_POST['expiration'] );

        check_ajax_referer( 'apalodi-dismissible-notice', 'nonce' );

        $expiration = is_numeric( $expiration ) ? $expiration : 1;
        $expiration = $expiration < 0 ? 1 : $expiration;
        $expiration = $expiration * DAY_IN_SECONDS;

        set_transient( 'kutak_admin_notice_' . $notice, '1', $expiration );
        wp_die();
    }

    /**
     * Is admin notice dismissed
     *
     * @since   2.0.0
     * @access  public
     * @param   string $notice
     * @return  bool
     */
    public static function is_dismissed( $notice ) {

        if ( false === get_transient( 'kutak_admin_notice_' . $notice ) ) {
            return false;
        }

        return true;
    }
}

endif;

Apalodi_Admin_Notices::init();
