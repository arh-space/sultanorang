<?php
/**
 * Theme Page Functions.
 *
 * @package     Kutak/Functions
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Hook into pre_get_posts to customize the query.
 *
 * @since   1.0
 * @access  public
 * @param   object $query query object
 */
function apalodi_pre_get_posts( $query ) {

    if ( is_admin() ) {
        return;
    }

    $posts_per_page = $query->get( 'posts_per_page' );

    if ( empty( $posts_per_page ) ) {
        $posts_per_page = get_option( 'posts_per_page' );
    }

    if ( $query->is_main_query() && empty( $query->get( 'post_type' ) ) ) {

        if ( $query->is_home() ) {

            $paged = apalodi_get_paged();
            $offset = absint( ( $paged - 1 ) * $posts_per_page + 1 );

            // first is the featured posts
            $query->set( 'offset', $offset );

            $query->set( 'ignore_sticky_posts', true );
        }

        if ( $query->is_search() ) {
            $query->set( 'post_type', 'post' );
        }
    }
}
add_filter( 'pre_get_posts', 'apalodi_pre_get_posts' );

/**
 * Change the pagination to infinite loader.
 *
 * @since   1.0
 * @access  public
 * @param   string $template
 */
function apalodi_pagination( $template, $class ) {

    if ( 'posts-navigation' == $class ) {

        $query = apalodi_get_current_query();

        $settings = array(
            'type' => 'block',
            'style' => 'grid',
            'sidebar' => '',
            'pagination' => apalodi_get_theme_mod( 'blog_pagination', 'infinite-scroll' ),
        );

        $load_more_args = apalodi_get_load_more_args( $settings, $query );

        $args = apply_filters( 'apalodi_pagination_args', array(
            'type' => $settings['pagination'],
            'max_num_pages' => $query->max_num_pages,
            'is_paged' => $query->is_paged,
            'load_more_args' => $load_more_args,
        ), $settings, $query );

        apalodi_get_template( 'pagination', $args );
        return;
    }

    return $template;
}
add_filter( 'navigation_markup_template', 'apalodi_pagination', 10, 2 );

/**
 * Add featured section recommended posts content.
 *
 * @since   1.0
 * @access  public
 * @param   string $type
 */
function apalodi_featured_section_recommended( $type ) {

    if ( 'recommended' == $type ) {
        $args = array(
            'post_type' => 'post',
            'status' => 'publish',
            'posts_per_page' => '4',
            'post__in' => apalodi_get_sticky_posts(),
            'update_post_thumbnail_cache' => true,
            'no_found_rows' => true,
            'ignore_sticky_posts' => true
        );

        $recommended = new WP_Query( $args );

        while ( $recommended->have_posts() ) : 
            $recommended->the_post();
            apalodi_get_template( 'content-small' );
        endwhile;

        wp_reset_postdata(); 
    }
}
add_action( 'apalodi_featured_section', 'apalodi_featured_section_recommended' );

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @since   1.0
 * @access  public
 * @global  WP_Query $wp_query WordPress Query object.
 * @return  void
 */
function apalodi_setup_author() {
    global $wp_query;
    if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
        $GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
    }
}
add_action( 'wp', 'apalodi_setup_author' );

/**
 * Add class to primary menu container.
 *
 * @since   1.0
 * @access  public
 * @param   array $args Configuration arguments.
 * @return  array
 */
function apalodi_wp_nav_menu_args( $args ) {

    $args = wp_parse_args( $args, array( 'cache_results' => false ) );

    if ( 'primary' === $args['theme_location'] && is_apalodi_header_type( 'modern' ) ) {

        $theme_nav_locations = get_nav_menu_locations();
        
        if ( isset( $theme_nav_locations[ 'primary' ] ) ) {

            $menu_id = $theme_nav_locations[ 'primary' ];

            if ( ! is_customize_preview() && ( $cached_menu = get_option( "apalodi_kutak_nav_menu_cached_{$menu_id}_primary" ) ) ) {
                return $args;
            }

            $items = wp_get_nav_menu_items( $menu_id );
            $parents = array();

            foreach ( $items as $item ) {
                // if the item's parent is 0, it's a top level, add it to an array
                if ( $item->menu_item_parent == 0 ) {
                    $parents[] = $item;
                }
            }

            $count = count( $parents );

            if ( $count ) {

                $classes = array( 'menu-number-is-' . $count );

                if ( $count % 2 == 0 ) {
                    $classes[] = 'menu-number-is-even';
                } else {
                    $classes[] = 'menu-number-is-odd';
                }

                if ( $count > 4 && $count !== 8 ) {
                    $classes[] = 'menu-number-is-bigger-than-5';
                }

                if ( $classes ) {
                    $args['container_class'] = $args['container_class'] . ' ' . implode( ' ', $classes );
                }
            }

        }

    }

    return $args;
}
add_filter( 'wp_nav_menu_args', 'apalodi_wp_nav_menu_args' );

/**
 * Setup menu custom fields.
 *
 * @since   1.0
 * @access  public
 * @param   array $menu_item
 * @return  array $menu_item
 */
function apalodi_setup_nav_menu_item( $menu_item ) {
    $menu_item->bg_image = get_post_meta( $menu_item->ID, '_apalodi_menu_item_bg_image', true );
    return $menu_item;
}
add_filter( 'wp_setup_nav_menu_item', 'apalodi_setup_nav_menu_item' );

/**
 * Add custom background image for main menu.
 *
 * @since   1.0
 * @access  public
 * @param   array $atts
 * @param   object $item
 * @param   object $args
 * @return  array $atts
 */
function apalodi_nav_menu_link_attributes( $atts, $item, $args ) {

    if ( $item->bg_image && 'primary' == $args->theme_location && is_apalodi_header_type( 'modern' ) ) {
        $image = apalodi_get_attachment_image_src( $item->bg_image, array( 'width' => 500, 'height' => 350, 'crop' => true ) );
        $atts['style'] = 'background-image:url('. esc_url( $image['src'] ) .')';
    }

    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'apalodi_nav_menu_link_attributes', 10, 3 );

/**
 * Adds custom classes to the array of nav menu classes.
 *
 * @since   1.0
 * @access  public
 * @param   array $classes
 * @param   array $item
 * @param   array $args
 * @param   int $depth
 * @return  array $classes
 */
function apalodi_nav_menu_css_classes( $classes, $item, $args, $depth ) {

    if ( $item->bg_image && 'primary' == $args->theme_location && is_apalodi_header_type( 'modern' ) ) {
        $classes[] = 'menu-item-has-bg';
    }

    return $classes;
}
add_filter( 'nav_menu_css_class', 'apalodi_nav_menu_css_classes', 10, 4 );

/**
 * Change the excerpt more.
 *
 * @since   1.0
 * @access  public
 * @param   string $more
 * @return  string $more
 */
function apalodi_excerpt_more( $more ) {
    
    if ( is_single() ) {
        return '';
    }

    return '...';
}
add_filter( 'excerpt_more', 'apalodi_excerpt_more' );

/**
 * Change the excerpt length only for posts.
 *
 * @since   1.0
 * @access  public
 * @param   int $length
 * @return  int $length
 */
function apalodi_excerpt_length( $length ) {

    if ( 'post' == get_post_type() ) {
        $length = 20;
    }

    return $length;
}
add_filter( 'excerpt_length', 'apalodi_excerpt_length' );

/**
 * Wrap embeds into a proportion containing div.
 *
 * @since   1.0
 * @access  public
 * @param   string $html Iframe html.
 * @param   string $url Embeded link.
 * @param   string $attr Attributes.
 * @param   string $post_ID The id.
 * @return  string $html Iframe html wrapped with div.
 */
function apalodi_oembed_wrap( $html, $url, $attr, $post_ID  ) {

    $html = preg_replace( '/(width|height|frameborder|allow)="\d*"\s/', '', $html );
    $html = preg_replace( '/( webkitallowfullscreen| mozallowfullscreen)/', '', $html );

    if ( false !== strpos( $html, '<iframe' ) || false !== strpos( $html, '<embed' ) ) {
        
        $class = '';

        if ( false !== strpos( $html, 'youtube') || false !== strpos( $html, 'vimeo') || false !== strpos( $html, 'videopress') ) {
            $class = ' iframe-video';
        }

        if ( false !== strpos( $html, 'soundcloud') ) {
            $class = ' iframe-soundcloud';
        }

        if ( is_apalodi_lazy_load_media() ) {
            $class .= ' lazy-load-iframe';
            $iframe_no_script = '<noscript>' . $html . '</noscript>';
            preg_match( '/src="(.*?)"/is', $html, $src );
            $html = '<i class="iframe-placeholder" data-src="'. esc_url( $src[1] ) .'"></i>';
            $html .= $iframe_no_script;
        }

        $html = '<div class="iframe-wrapper' . $class . '">' . $html . '</div>';
    }
    return $html;
}
add_filter( 'embed_oembed_html', 'apalodi_oembed_wrap', 10, 4 );

/**
 * Change video shortcode wrapper.
 *
 * @since   1.0
 * @access  public
 * @param   string $output  Video shortcode HTML output.
 * @param   array  $atts    Array of video shortcode attributes.
 * @param   string $video   Video file.
 * @param   int    $post_id Post ID.
 * @param   string $library Media library used for the video shortcode.
 * @return  string $html Iframe html wrapped with div.
 */
function apalodi_video_shortcode_wrap( $output, $atts, $video, $post_id, $library ) {
    preg_match( '/class="wp-video">(.*?)<\/div>/s', $output, $video );
    return '<div class="wp-video">' . $video[1] . '</div>';
}
add_filter( 'wp_video_shortcode', 'apalodi_video_shortcode_wrap', 10, 5 );
/**
 * Customize WordPress default gallery shortcode.
 *
 * @since   1.0
 * @access  public
 * @param   string $output
 * @param   array $attr - Attributes of the gallery shortcode
 * @return  string $output
 */
function apalodi_gallery_shortcode( $output, $attr ) {

    // Let Jetpack handle galleries
    if ( class_exists( 'Jetpack_Tiled_Gallery' ) ) {
        return $output;
    }

    $post = get_post();

    static $instance = 0;
    $instance++;

    if ( ! empty( $attr['ids'] ) ) {
         // 'ids' is explicitly ordered, unless you specify otherwise.
        if ( empty( $attr['orderby'] ) ) {
            $attr['orderby'] = 'post__in';
        }
        $attr['include'] = $attr['ids'];
    }

    $atts = shortcode_atts( array(
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        'id'         => $post ? $post->ID : 0,
        'itemtag'    => 'figure',
        'icontag'    => 'div',
        'captiontag' => 'figcaption',
        'columns'    => 3,
        'size'       => 'thumbnail',
        'include'    => '',
        'exclude'    => '',
        'link'       => '',
        'mobilecolumns' => '2',
        'ratio'      => 'landscape',
    ), $attr, 'gallery' );

    $id = intval( $atts['id'] );

    if ( ! empty( $atts['include'] ) ) {
        $_attachments = get_posts( array( 
            'include'        => $atts['include'],
            'post_status'    => 'inherit',
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'order'          => $atts['order'],
            'orderby'        => $atts['orderby'] 
        ) );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif ( ! empty( $atts['exclude'] ) ) {
        $attachments = get_children( array( 
            'post_parent'    => $id,
            'exclude'        => $atts['exclude'],
            'post_status'    => 'inherit',
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'order'          => $atts['order'],
            'orderby'        => $atts['orderby']
        ) );
    } else {
        $attachments = get_children( array( 
            'post_parent'    => $id,
            'post_status'    => 'inherit',
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'order'          => $atts['order'],
            'orderby'        => $atts['orderby']
        ) );
    }

    if ( empty( $attachments ) ) {
        return '';
    }

    if ( is_feed() ) {
        $output = "\n";
        foreach ( $attachments as $att_id => $attachment ) {
            $output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
        }
        return $output;
    }

    $columns = intval( $atts['columns'] );
    $columns = 3 < $columns ? 3 : $columns;

    $output = "\n" . '<ul class="wp-block-gallery alignwide columns-' . $columns . ' gallery-ratio gallery-ratio-'. esc_attr( $atts['ratio'] ) .'">';

    foreach ( $attachments as $id => $attachment ) {

        $image_caption = isset( $attachment->post_excerpt ) ? wptexturize( $attachment->post_excerpt ) : false;

        $size = apalodi_get_image_size( 'gallery-' . $atts['ratio'] );
        $ratio = round( $size['height'] / $size['width'] * 100, 2 );
        $image_url = apalodi_get_attachment_image_src( $id, $size );

        $new_size = apalodi_get_resized_image_size( $id, $size );
        $new_ratio = round( $new_size['height'] / $new_size['width'] * 100, 2 );

        $sizes = sprintf( '%sx%s', absint( $new_size['width'] ), absint( $new_size['height'] ) );

        $class = 'preload-image';
        $aspect_filler = '';

        if ( is_apalodi_lazy_load_media() ) {
            $class .= ' lazy-load-img';
        }

        if ( '0' == $size['height'] ) {
            $aspect_filler = '<div class="aspect-ratio-filler" style="padding-bottom:'. $new_ratio .'%"></div>';
        } else if ( $ratio != $new_ratio ) {
            $class .= ' is-object-fit';
            if ( $ratio >= $new_ratio ) {
                $class .= ' object-fit-wider';
            } else {
                $class .= ' object-fit-taller';
            }
        }

        $attr = array(
            'class' => $class,
            'data-id' => $id, 
            'data-full' => $image_url['src'],
            'data-full-size' => $sizes,
        );
        
        $image = apalodi_get_attachment_image( $id, $size, $attr );

        $output .= '<li class="blocks-gallery-item">';
            $output .= '<figure>';

                if ( 'none' != $atts['link'] ) {

                    $output .= '<a href="'. esc_url( $image_url['src'] ) .'">';
                    $output .= '<div class="image-wrapper">';
                    $output .=  $aspect_filler . $image;
                    $output .= '</div>';
                    $output .= '</a>';

                } else {
                    $output .= $image;
                }

                if ( $image_caption ) {
                    $output .= '<figcaption>' . wp_kses_post( $image_caption ) . '</figcaption>';
                }

            $output .= '</figure>';
        $output .= '</li>';
    }

    $output .= "\n</ul>\n";

    return $output;
}
add_filter( 'post_gallery', 'apalodi_gallery_shortcode', 10, 2 );

/**
 * Add data attribute sizes width and height to content images.
 *
 * @since   1.0
 * @access  public
 * @param   string $content
 * @return  string $content
 */
function apalodi_add_data_attr_sizes_to_content_images( $content ) {

    if ( ! preg_match_all( '/<img [^>]+>/', $content, $matches ) ) {
        return $content;
    }

    $selected_images = $attachment_ids = array();

    foreach( $matches[0] as $image ) {

        if ( 
            preg_match( '/wp-image-([0-9]+)/i', $image, $class_id ) 
            && ( $attachment_id = absint( $class_id[1] ) ) 
        ) {

            /*
             * If exactly the same image tag is used more than once, overwrite it.
             * All identical tags will be replaced later with 'str_replace()'.
             */
            $selected_images[ $image ] = $attachment_id;
            // Overwrite the ID when the same image is included more than once.
            $attachment_ids[ $attachment_id ] = true;
        }
    }

    if ( count( $attachment_ids ) > 1 ) {
        /*
         * Warm the object cache with post and meta information for all found
         * images to avoid making individual database calls.
         */
        _prime_post_caches( array_keys( $attachment_ids ), false, true );
    }

    foreach ( $selected_images as $image => $attachment_id ) {
        $img = wp_get_attachment_image_src( $attachment_id, 'full' );
        if ( $img ) {
            $full = sprintf( ' data-full="%s"', esc_url( $img[0] ) );
            $sizes = sprintf( ' data-full-size="%sx%s"', intval( $img[1] ), intval( $img[2] ) );
            $_image = preg_replace( '/<img ([^>]+?)[\/ ]*>/', '<img $1' . $full . $sizes . ' />', $image );
            $content = str_replace( $image, $_image, $content );
        }
    }

    return $content;
}
add_filter( 'the_content' , 'apalodi_add_data_attr_sizes_to_content_images' );

/**
 * Customize WordPress default image caption shortcode.
 *
 * @since   1.0
 * @access  public
 * @param   string $output
 * @param   array $attr - Attributes of the image caption shortcode
 * @param   string $content - The image element, possibly wrapped in a hyperlink.
 * @return  string $output
 */
function apalodi_img_caption_shortcode( $output, $attr, $content ) {

    $atts = shortcode_atts( array(
        'id'      => '',
        'align'   => 'alignnone',
        'width'   => '',
        'caption' => '',
        'class'   => '',
    ), $attr, 'caption' );

    $class = trim( $atts['align'] . ' ' . $atts['class'] );

    $output = '<div class="wp-block-image">';
    $output .= '<figure class="' . esc_attr( $class ) . '">';
    $output .= do_shortcode( $content );

    if ( ! empty( $atts['caption'] ) ) {
        $output .= '<figcaption>' . wp_kses_post( $atts['caption'] ) . '</figcaption>';
    }

    $output .= '</figure>';
    $output .= '</div>';

    return $output;
}
add_filter( 'img_caption_shortcode', 'apalodi_img_caption_shortcode', 10, 3 );

/**
 * Remove style tag from tag cloud widget.
 *
 * @since   1.0
 * @access  public
 * @param   string $string  Tag cloud item
 * @return  string $string Tag cloud item
 */
function apalodi_remove_style_tag_cloud( $string ){
   return preg_replace( '/style=("|\')(.*?)("|\')/', '', $string );
}
add_filter( 'wp_generate_tag_cloud', 'apalodi_remove_style_tag_cloud' );

/**
 * Modifies tag cloud widget arguments to display all tags in the same font size
 * and use list format for better accessibility.
 *
 * @since   1.0
 * @access  public
 * @param   array $args Arguments for tag cloud widget.
 * @return  array The filtered arguments for tag cloud widget.
 */
function apalodi_widget_tag_cloud_args( $args ) {
    $args['largest']  = 1;
    $args['smallest'] = 1;
    $args['unit'] = 'rem';

    return $args;
}
add_filter( 'widget_tag_cloud_args', 'apalodi_widget_tag_cloud_args' );

/**
 * Remove recent comments inline style.
 *
 * @since   1.0
 * @access  public
  */
add_filter( 'show_recent_comments_widget_style', '__return_false' );
