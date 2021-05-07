<?php
/**
 * Theme Template Functions.
 *
 * @package     Kutak/Functions
 * @since       2.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Adds custom classes to the array of body classes.
 *
 * @since   2.0
 * @access  public
 * @param   array $classes Classes for the element.
 * @return  array $classes
 */
function apalodi_get_body_classes( $classes ) {

    // Add class if sidebar is used in posts.
    if ( is_active_sidebar( 'sidebar-posts' ) && is_singular( 'post' ) ) {
        $classes[] = 'has-sidebar';
    }

    $classes[] = 'header-' . apalodi_get_header_type();

    return array_filter( $classes );
}
add_filter( 'body_class', 'apalodi_get_body_classes' );
