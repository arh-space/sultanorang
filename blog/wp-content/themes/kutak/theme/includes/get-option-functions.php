<?php
/**
 * Theme Get Options Functions.
 *
 * @package     Kutak/Functions
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Get paged value for pagination.
 *
 * Looking for get_query_var('paged') and get_query_var('page') in case shortcodes are used
 * on static front page which has problems returning the right paged number.
 *
 * @since   1.0
 * @access  public
 * @return  int $paged Current page number
 */
function apalodi_get_paged() {
    if ( get_query_var( 'paged' ) ) { 
        $paged = get_query_var( 'paged' );
    } else if ( get_query_var( 'page' ) ) {
        $paged = get_query_var( 'page' );
    } else {
        $paged = 1;
    }
    return intval( $paged );
}

/**
 * Get current query object.
 *
 * @since   1.0
 * @access  public 
 * @param   object  $query - WP_Query object
 * @return  object  $query - WP_Query object
 */
function apalodi_get_current_query( $query = false ) {

    if ( ! $query ) {
        global $wp_query;
        $query = $wp_query;
    }

   return $query;
}

/**
 * Get current loop index.
 *
 * @since   2.0
 * @access  public 
 * @param   object  $query - WP_Query object
 * @return  int     $index
 */
function apalodi_get_loop_index( $query = false ) {
   $query = apalodi_get_current_query( $query );
   return $query->current_post + 1;
}

/**
 * Get the number of found posts.
 *
 * @since   1.0
 * @access  public
 * @param   object  $query - WP_Query object
 * @return  int     $found_posts
 */
function apalodi_get_found_posts( $query = false ) {
    $query = apalodi_get_current_query( $query );
    return $query->found_posts;
}

/**
 * Get the number of loaded posts.
 *
 * @since   1.0
 * @access  public
 * @param   object  $query - WP_Query object
 * @return  int     $post_count
 */
function apalodi_get_post_count( $query = false ) {
    $query = apalodi_get_current_query( $query );
    return $query->post_count;
}

/**
 * Get the maximum number of pages.
 *
 * @since   1.0
 * @access  public
 * @param   object  $query - WP_Query object
 * @return  int     $found_posts
 */
function apalodi_get_max_num_pages( $query = false ) {
    $query = apalodi_get_current_query( $query );
    return $query->max_num_pages;
}

/**
 * Get the number of posts per page.
 *
 * @since   1.0
 * @access  public
 * @return  int $posts_per_page
 */
function apalodi_get_posts_per_page() {
    return (int) get_option( 'posts_per_page' );
}

/**
 * Get sticky posts or the number of sticky posts.
 *
 * @since   1.0
 * @access  public
 * @param   bool $count - If false return sticky posts if true return sticky post count
 * @return  array|int $sticky_posts
 */
function apalodi_get_sticky_posts( $count = false ) {
    $sticky_posts = get_option( 'sticky_posts' );
    if ( $count ) {
        return count( $sticky_posts );
    } else {
        return $sticky_posts;
    }
}

/**
 * Get number of posts from author.
 *
 * @since   1.0
 * @access  public
 * @return  int $count_user_posts
 */
function apalodi_count_user_posts() {
    return count_user_posts( get_the_author_meta( 'ID' ) );
}

/**
 * Get number of post tags.
 *
 * @since   2.0
 * @access  public
 * @return  int $count_tags
 */
function apalodi_count_terms_post_tag() {
    return wp_count_terms( 'post_tag' );
}

/**
 * Get number of post categories.
 *
 * @since   2.0
 * @access  public
 * @return  int $count_categories
 */
function apalodi_count_terms_category() {
    return wp_count_terms( 'category' );
}


/*=================================================================
 *
 * GENERAL OPTIONS
 * 
 ================================================================*/
 /**
 * Get Google Fonts url.
 *
 * @since   1.0
 * @access  public
 * @return  string $gfonts_url
 */
function apalodi_get_gfonts_url() {

    $gfonts_url = add_query_arg( array(
        'family' => 'Source+Sans+Pro:400,400i,600,600i,700,700i',
        'subset' => 'latin,latin-ext',
    ), 'https://fonts.googleapis.com/css' );

    return apply_filters( 'apalodi_gfonts_url', $gfonts_url );
}

/**
 * Get the logo url.
 *
 * @since   2.0
 * @access  public
 * @return  string $type - Header Type
 */
function apalodi_get_header_type() {
    return apalodi_get_theme_mod( 'header_type', 'modern' );
}

/**
 * Get the logo url.
 *
 * @since   1.0
 * @access  public
 * @param   string $type - Logo Type
 * @return  string $logo_url - Logo url
 */
function apalodi_get_logo( $type ) {

    $logo = get_theme_mod( $type . '_logo', '' );

    $logo_url = '';

    if ( '' == $logo && 'custom' == $type ) {
        $logo_url = get_template_directory_uri() .'/assets/img/logo.png';
    } else {
        $url = wp_get_attachment_image_src( $logo, 'full' );
        $logo_url = $url[0];
    }

    if ( '' == $logo_url ) {
        return false;
    } else {
        return $logo_url;
    }
}

/**
 * Get page id of tagmap template.
 *
 * @since   1.0
 * @access  public
 * @return  int $page_id
 */
function apalodi_get_tagmap_page_ID() {
    return (int) get_option( 'apalodi_tagmap_page_id', false );
}


/*=================================================================
 *
 * PAGINATION
 * 
 ================================================================*/
 /**
 * Get the load more pagination options.
 *
 * @since   2.0
 * @access  public
 * @param   array   $settings Pagination settings.
 * @return  array   $otions
 */
function apalodi_get_load_more_options( $settings ) {
    global $wp_registered_sidebars;

    $options = array( 
        'type' => $settings['type'],
        'has-sidebar' => 'false',
    );

    if ( isset( $settings['style'] ) ) {
        $options['style'] = $settings['style'];
    }

    if ( isset( $settings['sidebar'] ) ) {
        if ( '' !== $settings['sidebar'] && isset( $wp_registered_sidebars[ $settings['sidebar'] ] ) ) {
            $options['has-sidebar'] = 'true';
        }
    }

    return $options;
}

/**
 * Get the load more pagination args.
 *
 * @since   2.0
 * @access  public
 * @param   array   $settings Pagination settings.
 * @param   object  $query WP_Query
 * @return  array   $args
 */
function apalodi_get_load_more_args( $settings, $query ) {

    $posts_per_page = $query->get( 'posts_per_page' );

    if ( $posts_per_page < 1 ) {
        $posts_per_page = 12;
    }

    if ( $posts_per_page > 24 ) {
        $posts_per_page = 24;
    }

    $found_posts = $query->found_posts;
    $offset = $posts_per_page;

    if ( is_home() ) {
        $found_posts = $found_posts - 1;
        $offset = $offset + 1;
    }

    $query_args = array(
        'posts_per_page' => $posts_per_page * 2,
        'offset' => $offset,
    );

    if ( ! is_home() ) {
        $query_args = wp_parse_args( $query_args, $query->query );
    }

    // $options = apalodi_get_load_more_options( $settings );

    $args = array(
        'found-posts' => $found_posts,
        'post-count' => $query->post_count,
        'query' => esc_attr( wp_json_encode( $query_args ) ),
        // 'options' => esc_attr( wp_json_encode( $options ) ),
    );

    return apply_filters( 'apalodi_load_more_args', $args, $settings, $query );
}

/*=================================================================
 *
 * SOCIAL
 * 
 ================================================================*/
 /**
 * Get the share buttons. (Don't use anymore)
 *
 * @since   1.0
 * @access  public
 * @return  array $share_buttons
 */
function apalodi_get_share_buttons() {

    $share_buttons = get_theme_mod( 'share_icons', 
        array(
            'facebook', 
            'twitter',
            'facebook-messenger',
            'whatsapp',
            'viber',
            'mail',
        ) 
    );

    return apply_filters( 'apalodi_share_buttons', $share_buttons );
}

/**
 * Get the social icons.
 *
 * @since   1.0
 * @access  public
 * @return  array $social_icons
 */
function apalodi_get_social_icons() {
    $social_icons = get_theme_mod( 'social_icons', array() );
    return apply_filters( 'apalodi_social_icons', $social_icons );
}

/*=================================================================
 *
 * MEDIA
 * 
 ================================================================*/
 /**
 * Get the image sizes for posts block.
 *
 * @since   2.0
 * @access  public
 * @return  array $attr Image attributes
 * @return  string $sizes
 */
 function apalodi_get_posts_block_image_sizes( $attr ) {
    
    $sizes = '';    

    if ( 'grid' === $attr['data-style'] ) {

        if ( 'true' === $attr['data-has-sidebar'] ) {
            $sizes = '(min-width: 1121px) 228px, (min-width: 1056px) calc(33vw - 140px), (min-width: 980px) calc(50vw - 198px), (min-width: 760px) calc(50vw - 192px), (min-width: 480px) calc(50vw - 30px), 41vw';
        } else {
            $sizes = '(min-width: 1121px) 246px, (min-width: 1056px) calc(25vw - 30px), (min-width: 980px) calc(33.33vw - 32px), (min-width: 760px) calc(33.33vw - 24px), (min-width: 480px) calc(50vw - 30px), 41vw';
        }

    } else {

        if ( 'true' === $attr['data-has-sidebar'] ) {
            $sizes = '(min-width: 1121px) 220px, (min-width: 760px) 22vw, 41vw';

        } else {
            $sizes = '(min-width: 1121px) 370px, (min-width: 760px) 35vw, 41vw';
        }

    }

    return $sizes;
 }
 

/**
 * Get the image size.
 *
 * @since   1.0
 * @access  public 
 * @param   string $image_size
 * @return  array $size
 */
function apalodi_get_image_size( $image_size ) {

    switch ( $image_size ) {
        case 'blog':
            $size = array(
                'width' => '1200',
                'height' => '700',
                'crop' => '1'
            );
            break;
        case 'single':
            $size = array(
                'width' => '1200',
                'height' => '1200',
                'crop' => '1'
            );
            break;
        case 'gallery-landscape':
            $size = array(
                'width' => '1200',
                'height' => '675',
                'crop' => '1'
            );
            break;
        case 'gallery-square':
            $size = array(
                'width' => '1200',
                'height' => '1200',
                'crop' => '1'
            );
            break;
        case 'gallery-portrait':
            $size = array(
                'width' => '960',
                'height' => '1200',
                'crop' => '1'
            );
            break;
        case 'gallery-default':
            $size = array(
                'width' => '1200',
                'height' => '0',
                'crop' => '0'
            );
            break;
        default:
            $size = array(
                'width' => '500',
                'height' => '500',
                'crop' => '1'
            );
            break;
    }

    return apply_filters( 'apalodi_image_size', $size, $image_size );
}
