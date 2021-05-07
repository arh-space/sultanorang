<?php
/**
 * Theme Deprecated Functions.
 *
 * @package     Kutak/Functions
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Returns true if lazy load is enabled.
 *
 * @since   1.0
 * @deprecated 2.0 Use is_apalodi_lazy_load_media()
 * @access  public
 * @return  bool
 */
function is_apalodi_lazy_load() {
    _deprecated_function( __FUNCTION__, '2.0', 'is_apalodi_lazy_load_media()' );
    return is_apalodi_lazy_load_media();
}

/**
 * Returns true if there are more posts to be loaded.
 *
 * @since   1.0
 * @deprecated 2.0
 * @access  public
 * @return  bool
 */
function has_apalodi_pagination() {
    _deprecated_function( __FUNCTION__, '2.0' );
}

/**
 * Get the load more pagination args.
 *
 * @since   1.0
 * @deprecated 2.0
 * @access  public
 * @return  string $data
 */
function apalodi_load_more_data() {
    _deprecated_function( __FUNCTION__, '2.0' );
}

 /**
 * Returns true if there are share buttons selected.
 *
 * @since   1.0
 * @deprecated 2.0
 * @access  public
 * @return  bool
 */
function has_apalodi_share_buttons() {
    _deprecated_function( __FUNCTION__, '2.0' );
    return false;
}

/**
 * Retrieve the post thumbnail.
 *
 * @since   1.0
 * @deprecated 2.0 Use get_the_post_thumbnail()
 * @access  public
 * @param   int|WP_Post $post Optional. Post ID or WP_Post object.  Default is global `$post`.
 * @param   array|string $size Accepts any valid image size, or array of width, height in pixels and crop values (in that order)
 * @param   array $attr Array of attributes
 * @return  string $html The post thumbnail image tag.
 */
function apalodi_get_the_post_thumbnail( $post = null, $size = 'post-thumbnail', $attr = array() ) {
    _deprecated_function( __FUNCTION__, '2.0', 'get_the_post_thumbnail()' );
    return get_the_post_thumbnail( $post, $size, $attr );
}

/**
 * Display the post thumbnail. Must be used in a loop.
 *
 * @since   1.0
 * @deprecated 2.0 Use the_post_thumbnail()
 * @access  public
 * @param   array|string $size Accepts any valid image size, or array of width, height in pixels and crop values (in that order)
 * @param   array $attr Array of attributes
 */
function apalodi_the_post_thumbnail( $size = 'post-thumbnail', $attr = array() ) {
    _deprecated_function( __FUNCTION__, '2.0', 'the_post_thumbnail()' );
    echo get_the_post_thumbnail( null, $size, $attr );
}
