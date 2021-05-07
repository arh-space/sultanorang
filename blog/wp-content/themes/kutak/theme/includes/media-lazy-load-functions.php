<?php
/**
 * Lazy load media functions based on rocket lazy load.
 *
 * @see http://wordpress.org/plugins/rocket-lazy-load/
 *
 * @package     Kutak/Functions/Template_Tags
 * @since       2.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Replace images by Lazy Load images.
 *
 * @since   2.0
 * @access  public
 * @param   string $html HTML code to parse.
 * @return  string Updated HTML code
 */
function apalodi_lazy_load_images( $html ) {

    if ( ! is_apalodi_lazy_load_media() ) {
        return $html;
    }

    $html = preg_replace_callback( 
        '#<(div|a|span)([^>]*) style=("(?:[^"]+)"|\'(?:[^\']+)\'|(?:[^ >]+))([^>]*)>#', 
        'apalodi_lazy_load_bg_images_callback', 
        $html 
    );

    $html = preg_replace_callback( 
        '#<img([^>]*) src=("(?:[^"]+)"|\'(?:[^\']+)\'|(?:[^ >]+))([^>]*)>#', 
        'apalodi_lazy_load_images_callback', 
        $html 
    );

    return $html;
}
add_filter( 'get_avatar', 'apalodi_lazy_load_images', 98 );
add_filter( 'the_content', 'apalodi_lazy_load_images', 99 );

/**
 * Determine if the current image should be excluded from lazy load.
 *
 * @since   2.0
 * @access  public
 * @param   string  $tag HTML tag
 * @param   src     $src Image src
 * @return  bool
 */
function is_apalodi_lazy_load_media_excluded_values( $tag, $src ) {
    
    $excluded_attributes = apply_filters( 'apalodi_lazy_load_excluded_attributes', array(
        'data-no-lazy=',
        'data-lazy-original=',
        'data-lazy-src=',
        'data-src=',
        'data-lazysrc=',
        'data-lazyload=',
        'data-bgposition=',
        'data-envira-src=',
        'fullurl=',
        'lazy-slider-img=',
        'data-srcset=',
        'class="ls-l',
        'class="ls-bg',
    ) );

    $excluded_src = apply_filters( 'apalodi_lazy_load_excluded_src', array(
        '/wpcf7_captcha/',
    ) );

    if ( 
        is_apalodi_excluded_values( $tag, $excluded_attributes ) 
        || is_apalodi_excluded_values( $src, $excluded_src ) 
    ) {
        return true;
    }

    return false;
}

/**
 * Replace all tags with background image with lazy load tags.
 *
 * @since   2.0
 * @access  public
 * @param   string $matches a string matching the pattern to find images in HTML code.
 * @return  string Updated string with lazyload data
 */
function apalodi_lazy_load_bg_images_callback( $matches ) {

    if ( strpos( $matches[3], 'url(' ) === false ) {
        return $matches[0];
    }

    preg_match( '/style\\s*=\\s*(?:".*?(url\\(.*?\\)).*?"|\'.*?(url\\(.*?\\)).*?\')/si', $matches[0], $match);
    preg_match('/background(-image)??\s*?:.*?url\(["|\']??(.+)["|\']??\)/', $matches[3], $bg );
    
    $url = str_replace( array( '\'', '"' ), '', $bg[2] );

    if ( is_apalodi_lazy_load_media_excluded_values( $matches[0], $url ) ) {
        return $matches[0];
    }

    $styles = substr( $matches[3], 1, -1 );
    $styles = explode( ';', $styles );
    
    $remove_style = '';
    $new_style = '';

    foreach ( $styles as $key => $style ) {
        if ( strpos( $style, 'url(' ) !== false ) {
            $remove_style = $style;
            unset( $styles[ $key ] );
        }
    }

    if ( $styles ) {
        $styles = implode( ';', $styles );
        $new_style = sprintf( ' style="%s"', $styles );
    }

    $img_lazy = sprintf( '<span class="bg-image preload-bg-image lazy-load-bg-img" data-src="%s"></span>', $url );
    $img_noscript = sprintf( '<noscript><span class="bg-image" style="background-image:url(%s)" data-no-lazy="true"></span></noscript>', $url );
    $tag = sprintf( '<%1$s%2$s%3$s>', $matches[1], $matches[2], $new_style );

    return $tag. $img_lazy . $img_noscript;
}

/**
 * Replace all img tags with lazyload tags.
 *
 * @since   2.0
 * @access  public
 * @param   string $matches a string matching the pattern to find images in HTML code.
 * @return  string Updated string with lazyload data
 */
function apalodi_lazy_load_images_callback( $matches ) {
    global $content_width;

    if ( is_apalodi_lazy_load_media_excluded_values( $matches[0], $matches[2] ) ) {
        return $matches[0];
    }

    $placeholder = apalodi_get_base64_placeholder_image();
    $img_noscript = sprintf( '<noscript><img%1$s data-no-lazy="true" src=%2$s%3$s></noscript>', $matches[1], $matches[2], $matches[3] );

    $img = sprintf( '<img%1$s src="%4$s" data-src=%2$s%3$s>', $matches[1], $matches[2], $matches[3], $placeholder );
    $img = str_replace( 'srcset=', 'data-srcset=', $img );
    $img = str_replace( 'sizes=', 'data-sizes=', $img );

    // Add .lazy-load class to each image that already has a class.
    if ( preg_match( '/class=("(?:[^"]+)"|\'(?:[^\']+)\'|(?:[^ >]+))/i', $img, $img_class ) ) {
        $img_class = str_replace( array( '\'', '"' ), '', $img_class[1] );
        $img = preg_replace( '/class=("(?:[^"]+)"|\'(?:[^\']+)\'|(?:[^ >]+))/i', 'class="'. $img_class .' preload-image lazy-load-img"', $img );
    }

    // Add .lazy-load class to each image that doesn't already have a class.
    $img = preg_replace( '/<img((?:(?!class=).)*?)>/i', '<img class="preload-image lazy-load-img"$1>', $img );

    if ( 'get_avatar' == current_filter() ) {
        return $img . $img_noscript;
    }

    $width = false;
    $height = false;
    $only_width = false;

    // first let's try to get width and height img tags
    if ( preg_match( '/width=("(?:[^"]+)"|\'(?:[^\']+)\'|(?:[^ >]+))([^>]*)/', $img, $img_width_tag ) ) {
        $width = str_replace( array( '\'', '"' ), '', $img_width_tag[1] );
    }

    if ( preg_match( '/height=("(?:[^"]+)"|\'(?:[^\']+)\'|(?:[^ >]+))([^>]*)/', $img, $img_height_tag ) ) {
        $height = str_replace( array( '\'', '"' ), '', $img_height_tag[1] );
    }

    // then try to find width and height form image url e.g. -580x300.jpg
    if ( ! $width || ! $height ) {

        $src = str_replace( array( '\'', '"' ), '', $matches[2] );

        $sizes = substr( strrchr( $src , '-' ), 1 );
        $size = explode( 'x', strtok( $sizes, '.' ) );
        $size = array_filter( $size, 'absint' );

        if ( $size && count( $size ) > 1 ) {
            $width = $size[0];
            $height = $size[1];
        }
    }

    if ( $width && ! $height ) {
        $only_width = $width;
    }

    // then try to get the the full image size from data-full-size attribute
    if ( ! $width || ! $height ) {
        if ( preg_match( '/data-full-size="([^"]*)"/', $img, $img_data_full_size ) ) {
            $sizes = $img_data_full_size[1];
            $size = explode( 'x', $sizes );
            $size = array_filter( $size, 'absint' );

            if ( $size && count( $size ) > 1 ) {
                $width = $size[0];
                $height = $size[1];
            }
        }
    }

    // if we can't find width or height let's just return the image without wrapper
    if ( ! $width || ! $height ) {
        return $img . $img_noscript;
    }

    $ratio = round( $height / $width * 100, 2 );
    $class = '';
    $img_classes = array();

    $has_image_wrapper = false;
    $before_image = '';
    $after_image = '';

    if ( preg_match( '/class="(.*?)"/s', $matches[0], $img_classes ) ) {
        $img_classes = explode( ' ', $img_classes[1] );
    }

    if ( in_array( 'alignleft', $img_classes ) ) {
        $has_image_wrapper = true;
        $class = ' wp-caption alignleft';
        $img = str_replace( 'alignleft', '', $img );
        $img_noscript = str_replace( 'alignleft', '', $img_noscript );
    }

    if ( in_array( 'alignright', $img_classes ) ) {
        $has_image_wrapper = true;
        $class = ' wp-caption alignright';
        $img = str_replace( 'alignright', '', $img );
        $img_noscript = str_replace( 'alignright', '', $img_noscript );
    }

    if ( in_array( 'alignnone', $img_classes ) ) {
        $has_image_wrapper = true;
        $class = ' wp-caption alignnone';
        $img = str_replace( 'alignnone', '', $img );
        $img_noscript = str_replace( 'alignnone', '', $img_noscript );
    }

    if ( $has_image_wrapper ) {
        $before_image = '<div class="wp-block-image">';
        $after_image = '</div>';
    }

    $padding = 'padding-bottom:'. $ratio .'%;';

    if ( $only_width ) {
        $width = $only_width;
    }

    if ( $content_width < $width ) {
        $width = $content_width;
    }

    $width = 'width:'. $width .'px';

    return $before_image . '<div class="image-wrapper'. esc_attr( $class ) .'" style="max-'. $width .'"><div class="aspect-ratio-filler" style="'. $padding . $width .'"></div>' . $img . $img_noscript . '</div>' . $after_image;
}

/**
 * Applies lazy load on images displayed using wp_get_attachment_image().
 *
 * @since   2.0
 * @access  public
 * @param   array        $attr       Attributes for the image markup.
 * @param   WP_Post      $attachment Image attachment post.
 * @param   string|array $size       Requested size. Image size or array of width and height values
 *                                   (in that order). Default 'thumbnail'.
 * @return  array        $attr       Attributes for the image markup.
 */
function apalodi_lazy_load_get_attachment_image( $attr, $attachment, $size ) {

    if ( ! is_apalodi_lazy_load_media() || isset( $attr['data-no-lazy'] ) ) {
        return $attr;
    }

    if ( is_string( $size ) ) {

        $sizes = apalodi_get_image_sizes();

        /* 
        * Applies lazy load only on images with our sizes because
        * they allready have wrappers with aspect ratio.
        *
        * Content images are done with other functions.
        */
        if ( isset( $sizes[ $size ] ) ) {
            $attr['data-src'] = $attr['src'];
            $attr['src'] = apalodi_get_base64_placeholder_image();

            if ( isset( $attr['srcset'] ) ) {
                $attr['data-srcset'] = $attr['srcset'];
                unset( $attr['srcset'] );
            }

            if ( isset( $attr['sizes'] ) ) {
                $attr['data-sizes'] = $attr['sizes'];
                unset( $attr['sizes'] );
            }

            $attr['class'] .= ' preload-image lazy-load-img';
        }
    }

    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'apalodi_lazy_load_get_attachment_image', 11, 3 );

/**
 * Add noscript tag with image to the post thumbnail HTML.
 *
 * @since   2.0
 * @access  public
 * @param   string       $html              The post thumbnail HTML.
 * @param   int          $post_id           The post ID.
 * @param   string       $post_thumbnail_id The post thumbnail ID.
 * @param   string|array $size              The post thumbnail size. Image size or array of width and height
 *                                          values (in that order). Default 'post-thumbnail'.
 * @param   string       $attr              Query string of attributes.
 * @return  string       $html              The post thumbnail HTML.
 */
function apalodi_post_thumbnail_html_noscript( $html, $post_id, $post_thumbnail_id, $size, $attr ) {

    if ( ! is_apalodi_lazy_load_media() || isset( $attr['data-no-lazy'] ) ) {
        return $html;
    }

    $attr['data-no-lazy'] = 'true';
    $img_noscript = '<noscript>'. wp_get_attachment_image( $post_thumbnail_id, $size, false, $attr ) .'</noscript>';

    return $html . $img_noscript;
}
add_filter( 'post_thumbnail_html', 'apalodi_post_thumbnail_html_noscript', 10 ,5 );
