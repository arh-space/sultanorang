<?php
/**
 * Open Graph Tags
 *
 * Simplified version of Jetpack Open Graph functions.
 *
 * @package     AP_Popular_Posts/Classes
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * AP_Share_Buttons_Open_Graph class.
 */
class AP_Share_Buttons_Open_Graph {

    /**
     * Hook in methods.
     *
     * @since   1.0.0
     * @access  public
     */
    public static function init() {
        add_action( 'wp_head', array( __CLASS__, 'og_tags' ) );
    }

    /**
     * Output Open Graph Meta Tags.
     *
     * @since   1.0.0
     * @access  public
     */
    public static function og_tags() {

        if ( ! get_option( 'ap_share_buttons_open_graph', true ) ) {
            return;
        }

        $og_output = "\n<!-- AP Share Buttons Open Graph Tags -->\n";

        $tags = array();
        $description_length = 197;

        $tags['og:site_name'] = get_bloginfo( 'name' );

        if ( is_home() || is_front_page() ) {

            $tags['og:type'] = 'website';
            $tags['og:title'] = get_bloginfo( 'name' );
            $tags['og:description'] = get_bloginfo( 'description' );

            $front_page_id = get_option( 'page_for_posts' );

            if ( 'page' == get_option( 'show_on_front' ) && $front_page_id && is_home() ) {
                $tags['og:url'] = get_permalink( $front_page_id );
            } else {
                $tags['og:url'] = home_url( '/' );
            }

        } else if ( is_author() ) {

            $author = get_queried_object();

            $tags['og:type'] = 'profile';
            $tags['og:title'] = $author->display_name;

            if ( ! empty( $author->user_url ) ) {
                $tags['og:url'] = $author->user_url;
            } else {
                $tags['og:url'] = get_author_posts_url( $author->ID );
            }

            $tags['og:description'] = wp_kses( trim( convert_chars( wptexturize( $author->description ) ) ), array() );
            $tags['profile:first_name'] = get_the_author_meta( 'first_name', $author->ID );
            $tags['profile:last_name'] = get_the_author_meta( 'last_name', $author->ID );

        } else if ( is_archive() ) {

            $archive = get_queried_object();

            $tags['og:type'] = 'website';
            $tags['og:title'] = get_the_archive_title();
            $tags['og:description'] = wp_kses( trim( convert_chars( wptexturize( get_the_archive_description() ) ) ), array() );

            if ( $archive ) {
                $tags['og:url'] = get_term_link( $archive->term_id );
            } else {
                global $wp;
                $tags['og:url'] =  home_url( $wp->request );
            }

        } else if ( is_singular() ) {

            global $post;
            $data = $post; // so that we don't accidentally explode the global

            $tags['og:type'] = 'article';

            if ( empty( $data->post_title ) ) {
                $tags['og:title'] = ' ';
            } else {
                $tags['og:title'] = wp_kses( apply_filters( 'the_title', $data->post_title, $data->ID ), array() );
            }

            $tags['og:url'] = get_permalink( $data->ID );

            if ( ! post_password_required() ) {
                if ( ! empty( $data->post_excerpt ) ) {
                    $tags['og:description'] = preg_replace( '@https?://[\S]+@', '', strip_shortcodes( wp_kses( $data->post_excerpt, array() ) ) );
                } else {
                    $exploded_content_on_more_tag = explode( '<!--more-->', $data->post_content );
                    $tags['og:description'] = wp_trim_words( preg_replace( '@https?://[\S]+@', '', strip_shortcodes( wp_kses( $exploded_content_on_more_tag[0], array() ) ) ) );
                }
            }

            if ( empty( $tags['og:description'] ) ) {
                $tags['og:description'] = __( 'Visit the post for more.', 'ap-share-buttons' );
            } else {
                $tags['og:description'] = wp_kses( trim( convert_chars( wptexturize( $tags['og:description'] ) ) ), array() );
            }

            $tags['article:published_time'] = date( 'c', strtotime( $data->post_date_gmt ) );
            $tags['article:modified_time'] = date( 'c', strtotime( $data->post_modified_gmt ) );

        }

        // Do not return any Open Graph Meta tags if we don't have any info about a post.
        if ( empty( $tags ) ) {
            return;
        }

        // Facebook whines if you give it an empty title
        if ( empty( $tags['og:title'] ) ) {
            $tags['og:title'] = __( '(no title)', 'ap-share-buttons' );
        }

        // Shorten the description if it's too long
        if ( isset( $tags['og:description'] ) ) {
            $tags['og:description'] = strlen( $tags['og:description'] ) > $description_length ? mb_substr( $tags['og:description'], 0, $description_length ) . '…' : $tags['og:description'];
        }

        if ( ! post_password_required() ) {

            $image = self::get_image();

            if ( $image ) {
                $tags['og:image'] = $image[0];

                if ( is_ssl() ) {
                    $tags['og:image:secure_url'] = $image[0];
                }

                if ( ! empty( $image[1] ) ) {
                    $tags['og:image:width'] = (int) $image[1];
                }

                if ( ! empty( $image[2] ) ) {
                    $tags['og:image:height'] = (int) $image[2];
                }
            }
        }

        $tags['og:locale'] = get_locale();

        $tags = apply_filters( 'ap_share_buttons_open_graph_tags', $tags );
    
        foreach ( (array) $tags as $tag_property => $tag_content ) {

            if ( empty( $tag_content ) ) {
                continue; // Don't ever output empty tags          
            }

            $og_output .= sprintf( '<meta property="%s" content="%s" />', esc_attr( $tag_property ), esc_attr( $tag_content ) );
            $og_output .= "\n";
        }

        $og_output .= "<!-- End AP Share Buttons Open Graph Tags -->\n\n";

        echo $og_output;
    }


    /**
     * Returns an image used in social shares.
     *
     * @since   1.0.0
     * @access  public
     * @return  array|false The source ('src'), 'width', and 'height' of the image.
     */
    public static function get_image() {

        $image = false;

        if ( is_singular() ) {

            $post_thumbnail_id = get_post_thumbnail_id();
            $image = wp_get_attachment_image_src( $post_thumbnail_id, apply_filters( 'ap_share_buttons_open_graph_image_size', 'full' ) );

        }

        return $image;
    }

}

AP_Share_Buttons_Open_Graph::init();
