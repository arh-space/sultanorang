<?php
/**
 * Core Functions.
 *
 * @package     Kutak/Functions
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Load a template part with passing arguments.
 *
 * Makes it easy for a theme to reuse sections of code in a easy to overload way
 * for child themes.
 *
 * @since   1.0
 * @access  public
 * @param   string $slug The slug name for the generic template.
 * @param   array $args Pass args with the template load.
 */
function apalodi_get_template( $slug, $args = array() ) {

    $templates = array( "templates/{$slug}.php" );
    $located = locate_template( $templates, false, false );

    if ( '' != $located ) {
        include( $located );
    }
}

/**
 * Output the classes for various elements.
 *
 * @since   1.0
 * @access  public
 * @see     apalodi_get_class()
 * @param   string  $filter Name of the custom filter or false to not apply_filters
 * @param   array   $classes One or more classes to add to the class list
 * @return  string  Html class tag with classes
 */
function apalodi_class( $filter, $classes = array() ) {
    echo apalodi_get_class( $filter, $classes );
}

/**
 * Get the classes for various elements.
 *
 * It needs to be called like this: apalodi_class( 'apalodi_page_class' )
 * where apalodi_page_class is an applied filter which is used to insert classes
 *
 * function apalodi_get_page_classes( $classes ) {
 *      $classes[] = 'hfeed';
 *      $classes[] = 'site';
 *      return $classes;
 * }
 * add_filter('apalodi_page_class', 'apalodi_get_page_classes', 10 );
 *
 * By changing apalodi_page_class to something else you create new filter for inserting
 * classes somewhere else. Example apalodi_class( 'apalodi_header_class' )
 *
 *
 * To add classes without filter use it like this
 *      palodi_class( 'apalodi_header_class', array( 'class1', 'class2', 'class3' ) )
 *
 * @since   1.0
 * @access  public
 * @param   string  $filter Name of the custom filter or false to not apply_filters
 * @param   array   $classes One or more classes to add to the class list
 * @return  string  Html class tag with classes
 */
function apalodi_get_class( $filter, $classes = array() ) {

    if ( $filter ) {
        $classes = apply_filters( $filter, $classes );
    }
    
    if ( empty( $classes ) ) {
        return false;
    }

    $classes = array_map( 'esc_attr', $classes );

    // Separates classes with a single space
    return 'class="' . implode( ' ', $classes ) . '"';
}

/**
 * Output the data attributs for various elements.
 *
 * @since   1.0
 * @access  public
 * @see     apalodi_get_data_attr()
 * @param   string  $filter Name of the custom filter or false to not apply_filters
 * @param   array   $data One or more data attr to add to the list
 * @return  string  Html class tag with data
 */
function apalodi_data_attr( $filter, $data = array() ) {
    echo apalodi_get_data_attr( $filter, $data );
}

/**
 * Get the data attributs for various elements.
 *
 * To add classes without filter use it like this
*      apalodi_get_data_attr( 'apalodi_get_load_more_data_attr', array( 'posts_per_page' => '12', 'type' => 'example' ) )
 *
 * @since   1.0
 * @access  public
 * @param   string|bool $filter Name of the custom filter or false to not apply_filters
 * @param   string|array $data One or more data attr to add to the list
 * @return  string Html class tag with data
 */
function apalodi_get_data_attr( $filter, $data = array() ) {

    if ( $filter ) {
        $data = apply_filters( $filter, $data );
    }

    $data_attr = apalodi_array_keys_to_data_attr( $data );

    if ( empty( $data_attr ) ) { 
        return;
    } else {
        return ' ' . implode( ' ', $data_attr );
    }
}

/**
 * Get the tag attributs for various elements.
 *
 * To add classes without filter use it like this
 *      - apalodi_tag_attr( 'apalodi_link_tag_attr', true, array( 'class' => array( 'apalodi-icon', 'apalodi-link-icon' ), 'href' => 'http://example.com', 'target => '_blank') )
 *
 * @since   1.0
 * @access  public
 * @param   string|bool $filter Name of the custom filter or false to not apply_filters
 * @param   bool $echo If false it will return the value
 * @param   string|array $data One or more tag attr to add to the list
 * @return  string Html class tag with tag
 */
function apalodi_tag_attr( $filter, $echo = true, $data = array() ) {
    
    $tags = $attr = array();

    if ( $filter ) {
        $data = apply_filters( $filter, $data );
    }

    foreach ( $data as $tag => $value ) {
        if ( is_array( $value ) ) {
            $attr[$tag] = implode( ' ', $value );
        } else {
            $attr[$tag] = $value;
        }
    }

    foreach ( $attr as $tag => $value ) {
        $tags[] = esc_attr( $tag ) . '="' . esc_attr( $value ) . '"';
    }

    if ( $echo == true ) {
        echo implode( ' ', $tags );
    } else {
        return implode( ' ', $tags );
    }

}

/**
 * Get the inline styles for various elements.
 *
 * To add inline styles without filter use it like this
 *      1.string    apalodi_inline_styles( 'apalodi_overlay_inline_styles', true, 'background="#000" color="#fff" opacity="0.8"' )
 *      2.string    apalodi_inline_styles( 'apalodi_overlay_inline_styles', true, 'background="#000", color="#fff", opacity="0.8"', ',' )
 *      3.array     apalodi_inline_styles( 'apalodi_overlay_inline_styles', true, array( 'background' => '#000', 'color' => '#fff', 'opacity => '0.8') )
 *
 * @since   1.0
 * @access  public
 * @param   string|bool $filter Name of the custom filter or false to not apply_filters
 * @param   bool $echo If false it will return the value
 * @param   string|array $data One or more data to add to the list
 * @param   string $delimiter If we enter data as string set what delimiter is being used between data 
 * @return  string Html style tag with data
 */
function apalodi_inline_styles( $filter, $echo = true, $data = array(), $delimiter = ' ' ) {
    if ( is_string( $data ) ) {
        $data = explode( $delimiter, $data );
    }

    $styles = array();

    if ( $filter ) {
        $data = apply_filters( $filter, $data );
    }

    foreach ( $data as $key => $value ) {
        $styles[] = esc_attr( $key ) . ':' . esc_attr( $value );
    }

    if ( empty( $styles ) ) return;

    if ( $echo ) {
        echo 'style="' . implode( ';', $styles ) . '"';
    } else {
        return 'style="' . implode( ';', $styles ) . '"';
    }

}

/**
 * Output the translated text.
 *
 * @since   1.0
 * @access  public
 * @param   string $option Option id for text
 * @param   string $text Text in gettext function
 * @param   bool $echo Echo the text or return it
 * @param   bool $escape If set escape attr, html or wp_kses allowed html
 * @param   string $allowed_html If the escape type is wp_kses choose what html is allowed
 * @return  string $text Text
 */
function apalodi_translate( $option, $text, $echo = true, $escape = 'html', $allowed_html = array() ) {
    if ( $echo ) {
        echo apalodi_get_translate( $option, $text, $escape, $allowed_html );    
    } else {
        return apalodi_get_translate( $option, $text, $escape, $allowed_html );
    }
}

/**
 * Get the translated text.
 *
 * If the translation is disabled use the text from gettext function.
 *
 * @since   1.0
 * @access  public
 * @param   string $option Option id for text
 * @param   string $text Text in gettext function
 * @param   bool $echo Echo the text or return it
 * @param   bool $escape If set escape attr, html or wp_kses allowed html
 * @param   string $allowed_html If the escape type is wp_kses choose what html is allowed
 * @return  string $text Text
 */
function apalodi_get_translate( $option, $text, $escape = 'html', $allowed_html = array() ) {

    $i18n = apalodi_get_theme_mod( 'i18n', false );
    $text_escaped = '';

    if ( ! $i18n ) {
        $text = apalodi_get_theme_mod( $option, $text );
    }

    if ( $escape ) {
        switch ( $escape ) {
            case 'attr':
                $text_escaped = esc_attr( $text );
                break;
            case 'wp_kses':
                if ( empty( $allowed_html ) ) {
                    $allowed_html = wp_kses_allowed_html();
                }
                $text_escaped = wp_kses( $text, $allowed_html );
                break;
            default:
                $text_escaped = esc_html( $text );
                break;
        }
    }

    return $text_escaped;
}

/**
 * Retrieve theme modification value for the current theme.
 *
 * @since   1.0
 * @access  public
 * @param   string $name Theme modification name.
 * @param   bool|string $default
 * @return  string $mod
 */
function apalodi_get_theme_mod( $name, $default = false ) {
    $mods = get_theme_mods();
 
    if ( isset( $mods[$name] ) ) {
        return apply_filters( "theme_mod_{$name}", $mods[$name] );
    }

    return apply_filters( "theme_mod_{$name}", $default );
}

/**
 * Get the ID. When on blog page return the ID of the page selected as the blog page.
 *
 * @since   1.0
 * @access  public
 * @return  int $id Page ID
 */
function apalodi_get_the_ID() {

    $id = get_the_ID();

    if ( is_home() ) {
        $id = get_option( 'page_for_posts' );
    }

    if ( is_archive() || is_search() || is_404() ) {
        $id = false;
    }

    return apply_filters( 'apalodi_the_ID', $id );
}

/**
 * Get the Theme Informations.
 *
 * @since   1.0
 * @access  public
 * @param   string $info What information do we want
 * @param   bool $child If false on child theme it returns the parent theme info
 * @return  string $theme_info Desired theme information
 */
function apalodi_get_theme_info( $info, $child = false ) {
    
    $theme = wp_get_theme( get_template() );

    if ( true == $child ) {
        $theme = wp_get_theme();
    }

    switch ( $info ) {
        case 'name':
            $theme_info = $theme->get( 'Name' );
            break;
        case 'version':
            $theme_info = $theme->get( 'Version' );
            break;
        case 'uri':
            $theme_info = $theme->get( 'ThemeURI' );
            break;
        case 'author':
            $theme_info = $theme->get( 'Author' );
            break;
        case 'author_uri':
            $theme_info = $theme->get( 'AuthorURI' );
            break;
        case 'description':
            $theme_info = $theme->get( 'Description' );
            break;
        case 'template':
            $theme_info = $theme->get( 'Template' );
            break;
        case 'status':
            $theme_info = $theme->get( 'Status' );
            break;
        case 'tags':
            $theme_info = $theme->get( 'Tags' );
            break;
        case 'text_domain':
            $theme_info = $theme->get( 'TextDomain' );
            break;
        case 'domain_path':
            $theme_info = $theme->get( 'DomainPath' );
            break;
        default:
            $theme_info = '';
            break;
    }
        
    return $theme_info;
}

/**
 * Return a list of allowed tags and attributes for a given context.
 *
 * @since   1.0
 * @access  public
 * @param   string $context The context for which to retrieve tags.
 * @return  array $allowed_html List of allowed tags and their allowed attributes.
 */
function apalodi_wp_kses_allowed_html( $context = 'basic' ) {

    switch ( $context ) {
        case 'metaboxes':
            $allowed_html = array(
                'a' => array(
                    'href' => array(),
                    'target' => array(),
                    'title' => array()
                ),
                'span' => array(
                    'style' => array()
                ),
                'em' => array(),
                'del' => array(),
            );
            break;
        case 'basic':
        default:
            $allowed_html = array(
                'a' => array(
                    'href' => array(),
                    'target' => array(),
                    'title' => array()
                ),
                'span' => array(
                    'style' => array()
                ),
                'em' => array(),
                'del' => array(),
                'p' => array(
                    'style' => array()
                )
            );
            break;
    }

    return apply_filters( 'apalodi_wp_kses_allowed_html', $allowed_html, $context );
}

/**
 * Get all tags ordered.
 *
 * @since   1.0
 * @access  public
 * @param   bool $l10n Wheter to order tags by translated language
 * @return  array $tags
 */
function apalodi_get_all_tags_ordered( $l10n = true ) {

    $tags = get_tags();
    $locale = get_locale();

    if ( $l10n && strpos( $locale, 'en' ) === false ) {

        $tags_names = array();
        $tags_keys = array();

        foreach ( $tags as $key => $tag ) {
            $tags_names[] = $tag->name;
            $tkey = sanitize_key( $tag->name );
            $tags_keys[$tkey] = $tag;
        }

        $tags = array();
        $collator = new Collator( $locale );
        $collator->sort( $tags_names );

        foreach ( $tags_names as $key => $name ) {
            $tkey = sanitize_key( $name );
            $tags[] = $tags_keys[$tkey];
        }
    }

    return $tags;
}

/**
 * Get tagmap.
 *
 * @since   1.0
 * @access  public
 * @return  array $tagmap
 */
function apalodi_get_tagmap() {
    
    if ( false === ( $tagmap = apalodi_get_transient( 'tagmap' ) ) ) {
        $tags = apalodi_get_all_tags_ordered();
        $tagmap = array();

        foreach ( $tags as $key => $tag ) {
            $fist_letter = strtolower( mb_substr( $tag->name, 0, 1 ) );
            $tagmap[$fist_letter][] = $tag;
        }

        apalodi_set_transient( 'tagmap', $tagmap, 6 * HOUR_IN_SECONDS );
    }

    return $tagmap;
}

/**
 * Get reading time.
 *
 * @since   1.0
 * @access  public
 * @return  int $minutes
 */
function apalodi_get_reading_time() {

    $post = get_post();
    $words = str_word_count( strip_tags( $post->post_content ) );
    $minutes = max( 1, floor( $words / 190 ) ); // 190 words per minute

    return $minutes;
}

/**
 * Get published time.
 *
 * The difference is returned in a human readable format such as "1 hour", "5 mins", "2 days".
 *
 * @since   1.0.0 
 * @access  public
 * @param   int $number_of_days When to stop showing human readable time and show the date.
 * @param   (int|WP_Post) Post ID or WP_Post object. If null defaults to current post.
 * @return  string $published Human readable time difference or date.
 */
function apalodi_get_published_time( $number_of_days = 30, $post = null ) {

    $post = get_post( $post );
 
    if ( ! $post ) {
        return false;
    }

    $now = time();
    $date = get_the_time( 'U', $post );
    $diff = (int) abs( $now - $date );
    $limit = $number_of_days * DAY_IN_SECONDS;

    if ( $diff < HOUR_IN_SECONDS ) {

        $mins = round( $diff / MINUTE_IN_SECONDS );
        $mins = $mins <= 1 ? 1 : $mins;

        $published = sprintf( _n( '%s min ago', '%s mins ago', $mins, 'kutak' ), $mins );

    } elseif ( $diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS ) {

        $hours = round( $diff / HOUR_IN_SECONDS );
        $hours = $hours <= 1 ? 1 : $hours;

        $published = sprintf( _n( '%s hour ago', '%s hours ago', $hours, 'kutak' ), $hours );

    } elseif ( $diff < $limit && $diff >= DAY_IN_SECONDS ) {

        $days = round( $diff / DAY_IN_SECONDS );
        $days = $days <= 1 ? 1 : $days;

        $published = sprintf( _n( '%s day ago', '%s days ago', $days, 'kutak' ), $days );

    } else {

        $published = get_the_date( '', $post );
    }

    return apply_filters( 'apalodi_published_time', $published, $post, $number_of_days, $diff );
}

/**
 * Get related posts ids.
 *
 * 1. Get tags number from 70% of posts_per_page
 * 2. The rest 30% get first from child category and then parent category
 * 3. If there are less items get the latest posts
 *
 * @since   1.0
 * @access  public
 * @param   int     $posts_per_page
 * @return  array   $related_posts_ids
 */
function apalodi_get_related_posts_ids( $posts_per_page = 6 ) {

    $post_id = get_the_ID();
    $tags_number = round( $posts_per_page * 0.7, 0 );

    if ( false === ( $related_posts_ids = apalodi_get_post_meta_transient( $post_id, "related_posts_ids_{$posts_per_page}" ) ) ) {

        // Tags
        $tag_ids = wp_get_object_terms( $post_id, 'post_tag', array( 
            'fields' => 'ids',
            'update_term_meta_cache' => false,
        ) );

        $tags_args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $tags_number + 1,
            'fields' => 'ids',
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
            'suppress_filters' => false,
            'tax_query' => array(
                array(
                    'taxonomy' => 'post_tag',
                    'field' => 'term_id',
                    'terms' => $tag_ids
                ),
            ),
        );

        $tags = get_posts( $tags_args );
        $tags = array_slice( array_diff( $tags, array( $post_id ) ), 0, $tags_number );
        $count_tags = count( $tags );

        // Child categories
        $child_category_ids = wp_get_object_terms( $post_id, 'category', array( 
            'fields' => 'ids',
            'update_term_meta_cache' => false,
            'childless' => true,
        ) );

        $child_categories_args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $posts_per_page + 1,
            'fields' => 'ids',
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
            'suppress_filters' => false,
            'tax_query' => array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'term_id',
                    'terms' => $child_category_ids
                ),
            ),
        );

        $child_categories = get_posts( $child_categories_args );
        $child_categories = array_diff( $child_categories, array( $post_id ) );
        $count_child_categories = count( $child_categories );

        // Parent categories
        $parent_category_ids = wp_get_object_terms( $post_id, 'category', array( 
            'fields' => 'ids',
            'update_term_meta_cache' => false,
            'parent' => 0
        ) );

        $parent_categories_args = array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => $posts_per_page + 1,
            'fields' => 'ids',
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
            'suppress_filters' => false,
            'tax_query' => array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'term_id',
                    'terms' => $parent_category_ids
                ),
            ),
        );

        $parent_categories = get_posts( $parent_categories_args );
        $parent_categories = array_diff( $parent_categories, array( $post_id ) );
        $count_parent_categories = count( $parent_categories );

        // Combine categories and tags
        $categories = array_values( array_unique( array_merge( $child_categories, $parent_categories ) ) );
        $categories_tags = array_intersect( $categories, $tags );
        $count_categories_tags = count( $categories_tags );

        $categories_slice = $posts_per_page - $count_tags + $count_categories_tags;
        $categories =  array_slice( $categories, 0, $categories_slice );

        $related_posts_ids = array_slice( array_values( array_unique( array_merge( $tags, $categories ) ) ), 0, $posts_per_page );
        $count_related = count( $related_posts_ids );

        // If there are less posts get the latests
        if ( $count_related < $posts_per_page ) {

            $latest_posts_args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => $posts_per_page * 2,
                'fields' => 'ids',
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
                'suppress_filters' => false,
            );

            $latest_posts = get_posts( $latest_posts_args );
            $latest_posts = array_diff( $latest_posts, array( $post_id ) );
            $related_posts_ids = array_slice( array_values( array_unique( array_merge( $related_posts_ids, $latest_posts ) ) ), 0, $posts_per_page );
        }

        apalodi_set_post_meta_transient( $post_id, "related_posts_ids_{$posts_per_page}", $related_posts_ids, 60 * MINUTE_IN_SECONDS );
    }

    return $related_posts_ids;
}
