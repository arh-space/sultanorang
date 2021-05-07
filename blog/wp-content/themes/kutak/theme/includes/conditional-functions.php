<?php
/**
 * Theme Conditional Functions.
 *
 * @package     Kutak/Functions
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*============================================
GLOBAL
=============================================*/
/**
 * Returns true if debug enabled
 *
 * @since   1.0
 * @access  public
 * @return  bool
 */
function is_apalodi_debug() {
    return apply_filters( 'apalodi_debug', false );
}

/**
 * Returns true if lazy load media is enabled.
 *
 * @since   2.0
 * @access  public
 * @return  bool
 */
function is_apalodi_lazy_load_media() {

    $do_lazy = get_theme_mod( 'lazy_load_media', true ) ? true : false;
    $is_lazy = true;

    // Don't LazyLoad if the thumbnail is in admin, a feed, REST API, a post preview or amp-wp content.
    if (
        is_admin() || is_feed() || is_preview() || is_customize_preview()
        || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) 
        || ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() )
        || ! $do_lazy 
    ) {
        $is_lazy = false;
    }

    return apply_filters( 'apalodi_lazy_load_media', $is_lazy, $do_lazy );
}

/**
 * Returns true if it's posts archives.
 *
 * @since   2.0
 * @access  public
 * @return  bool
 */
function is_apalodi_posts_archive() {
    return ( is_category() || is_tag() || is_author() || is_date() );
}

/**
 * Returns true if it's main blog page or an archive.
 *
 * @since   1.0
 * @access  public
 * @return  bool
 */
function is_apalodi_posts_page() {
    return ( is_home() || is_apalodi_posts_archive() );
}

/**
 * Returns true if it's blog archive paged page.
 *
 * @since   1.0
 * @access  public
 * @return  bool
 */
function is_apalodi_posts_archive_paged() {
    return ( is_apalodi_posts_page() && is_paged() );
}

/**
 * Returns true when shortcode is active on page.
 *
 * @since   1.0
 * @access  public
 * @param   string $shortcode
 * @return  bool
 */
function is_apalodi_shortcode_active( $shortcode ) {
    global $post;
    return ( $post ) ? has_shortcode( $post->post_content, $shortcode ) : false;
}


/*============================================
HEADER
=============================================*/
/**
 * Returns true if type is selected.
 *
 * @since   2.0
 * @access  public
 * @param   string $type - Header Type
 * @return  bool
 */
function is_apalodi_header_type( $type ) {
    return $type === apalodi_get_header_type() ? true : false;
}

/**
 * Returns true if there is selected logo.
 *
 * @since   1.0
 * @access  public
 * @param   string $type - Logo Type
 * @return  bool
 */
function has_apalodi_logo( $type ) {
    return apalodi_get_logo( $type ) ? true : false;
}


/*============================================
SOCIAL
=============================================*/
 /**
 * Returns true if there are social icons selected.
 *
 * @since   1.0
 * @access  public
 * @return  bool
 */
function has_apalodi_social_icons() {
    $social_buttons = apalodi_get_social_icons();
    return ( $social_buttons ) ? true : false;
}
