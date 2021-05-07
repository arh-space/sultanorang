<?php
/**
 * Theme functions, definitions and includes.
 *
 * @package     Kutak/Theme
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$theme_dir = get_template_directory() . '/theme/';
require_once( $theme_dir . 'includes/core-functions.php' );
require_once( $theme_dir . 'includes/option-functions.php' );
require_once( $theme_dir . 'includes/conditional-functions.php' );
require_once( $theme_dir . 'includes/get-option-functions.php' );
require_once( $theme_dir . 'includes/helper-functions.php' );
require_once( $theme_dir . 'includes/media-functions.php' );
require_once( $theme_dir . 'includes/media-lazy-load-functions.php' );
require_once( $theme_dir . 'includes/page-functions.php' );
require_once( $theme_dir . 'includes/cache-functions.php' );
require_once( $theme_dir . 'includes/ajax-functions.php' );
require_once( $theme_dir . 'includes/sanitization-functions.php' );
require_once( $theme_dir . 'includes/template-tags.php' );
require_once( $theme_dir . 'includes/template-functions.php' );
require_once( $theme_dir . 'includes/template-hooks.php' );
require_once( $theme_dir . 'includes/deprecated-functions.php' );

require_once( $theme_dir . 'includes/class-walker-comment.php' );
require_once( $theme_dir . 'includes/class-background-process.php' );
require_once( $theme_dir . 'includes/class-regenerate-images-request.php' );
require_once( $theme_dir . 'includes/class-images.php' );

if ( class_exists( 'AP_Popular_Posts', false ) ) {
    require_once( $theme_dir . 'plugins-supported/ap-popular-posts/config.php' );
}

if ( class_exists( 'AP_Share_Buttons', false ) ) {
    require_once( $theme_dir . 'plugins-supported/ap-share-buttons/config.php' );
}

if ( class_exists( 'WPCF7', false ) ) {
    require_once( $theme_dir . 'plugins-supported/contact-form-7/config.php' );
}

if ( function_exists( 'kutak_popular_posts_get_ids' ) ) {
    require_once( $theme_dir . 'plugins-supported/kutak-popular-posts/config.php' );
}

if ( function_exists( 'kutak_share_link' ) ) {
    require_once( $theme_dir . 'plugins-supported/kutak-share-buttons/config.php' );
}

/**
 * If there is a theme in the repo with the same name prevent WP from prompting an update.
 *
 * @since   1.0
 * @access  public
 * @param   array $args Existing request arguments
 * @param   string $url Request URL
 * @return  array Amended request arguments
 */
function apalodi_dont_update_theme( $args, $url ) {

    if ( 0 !== strpos( $url, 'https://api.wordpress.org/themes/update-check/1.1/' ) ) {
        return $args; // Not a theme update request. Bail immediately.
    }

    $themes = json_decode( $args['body']['themes'] );
    $child = get_option( 'stylesheet' );
    unset( $themes->themes->$child );
    $args['body']['themes'] = json_encode( $themes );

    return $args;
}
add_filter( 'http_request_args', 'apalodi_dont_update_theme', 5, 2 );


/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @since   1.0
 * @access  public
 */
function apalodi_content_width( ) {
    $GLOBALS['content_width'] = apply_filters( 'apalodi_content_width', 790 );
}
add_action( 'after_setup_theme', 'apalodi_content_width', 0 );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since   1.0
 * @access  public
 */
function apalodi_theme_setup() {
        
    /**
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     */
    load_theme_textdomain( 'kutak', get_template_directory() . '/languages' );

    // Let WordPress manage the document title.
    add_theme_support( 'title-tag' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    // Enable support for Post Thumbnails.
    add_theme_support( 'post-thumbnails' );

    // Add theme support for selective refresh for widgets.
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Add excerpts to pages.
    add_post_type_support( 'page', 'excerpt' );

    // Register menus and set default locations.
    register_nav_menus( array(
        'primary' => esc_html__( 'Primary Menu', 'kutak' ),
        'secondary' => esc_html__( 'Secondary Menu', 'kutak' ),
        'footer' => esc_html__( 'Footer Menu', 'kutak' ),
    ) );

    // Output valid HTML5 for search form, comment form, comments, gallery and captions.
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
    ) );

    // Add theme support for Custom Logo.
    add_theme_support( 'custom-logo', array(
        'width' => 300,
        'height' => 100,
        'flex-width' => true,
        'flex-height' => false,
    ) );

    // Add custom image sizes.
    apalodi_add_image_size( 'kutak-blog', 760, 428, true, array( 1520, 1248, 990, 480, 360 ) );

    // Add support for Block Styles.
    // add_theme_support( 'wp-block-styles' );

    // Add support for wide alignment.
    add_theme_support( 'align-wide' );

    // Add support for responsive embedded content.
    add_theme_support( 'responsive-embeds' );

    // Add support for editor styles.
    add_theme_support( 'editor-styles' );

    // Enqueue editor styles.
    add_editor_style( 'style-editor.css' );

    // Add support for custom color scheme.
    add_theme_support( 'editor-color-palette', array(
        array(
            'name'  => esc_html__( 'Accent Color', 'kutak' ),
            'slug'  => 'accent',
            'color' => get_theme_mod( 'accent_color', '#d93f7e' )
        ),
    ) );

    // Disable the custom color picker by having only our color palette.
    // add_theme_support( 'disable-custom-colors' );

    // Add support for custom font sizes.
    add_theme_support( 'editor-font-sizes', array(
        array(
            'name' => esc_html__( 'small', 'kutak' ),
            'shortName' => esc_html__( 'S', 'kutak' ),
            'size' => 17,
            'slug' => 'small'
        ),
        array(
            'name' => esc_html__( 'regular', 'kutak' ),
            'shortName' => esc_html__( 'M', 'kutak' ),
            'size' => 20,
            'slug' => 'regular'
        ),
        array(
            'name' => esc_html__( 'large', 'kutak' ),
            'shortName' => esc_html__( 'L', 'kutak' ),
            'size' => 24,
            'slug' => 'large'
        ),
        array(
            'name' => esc_html__( 'larger', 'kutak' ),
            'shortName' => esc_html__( 'XL', 'kutak' ),
            'size' => 26,
            'slug' => 'larger'
        )
    ) );
}
add_action( 'after_setup_theme', 'apalodi_theme_setup' );

/**
 * Register widget area.
 *
 * @since   2.0
 * @access  public
 */
function apalodi_widgets_init() {

    register_sidebar(
        array(
            'id' => 'sidebar-posts',
            'name'=> esc_html__( 'Posts Widget Area', 'kutak' ),
            'description' => esc_html__( 'Add widgets here to appear below blog posts.', 'kutak' ),
            'before_widget' => '<aside id="%1$s" class="widget %2$s"><div class="widget-inner">',
            'after_widget' => '</div></aside>',
            'before_title' => '<h3 class="widget-title meta-title"><span>',
            'after_title' => '</span></h3>',
        )
    );

}
add_action( 'widgets_init', 'apalodi_widgets_init' );

/**
 * Register and Enqueue CSS and JS.
 *
 * @since   1.0
 * @access  public
 */
function apalodi_enqueue_scripts() {

    $version = apalodi_get_theme_info( 'version' );
    $gfonts_url = esc_url_raw( apalodi_get_gfonts_url() );

    // Deregister CSS
    wp_deregister_style( 'wp-block-library' );

    // Register CSS 
    wp_register_style( 'kutak-gfonts', $gfonts_url, array(), $version );
    wp_register_style( 'photoswipe', get_theme_file_uri( '/assets/css/photoswipe.css' ), array(), $version );
    wp_register_style( 'photoswipe-default-skin', get_theme_file_uri( '/assets/css/photoswipe-default-skin.css' ), array( 'photoswipe' ), $version );
    wp_register_style( 'kutak-style', get_stylesheet_uri(), array(), apalodi_get_theme_info( 'version', true ) );
    wp_register_style( 'kutak-parent', get_parent_theme_file_uri( 'style.css' ), array(), $version );

    // Register JS
    wp_register_script( 'photoswipe', get_theme_file_uri( '/assets/js/vendor/photoswipe.min.js' ), array(), '4.1.2', true );
    wp_register_script( 'photoswipe-ui-default', get_theme_file_uri( '/assets/js/vendor/photoswipe-ui-default.min.js' ), array( 'photoswipe' ), '4.1.2', true );
    wp_register_script( 'kutak-main', get_theme_file_uri( '/assets/js/main.js' ), array( 'jquery' ), $version, true );

    // Enqueue CSS
    wp_enqueue_style( 'kutak-gfonts' );

    // If using a child theme, auto-load the parent theme style.
    if ( is_child_theme() ) {
        wp_enqueue_style( 'kutak-parent' );
        wp_enqueue_style( 'kutak-style' );
    } else {
        wp_enqueue_style( 'kutak-style' );
    }

    // Enqueue JS
    wp_enqueue_script( 'kutak-main' );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    // Localize scripts
    wp_localize_script(
        'kutak-main',
        'kutak_vars',
        array(
            'rest_url' => esc_url_raw( rest_url() ),
            'ajax_url' => esc_url_raw( apalodi_get_ajax_url() ),
            'is_lazy_load' => is_apalodi_lazy_load_media(),
        )
    );
}
add_action( 'wp_enqueue_scripts', 'apalodi_enqueue_scripts' );

/**
 * Print some styles in footer that are render blocking and not neccessary in head.
 *
 * @since   2.0
 * @access  public
 */
function apalodi_print_late_styles() {

    if ( ! is_search() && ! is_apalodi_posts_page() && ! is_page_template( 'page-templates/widgets.php' ) ) {

        // wp_enqueue_style( 'photoswipe' );
        wp_enqueue_style( 'photoswipe-default-skin' );

        // wp_enqueue_script( 'photoswipe' );
        wp_enqueue_script( 'photoswipe-ui-default' );
    }
}
add_action( 'wp_footer', 'apalodi_print_late_styles' );

/**
 * Enqueue styles for editor.
 *
 * @since   2.0
 * @access  public
 */
function apalodi_block_editor_styles() {

    $version = apalodi_get_theme_info( 'version' );
    $gfonts_url = esc_url_raw( apalodi_get_gfonts_url() );

    wp_enqueue_style( 'kutak-editor-fonts', $gfonts_url, array(), $version );
    wp_add_inline_style( 'kutak-editor-fonts', apalodi_custom_colors_css() );
}
add_action( 'enqueue_block_editor_assets', 'apalodi_block_editor_styles' );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images.
 *
 * @since   2.0
 * @access  public
 * @param   string       $sizes         A source size value for use in a 'sizes' attribute.
 * @param   array|string $size          Requested size. Image size or array of width and height values
 *                                      in pixels (in that order).
 * @param   string|null  $image_src     The URL to the image file or null.
 * @param   array|null   $image_meta    The image meta data as returned by wp_get_attachment_metadata() or null.
 * @param   int          $attachment_id Image attachment ID of the original image or 0.
 * @return  string A source size value for use in a content image 'sizes' attribute.
 */
function apalodi_content_image_sizes_attr( $sizes, $size, $image_src, $image_meta, $attachment_id ) {
    global $content_width;

    $width = 0;
 
    if ( is_array( $size ) ) {
        $width = absint( $size[0] );
    } elseif ( is_string( $size ) ) {
        if ( ! $image_meta && $attachment_id ) {
            $image_meta = wp_get_attachment_metadata( $attachment_id );
        }
 
        if ( is_array( $image_meta ) ) {
            $size_array = _wp_get_image_size_from_meta( $size, $image_meta );
            if ( $size_array ) {
                $width = absint( $size_array[0] );
            }
        }
    }

    if ( $width < $content_width ) {
        $sizes = sprintf( '(max-width: %1$dpx) 100vw, %1$dpx', $width );
    } else {
        $sizes = sprintf( '(min-width: %dpx) %1$dpx, 100vw,', $content_width );
    }

    return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'apalodi_content_image_sizes_attr', 10, 5 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality.
 *
 * @since   1.0
 * @access  public
 * @param   array $attr       Attributes for the image markup.
 * @param   int   $attachment Image attachment ID.
 * @param   array $size       Registered image size or flat array of height and width dimensions.
 * @return  array $attr       The filtered attributes for the image markup.
 */
function apalodi_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {

    if ( is_string( $size ) && 'kutak-blog' === $size  ) {

        if ( isset( $attr['data-type'] ) ) {

            if ( 'block' === $attr['data-type'] ) {
                $attr['sizes'] = '(min-width: 1220px) 360px, (min-width: 870px) calc(33vw - 25px), (min-width: 700px) calc(50vw - 30px), calc(100vw - 40px)';
            }

            if ( 'featured' === $attr['data-type'] ) {
                $attr['sizes'] = '(min-width: 1200px) 760px, (min-width: 870px) calc(66vw - 60px), calc(100vw - 40px)';
            }

            if ( 'single' === $attr['data-type'] ) {
                $attr['sizes'] = '(min-width: 1120px) 530px, (min-width: 780px) calc(50vw - 30px), 100vw';
            }

            if ( 'small' === $attr['data-type'] ) {
                $attr['sizes'] = '(min-width: 1200px) 144px, (min-width: 1124px) 12vw, (min-width: 870px) 10vw, (min-width: 600px) 15vw, 28vw';
            }
        }
    }

    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'apalodi_post_thumbnail_sizes_attr', 10, 3 );

/**
 * Add preconnect for Google Fonts.
 *
 * @since   1.0
 * @access  public
 * @param   array $urls URLs to print for resource hints.
 * @param   string $relation_type The relation type the URLs are printed.
 * @return  array $urls URLs to print for resource hints.
 */
function apalodi_resource_hints( $urls, $relation_type ) {
    if ( wp_style_is( 'kutak-gfonts', 'queue' ) && 'preconnect' === $relation_type ) {
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }

    return $urls;
}
add_filter( 'wp_resource_hints', 'apalodi_resource_hints', 10, 2 );

/**
 * Add script to head that changes html class no-js to js.
 *
 * @since   1.0
 * @access  public
 */
function apalodi_output_check_js_script() {
    echo "<script>document.documentElement.className = document.documentElement.className.replace(/\bno-js\b/,'js');</script>\n";
}
add_action( 'wp_head', 'apalodi_output_check_js_script', 0 );

/**
 * Add meta tag to head for theme color.
 *
 * @since   2.0
 * @access  public
 */
function apalodi_output_meta_theme_color() {
    $color = get_theme_mod( 'theme_color', '#ffffff' );
    echo "<meta name='theme-color' content='". $color ."'>\n";
}
add_action( 'wp_head', 'apalodi_output_meta_theme_color' );

/**
 * Add custom color CSS in head.
 *
 * @since   2.0
 * @access  public
 */
function apalodi_add_custom_colors_css_to_head() {

    $color = get_theme_mod( 'accent_color', '#d93f7e' );

    // Only include custom colors in customizer or frontend.
    if ( ( ! is_customize_preview() && '#d93f7e' === $color ) || is_admin() ) {
        return;
    }

    ?>
<style type="text/css" id="kutak-custom-colors"<?php echo is_customize_preview() ? ' data-color="' . $color . '"' : ''; ?>>
    <?php echo apalodi_custom_colors_css(); ?> 
</style> 
<?php
}
add_action( 'wp_head', 'apalodi_add_custom_colors_css_to_head' );

/**
 * Generate the CSS for the current custom color scheme.
 *
 * @since   2.0
 * @access  public
 */
function apalodi_custom_colors_css() {
    
    $color = get_theme_mod( 'accent_color', '#d93f7e' );

    $css = 
    'blockquote,
    .section-title, 
    .footer-social .social-icon, 
    .copyright a, 
    .button:hover,
    .wp-block-button a:hover,
    .wp-block-file__button:hover,
    .wp-block-button.is-style-outline a,
    [type="button"]:hover, 
    [type="reset"]:hover, 
    [type="submit"]:hover, 
    input[type=checkbox]:checked::before, 
    .featured-tab.is-active, 
    .social-icon, 
    .popup-cookies a:hover, 
    .tagmap-item span, 
    .error404-sign, 
    .site-heading p a, 
    .term-count, 
    .term-count span, 
    .site-content p > a,
    .site-content li > a,
    .site-content li p > a,
    .site-content p em > a,
    .site-content li em > a,
    .site-content p strong > a,
    .site-content li strong > a,
    .site-content table a:not(.button),
    .single-meta-title, 
    .tags-title, 
    .single-tags a, 
    .social-icon,
    .has-accent-color {
        color: ' . $color . ';
    }

    .paging-navigation .page-numbers .page-numbers.current, 
    .button,
    .wp-block-button a,
    .wp-block-file__button,
    .wp-block-button.is-style-outline a:hover,
    [type="button"], 
    [type="reset"], 
    [type="submit"], 
    input[type=radio]:checked::before, 
    .featured-tab::after,
    .has-accent-background-color {
        background-color: ' . $color . ';
    }

    .site-navigation::-webkit-scrollbar-thumb:vertical {
        background-color: ' . $color . ';
    }

    .paging-navigation .page-numbers .page-numbers.current, 
    .bypostauthor > .comment-body::before, 
    #cancel-comment-reply-link, 
    .button,
    .wp-block-button a,
    .wp-block-file__button,
    [type="button"], 
    [type="reset"], 
    [type="submit"], 
    .button:focus,
    .wp-block-button a:focus,
    .wp-block-file__button:focus,
    [type="button"]:focus, 
    [type="reset"]:focus, 
    [type="submit"]:focus,
    .tagmap-title,
    .message-notice,
    .message-info,
    .message-error,
    .widget-area .button {
        border-color: ' . $color . ';
    }

    .site-heading p a,
    .site-content p > a:hover,
    .site-content li > a:hover,
    .site-content li p > a:hover,
    .site-content p em > a:hover,
    .site-content li em > a:hover,
    .site-content p strong > a:hover,
    .site-content li strong > a:hover,
    .site-content table a:not(.button):hover {
        background-image: linear-gradient(to bottom,' . $color . ' 0%,' . $color . ' 100%);
    }
    ';

    $editor_css =
    '
    .editor-styles-wrapper .editor-block-list__layout .wp-block p > a,
    .editor-styles-wrapper .editor-block-list__layout .wp-block li > a,
    .editor-styles-wrapper .editor-block-list__layout .wp-block li p > a,
    .editor-styles-wrapper .editor-block-list__layout .wp-block p em > a,
    .editor-styles-wrapper .editor-block-list__layout .wp-block li em > a,
    .editor-styles-wrapper .editor-block-list__layout .wp-block p strong > a,
    .editor-styles-wrapper .editor-block-list__layout .wp-block li strong > a,
    .editor-styles-wrapper .editor-block-list__layout .wp-block table a,
    .editor-styles-wrapper .editor-block-list__layout .wp-block-button__link:hover,
    .editor-styles-wrapper .editor-block-list__layout .wp-block-file__button:hover,
    .editor-styles-wrapper .editor-block-list__layout .has-accent-color {
        color: ' . $color . ';
    }

    .editor-styles-wrapper .editor-block-list__layout .wp-block p > a:hover,
    .editor-styles-wrapper .editor-block-list__layout .wp-block li > a:hover,
    .editor-styles-wrapper .editor-block-list__layout .wp-block li p > a:hover,
    .editor-styles-wrapper .editor-block-list__layout .wp-block p em > a:hover,
    .editor-styles-wrapper .editor-block-list__layout .wp-block li em > a:hover,
    .editor-styles-wrapper .editor-block-list__layout .wp-block p strong > a:hover,
    .editor-styles-wrapper .editor-block-list__layout .wp-block li strong > a:hover,
    .editor-styles-wrapper .editor-block-list__layout .wp-block table a:hover {
        background-image: linear-gradient(to bottom,' . $color . ' 0%,' . $color . ' 100%);
    }

    .editor-styles-wrapper .editor-block-list__layout .wp-block-button__link,
    .editor-styles-wrapper .editor-block-list__layout .wp-block-file__button,
    .editor-styles-wrapper .editor-block-list__layout .wp-block-button.is-style-outline .wp-block-button__link:hover,
    .editor-styles-wrapper .editor-block-list__layout .has-accent-background-color { 
        background-color: ' . $color . ';
    }

    .editor-styles-wrapper .editor-block-list__layout .wp-block-button__link,
    .editor-styles-wrapper .editor-block-list__layout .wp-block-file__button,
    .editor-styles-wrapper .editor-block-list__layout .wp-block-button__link:focus,
    .editor-styles-wrapper .editor-block-list__layout .wp-block-file__button:focus { 
        border-color: ' . $color . ';
    }
    ';

    if ( function_exists( 'register_block_type' ) && is_admin() ) {
        $css = $editor_css;
    }
    
    return $css;
}
