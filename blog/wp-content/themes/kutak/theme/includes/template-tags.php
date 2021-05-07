<?php
/**
 * Custom template tags for this theme.
 *
 * @package     Kutak/Functions/Template_Tags
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*============================================
TEMPLATES
=============================================*/

if ( ! function_exists( 'apalodi_search_form' ) ) {
    /**
     * Output the search form.
     *
     * @since   1.0
     * @access  public
     */
    function apalodi_search_form() {
        apalodi_get_template( 'searchform' );
    }
}

if ( ! function_exists( 'apalodi_social_icons' ) ) {
    /**
     * Output the social icons.
     *
     * @since   1.0
     * @access  public
     */
    function apalodi_social_icons() {
        apalodi_get_template( 'social-icons' );
    }
}

if ( ! function_exists( 'apalodi_photoswipe_template' ) ) {
    /**
     * Output the photoswipe template.
     *
     * @since   1.0
     * @access  public
     */
    function apalodi_photoswipe_template() {
        apalodi_get_template( 'photoswipe' );
    }
}

/*============================================
BLOG
=============================================*/
if ( ! function_exists( 'apalodi_single_tags' ) ) {
    /**
     * Output the single tags.
     *
     * @since   1.0
     * @access  public
     */
    function apalodi_single_tags() {
        apalodi_get_template( 'single-tags' );
    }
}

if ( ! function_exists( 'apalodi_share_buttons' ) ) {
    /**
     * Output the share buttons.
     *
     * @since   1.0
     * @access  public
     */
    function apalodi_share_buttons() {
        apalodi_get_template( 'share-buttons' );
    }
}

if ( ! function_exists( 'apalodi_ap_share_buttons' ) ) {
    /**
     * Output the ap share buttons.
     *
     * @since   2.0
     * @access  public
     */
    function apalodi_ap_share_buttons() {
        apalodi_get_template( 'ap-share-buttons' );
    }
}

if ( ! function_exists( 'apalodi_related_posts' ) ) {
    /**
     * Output the related posts.
     *
     * @since   1.0
     * @access  public
     */
    function apalodi_related_posts() {
        apalodi_get_template( 'related-posts' );
    }
}
