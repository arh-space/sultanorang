<?php
/**
 * Kutak Popular Posts Integration.
 *
 * @package     Kutak/KutakPopularPosts/Functions
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
function apalodi_kutak_popular_posts_plugin_notice() {

    if ( Apalodi_Admin_Notices::is_dismissed( 'kutak_popular_posts_deprecated' ) ) {
        return;
    }

    if ( class_exists( 'AP_Popular_Posts', false ) ) {
        return;
    }

    echo '<div data-apalodi-dismissible="kutak_popular_posts_deprecated" data-expiration="3" class="notice notice-info is-dismissible"><p>'. wp_kses_post( sprintf( __( 'You are using %s plugin. Please install and activate the new and better plugin %s and then uninstall the old one.', 'kutak' ), '<strong><em>Kutak Popular Posts</em></strong>', '<strong><em>AP Popular Posts</em></strong>' ) ) .'</p></div>';
}
add_action( 'admin_notices', 'apalodi_kutak_popular_posts_plugin_notice' );

/**
 * Add featured tab popular posts.
 *
 * @since   1.0
 * @access  public
 * @param   string $type
 */
function apalodi_popular_posts_featured_tab( $tabs ) {
    $tabs['4'] = array(
        'key' => 'popular',
        'title' => __( 'Trending', 'kutak' )
    );

    return $tabs;
}
add_filter( 'apalodi_featured_tabs', 'apalodi_popular_posts_featured_tab' );

/**
 * Add featured section popular posts content.
 *
 * @since   1.0
 * @access  public
 * @param   string $type
 */
function apalodi_popular_posts_featured_section( $type ) {

    if ( 'popular' == $type ) {
        $args = array(
            'post_type' => 'post',
            'status' => 'publish',
            'posts_per_page' => '4',
            'post__in' => kutak_popular_posts_get_ids( 7, 4 ),
            'update_post_thumbnail_cache' => true,
            'orderby' => 'post__in',
            'no_found_rows' => true,
            'ignore_sticky_posts' => true,
        );

        $popular = new WP_Query( $args );

        while ( $popular->have_posts() ) : 
            $popular->the_post();
            apalodi_get_template( 'content-small' );
        endwhile;

        wp_reset_postdata(); 
    }
}
add_action( 'apalodi_featured_section', 'apalodi_popular_posts_featured_section' );
