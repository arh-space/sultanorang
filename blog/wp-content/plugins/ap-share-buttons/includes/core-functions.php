<?php
/**
 * Core Functions.
 *
 * @package     AP_Share_Buttons/Functions
 * @since       1.0.0 
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Load a template part with passing arguments.
 *
 * Makes it easy for a theme to reuse sections of code in a easy to overload way
 * for child themes.
 *
 * @since   1.0
 * @access  public
 * @param   string  $slug   The slug name for the generic template.
 * @param   array   $args   Pass args with the template load.
 */
function ap_share_buttons_get_template( $slug, $args = array() ) {

    $template_path = ap_share_buttons()->get_template_path();
    $templates = array( "{$template_path}/{$slug}.php" );
    $template = locate_template( $templates, false, false );

    if ( ! $template ) {
        $template = ap_share_buttons()->get_plugin_path() . '/templates/' . $slug . '.php';
    }

    include( $template );
}

/**
 * Get share buttons option values.
 *
 * @since   1.0.0
 * @access  public
 * @return  array $buttons
 */
function ap_share_buttons_get_values() {

    $choices = ap_share_buttons()->get_choices();
    $values = get_option( 'ap_share_buttons', array( 'facebook', 'twitter', 'mail' ) );
    $buttons = array();

    foreach ( $values as $key => $value ) {
        if ( array_key_exists( $value, $choices ) ) {
            $buttons[] = $value;
        }
    }

    return $buttons;
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
function ap_share_buttons_get_link( $social, $post_id = null ) {
    return ap_share_buttons()->get_link( $social, $post_id );
}

/**
 * Output the url for share icons.
 *
 * @since   1.0.0
 * @access  public
 * @param   string $social What social url to build
 * @param   int $post_id If outside loop add post ID
 * @return  string $url Url query
 */
function ap_share_buttons_link( $social, $post_id = null ) {
    $url = ap_share_buttons_get_link( $social, $post_id );
    echo esc_url( $url );
}

/**
 * Get the onlick attribute for share icons.
 *
 * @since   1.0.0
 * @access  public
 * @param   string $social What social onclick to build
 * @return  string $onclick Onclick attribute
 */
function ap_share_buttons_get_onclick( $social ) {
    return ap_share_buttons()->get_onclick( $social );
}
