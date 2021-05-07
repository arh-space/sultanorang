<?php
/**
 * Cache Functions.
 *
 * @package     Kutak/Functions
 * @since       2.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Hook into the_posts to update post thumbnail cache if
 * update_post_thumbnail_cache is set to lower the number of SQL queries.
 *
 * @since   2.0
 * @access  public
 * @param   object $posts Posts
 * @param   object $query Query object
 * @return  object $posts Posts
 */
function apalodi_update_post_thumbnail_cache( $posts, $query ) {

    if ( $query->get( 'update_post_thumbnail_cache' ) ) {
        update_post_thumbnail_cache( $query );
    }

    return $posts;
}
add_action( 'the_posts', 'apalodi_update_post_thumbnail_cache', 10, 2 );

/**
 * Hook into the_content to update gallery items cache to lower the number of SQL queries.
 *
 * @since   2.0
 * @access  public
 * @param   string $content
 * @return  string $content
 */
function apalodi_prime_gallery_items_cache( $content ) {
    
    $ids = array();
    preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );

    if ( ! empty( $matches ) ) {
        foreach ( $matches as $shortcode ) {
            if ( 'gallery' === $shortcode[2] ) {
                $atts = shortcode_parse_atts( $shortcode[3] );
                if ( isset( $atts['ids'] ) ) {
                    $array_ids = explode( ',', $atts['ids'] );
                    foreach ( $array_ids as $key => $id ) {
                        $ids[] = $id;
                    }
                }
            }
        }
    }

    if ( $ids ) {
        _prime_post_caches( $ids, false, true );
    }

    return $content;
}
add_filter( 'the_content', 'apalodi_prime_gallery_items_cache' );

/**
 * Get nav menu items that will have it's own menu cache results.
 *
 * If there are taxonomy items in menu save those items so when that taxonomy
 * is viewed show cached nav menu for that taxonomy for all menu classes to work.
 *
 * @since   2.0
 * @access  public
 * @param   array   $items  An array of menu item post objects.
 * @param   object  $menu   The menu object.
 * @param   array   $args   An array of arguments used to retrieve menu item objects.
 * @return  array   $items  An array of menu item post objects.
 */
function apalodi_get_nav_menu_items_cache_items( $items, $menu, $args ) {

    $cache_items = array();

    foreach ( $items as $key => $item ) {
        $cache_items[ $item->type ][ $item->object ][] = $item->object_id;
    }

    $cached_items = get_option( 'apalodi_kutak_nav_menu_cached_items', array() );
    $cached_items[ $menu->term_id ] = $cache_items;
    update_option( 'apalodi_kutak_nav_menu_cached_items', $cached_items );

    return $items;

}
// add_filter( 'wp_get_nav_menu_items', 'apalodi_get_nav_menu_items_cache_items', 10, 3 );

/**
 * Short-circuit the wp_nav_menu() output if we have cached output ready.
 *
 * @param   string|null     $output     Nav menu output to short-circuit with. Default null.
 * @param   stdClass        $args       An object containing wp_nav_menu() arguments.
 * @return  string|null     Nav menu    output to short-circuit with. Passthrough (default null) if we don’t have a cached version.
 */
function apalodi_pre_wp_nav_menu( $output, $args ) {

    if ( $args->theme_location && ( $locations = get_nav_menu_locations() ) && isset( $locations[ $args->theme_location ] ) ) {
        
        $menu_id = $locations[ $args->theme_location ];
        $location = $args->theme_location;
        $header_type = apalodi_get_header_type();

        if ( ! is_customize_preview() && ( $cached_menu = get_option( "apalodi_kutak_nav_menu_cached_{$menu_id}_{$location}_{$header_type}" ) ) ) {
            return $cached_menu;
        }
    }

    return $output;
}
add_filter( 'pre_wp_nav_menu', 'apalodi_pre_wp_nav_menu', 10, 2 );

/**
 * Cache nav menus if cache_results is enabled in args.
 *
 * @since   2.0
 * @access  public
 * @param   string $menu Menu
 * @return  array $args Menu args
 */
function apalodi_cache_wp_nav_menu( $menu, $args ) {
    global $wp_query;

    if ( $args->cache_results && ! is_customize_preview() ) {

        $menu_id = $args->menu->term_id;
        $location = $args->theme_location;
        $header_type = apalodi_get_header_type();

        // $cached_items = get_option( 'apalodi_plio_nav_menu_cached_items', array() );
        // $cache_key = 'all';

        // print_r( $wp_query );
        // print_r( $cached_items );
        // if ( isset( $cached_items[ $menu_id ] ) ) {
        //     $items = $cached_items[ $menu_id ];
        //     // print_r( $args );

        //     if ( is_tax() ) {
        //         $id = $wp_query->queried_object_id;
        //         $taxonomy = $wp_query->queried_object->taxonomy;
        //         if ( in_array( $id, $items['taxonomy'][ $taxonomy ] ) ) {
        //             $cache_key = $taxonomy . '_' . $id;
        //         } else {
        //             $cache_key = $taxonomy;
        //         }
        //     }

        //     if ( is_singular() ) {
        //         $id = get_the_ID();
        //         $type = get_post_type();
        //         if ( in_array( $id, $items['post_type'][ $type ] ) ) {
        //             $cache_key = $type . '_' . $id;
        //         } else {
        //             $cache_key = $type;
        //         }
        //     }

            // $cached_menu = get_option( "apalodi_plio_nav_menu_cached_{$menu_id}" );
            // $cached_menu[ $cache_key ] = $menu;
        // }

        update_option( "apalodi_kutak_nav_menu_cached_{$menu_id}_{$location}_{$header_type}", $menu );
    }

    return $menu;
}
add_filter( 'wp_nav_menu', 'apalodi_cache_wp_nav_menu', 10, 2 );

/**
 * Clears the menu cache.
 *
 * Fires after a navigation menu has been successfully updated.
 *
 * @since   2.0
 * @access  public
 * @param   int $menu_id ID of the updated menu.
 */
function apalodi_wp_update_nav_menu( $menu_id ) {

    $theme_nav_locations = get_nav_menu_locations();
    $header_type = apalodi_get_header_type();

    foreach ( $theme_nav_locations as $location => $id ) {
        if ( $id == $menu_id ) {
            delete_option( "apalodi_kutak_nav_menu_cached_{$menu_id}_{$location}_{$header_type}" );
        }
    }
}
add_action( 'wp_update_nav_menu', 'apalodi_wp_update_nav_menu' );

/**
 * Save in options pages with tagmap template that will be 
 * used for a link to tagmap page.
 *
 * @since   2.0.0
 * @access  public
 * @param   int    $meta_id    ID of updated metadata entry.
 * @param   int    $object_id  Object ID.
 * @param   string $meta_key   Meta key.
 * @param   mixed  $meta_value Meta value.
 */
function apalodi_save_page_template_options( $meta_id, $post_id, $meta_key, $meta_value ) {

    if ( $meta_key === '_wp_page_template' ) {

        $page_template = get_page_template_slug( $post_id );

        if ( 'page-templates/tagmap.php' === $page_template ) {
            update_option( 'apalodi_tagmap_page_id', $post_id );            
        }
    }
}
add_action( 'added_post_meta', 'apalodi_save_page_template_options', 10, 4 );
add_action( 'updated_post_meta', 'apalodi_save_page_template_options', 10, 4 );
