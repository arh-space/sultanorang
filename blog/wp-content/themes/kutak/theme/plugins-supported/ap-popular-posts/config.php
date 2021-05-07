<?php
/**
 * Kutak AP Popular Posts Integration.
 *
 * @package     Kutak/AP_Popular_Posts/Functions
 * @since       2.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Disable the old popular posts plugin.
 *
 * @since   2.0
 * @access  public
 */
function apalodi_ap_popular_posts_disable_kutak_popular_posts() {
    if ( function_exists( 'kutak_popular_posts_get_ids' ) ) {
        deactivate_plugins( 'kutak-popular-posts/kutak-popular-posts.php' );
    }
}
add_filter( 'admin_init', 'apalodi_ap_popular_posts_disable_kutak_popular_posts' );

/**
 * Change the custom template path for plugin templates.
 *
 * @since   2.0
 * @access  public
 * @param   string $path
 * @return  string $path
 */
function apalodi_ap_popular_posts_template_path( $path ) {
    return 'templates/ap-popular-posts';
}
add_filter( 'ap_popular_posts_template_path', 'apalodi_ap_popular_posts_template_path' );

/**
 * Hook into ap_popular_posts_widget_query_args to update post thumbnail cache
 * to lower the number of SQL queries.
 *
 * @since   2.0
 * @access  public
 * @param   array $args
 * @return  array $args
 */
function apalodi_ap_popular_posts_widget_query_args( $args ) {
    $args['update_post_thumbnail_cache'] = true;
    return $args;
}
add_filter( 'ap_popular_posts_widget_query_args', 'apalodi_ap_popular_posts_widget_query_args' );

/**
 * Add featured tab popular posts.
 *
 * @since   2.0
 * @access  public
 * @param   string $type
 */
function apalodi_ap_popular_posts_featured_tab( $tabs ) {
    $tabs['4'] = array(
        'key' => 'popular',
        'title' => __( 'Trending', 'kutak' )
    );

    return $tabs;
}
add_filter( 'apalodi_featured_tabs', 'apalodi_ap_popular_posts_featured_tab' );

/**
 * Add featured section popular posts content.
 *
 * @since   2.0
 * @access  public
 * @param   string $type
 */
function apalodi_ap_popular_posts_featured_section( $type ) {

    if ( 'popular' == $type ) {
        $args = array(
            'post_type' => 'post',
            'status' => 'publish',
            'posts_per_page' => '4',
            'post__in' => ap_popular_posts_get_ids( 3, 4 ),
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
add_action( 'apalodi_featured_section', 'apalodi_ap_popular_posts_featured_section' );
