<?php
/**
 * Contact Form 7 Integration.
 *
 * @package     Kutak/Contact_Form_7/Functions
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Register and Enqueue CSS and JS for Contact Form 7 plugin.
 *
 * @since   1.0
 * @access  public
 */
function apalodi_wpcf7_enqueue_scripts() {

    $assets_dir = get_template_directory_uri() . '/assets/';
    $version    = apalodi_get_theme_info( 'version' );

    // Dequeue CSS
    wp_dequeue_style( 'contact-form-7' );
    wp_dequeue_style( 'contact-form-7-rtl' );

    // Register CSS
    wp_register_style( 'contactform-7', $assets_dir . 'css/contact-form-7.css', array(), $version );   

    // Dequeue JS
    wp_dequeue_script( 'contact-form-7' );

    if ( is_apalodi_shortcode_active( 'contact-form-7' ) ) {
        wp_enqueue_style( 'contactform-7' );
        wp_enqueue_script( 'contact-form-7' );
    }
}
add_action( 'wp_enqueue_scripts', 'apalodi_wpcf7_enqueue_scripts', 20 );

/**
 * Add custom class to form response output.
 *
 * @since   1.0
 * @access  public
 * @param   string $output
 * @return  string $output
 */
function apalodi_wpcf7_form_response_output( $output ) {
    $output = str_replace( 'class="wpcf7-response-output', 'class="wpcf7-response-output message-info', $output );
    return $output;
}
add_filter( 'wpcf7_form_response_output', 'apalodi_wpcf7_form_response_output', 10 );
