<?php
/**
 * Kutak Share Buttons Integration.
 *
 * @package     Kutak/KutakShareButtons/Functions
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Display notice to use the new plugin for popular posts.
 *
 * @since   1.0
 * @access  public
 * @param   string $type
 */
function apalodi_kutak_share_buttons_plugin_notice() {

    if ( Apalodi_Admin_Notices::is_dismissed( 'kutak_share_buttons_deprecated' ) ) {
        return;
    }

    if ( class_exists( 'AP_Share_Buttons', false ) ) {
        return;
    }

    echo '<div data-apalodi-dismissible="kutak_share_buttons_deprecated" data-expiration="3" class="notice notice-info is-dismissible"><p>'. wp_kses_post( sprintf( __( 'You are using %s plugin. Please install and activate the new and better plugin %s and then uninstall the old one.', 'kutak' ), '<strong><em>Kutak Share Buttons</em></strong>', '<strong><em>AP Share Buttons</em></strong>' ) ) .'</p></div>';
}
add_action( 'admin_notices', 'apalodi_kutak_share_buttons_plugin_notice' );

/**
 * Add new protocols for wp_kses.
 *
 * @since   1.0
 * @access  public
 * @param   array $protocols
 * @return  array $protocols
 */
function apalodi_kses_allowed_protocols( $protocols ) {
    $protocols[] = 'fb-messenger';
    $protocols[] = 'whatsapp';
    $protocols[] = 'viber';
    return $protocols;
}
add_filter( 'kses_allowed_protocols', 'apalodi_kses_allowed_protocols' );

/**
 * Output share buttons after single content.
 *
 * @since   1.0
 * @access  public
 */
add_action( 'apalodi_after_single_content', 'apalodi_share_buttons', 10 );
