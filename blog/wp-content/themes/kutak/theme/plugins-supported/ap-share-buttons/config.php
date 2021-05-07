<?php
/**
 * AP Share Buttons Integration.
 *
 * @package     Kutak/AP_Share_Buttons/Functions
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Disable the old share buttons plugin.
 *
 * @since   2.0
 * @access  public
 */
function apalodi_ap_share_button_disable_kutak_share_buttons() {
    if ( function_exists( 'kutak_share_link' ) ) {
        deactivate_plugins( 'kutak-share-buttons/kutak-share-buttons.php' );

        $share_buttons = apalodi_get_share_buttons();
        update_option( 'ap_share_buttons', $share_buttons );
    }
}
add_filter( 'admin_init', 'apalodi_ap_share_button_disable_kutak_share_buttons' );

/**
 * Change the custom template path for plugin templates.
 *
 * @since   2.0
 * @access  public
 * @param   string $type
 */
function apalodi_ap_share_buttons_template_path( $path ) {
    return 'templates';
}
add_filter( 'ap_share_buttons_template_path', 'apalodi_ap_share_buttons_template_path' );

/**
 * Change the image size for open graph image.
 *
 * @since   2.0
 * @access  public
 * @param   string $size
 */
function apalodi_ap_share_buttons_open_graph_image_size( $size ) {
    return 'kutak-blog-1520';
}
add_filter( 'ap_share_buttons_open_graph_image_size', 'apalodi_ap_share_buttons_open_graph_image_size' );

/**
 * Change the choices for the share buttons.
 *
 * @since   2.0
 * @access  public
 * @param   string $size
 */
function apalodi_ap_share_buttons_choices( $choices ) {
    unset( $choices['print'] );
    return $choices;
}
add_filter( 'ap_share_buttons_choices', 'apalodi_ap_share_buttons_choices' );

/**
 * Output share buttons after single content.
 *
 * @since   1.0
 * @access  public
 */
add_action( 'apalodi_after_single_content', 'apalodi_ap_share_buttons' );
