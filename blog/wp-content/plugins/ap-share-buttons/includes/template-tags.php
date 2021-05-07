<?php
/**
 * Custom template tags that can be overwritten by themes.
 *
 * @package     AP_Share_Buttons/Functions
 * @since       1.0.0 
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'ap_share_buttons_template' ) ) {
    /**
     * Output the share buttons content.
     *
     * @since   1.0
     * @access  public
     * @param   array $args Pass args with the template load.
     */
    function ap_share_buttons_template( $args = array() ) {
        ap_share_buttons_get_template( 'share-buttons', $args );
    }
}
