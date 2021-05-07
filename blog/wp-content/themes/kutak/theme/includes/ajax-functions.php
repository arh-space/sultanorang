<?php
/**
 * Ajax Functions.
 *
 * @package     Kutak/Functions
 * @since       1.0.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Get Ajax url.
 *
 * @since   2.0.0
 * @access  public
 * @param   string $action Optional.
 * @return  string
 */
function apalodi_get_ajax_url( $action = '%%action%%' ) {
    return add_query_arg( 'apalodi-ajax', $action, home_url( '/', 'relative' ) );
}

/**
 * Send headers for Ajax Requests.
 *
 * @since   2.0.0
 * @access  public
 */
function apalodi_ajax_headers() {

    apalodi_maybe_define_constant( 'DOING_AJAX', true );

    send_origin_headers();

    @header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
    @header( 'X-Robots-Tag: noindex' );

    send_nosniff_header();
    nocache_headers();

    apalodi_maybe_define_constant( 'DONOTCACHEPAGE', true );
    apalodi_maybe_define_constant( 'DONOTCACHEOBJECT', true );
    apalodi_maybe_define_constant( 'DONOTCACHEDB', true );

    status_header( 200 );
}

/**
 * Send a JSON response back to an Ajax request.
 *
 * @since   2.0.0
 * @access  public
 * @param   mixed $response Variable (usually an array or object) to encode as JSON.
 * @param   int $status_code (Optional) The HTTP status code to output.
 */
function apalodi_send_json( $response, $status_code = null ) {
    @header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
    if ( null !== $status_code ) {
        status_header( $status_code );
    }
    echo wp_json_encode( $response );
}

/**
 * AJAX Event Handlers.
 *
 * Trigger right after_setup_theme because here only 4 SQL queries are executed and with
 * later actions like template_redirect over 20 SQL queries are executed loading all posts.
 *
 * Note: user isn't loaded yet here, only option
 *
 * @since   2.0.0
 * @access  public
 * @return  array $results
 */
function apalodi_do_ajax() {

    if ( empty( $_GET['apalodi-ajax'] ) ) {
        return;
    }

    apalodi_ajax_headers();
    $action = sanitize_text_field( wp_unslash( $_GET['apalodi-ajax'] ) );
    $action = str_replace( '-', '_', $action );

    // If no action is registered, return a Bad Request response.
    if ( ! has_action( 'apalodi_ajax_' . $action ) ) {
        wp_die( '', 400 );
    }

    do_action( 'apalodi_ajax_' . $action );
    do_action( 'apalodi_ajax_after' );
    wp_die();
}
add_action( 'template_redirect', 'apalodi_do_ajax', 99 );

/**
 * Load more posts ajax callback.
 *
 * @since   2.0.0
 * @access  public
 * @return  array $results
 */
function apalodi_ajax_load_more_posts_callback() {

    $results = array( 'status' => 'error' );
    $content_args = array();
    $load_more_query_args = array();
    // $options = $_POST['options'];
    $load_more_args = $_POST['query'];

    // $content_args['thumbnail'] = apalodi_array_keys_to_data_attr( $options, 'array' );

    // don't allow anybody to pass whatever they want in a ajax query
    $whitelist_args = apply_filters( 'apalodi_whitelist_load_more_args', array( 
        'posts_per_page', 
        'category_name',
        'category__in', 
        'cat', 
        'tag_id',
        'tag',
        's',
        'year',
        'monthnum',
        'day',
        'offset', 
        'apalodi_post_not_in',
    ) );

    foreach ( $load_more_args as $key => $value ) {
        if ( in_array( $key, $whitelist_args ) ) {
            $load_more_query_args[$key] = $value;
        }
    }

    $posts_per_page = isset( $load_more_query_args['posts_per_page'] ) ? $load_more_query_args['posts_per_page'] : 12;

    if ( $posts_per_page < 1 ) {
        $posts_per_page = 1;
    }

    if ( $posts_per_page > 24 ) {
        $posts_per_page = 24;
    }

    $load_more_query_args['posts_per_page'] = $posts_per_page;

    $query_args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'no_found_rows' => true,
        'ignore_sticky_posts' => true,
        'update_post_thumbnail_cache' => true,
    );

    $args = wp_parse_args( $query_args, $load_more_query_args );

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) :

        $results['status'] = 'success';

        $results['post_count'] = $query->post_count;

        ob_start();

        while ( $query->have_posts() ) : $query->the_post();
            apalodi_get_template( 'content', array( 'classes' => 'column flex' ) );
        endwhile;

        $results['html'] = ob_get_clean();

    else :

        $results['status'] = 'empty';

    endif;

    wp_reset_postdata();

    do_action( 'apalodi_ajax_after_load_more_posts' );

    apalodi_send_json( $results, 200 );
}
add_action( 'apalodi_ajax_load_more_posts', 'apalodi_ajax_load_more_posts_callback' );
