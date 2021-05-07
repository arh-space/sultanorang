<?php
/**
 * One Click Demo Importer Settings.
 *
 * @package     Kutak/Admin/Functions
 * @since       2.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Get import files for the One Click Demo Import plugin.
 *
 * @since   2.0
 * @access  public
 * @author  apalodi
 * @return  array $import_files
 */
function apalodi_ocdi_import_files() {

    $import_files = array(
        array(
            'import_file_name' => 'MainDemo',
            'import_file_url' => 'https://demo.apalodi.com/import/kutak/content-46.xml',
            'import_widget_file_url' => 'https://demo.apalodi.com/import/kutak/widgets-46.wie',
            'import_customizer_file_url' => 'https://demo.apalodi.com/import/kutak/customizer-46.dat',
            'import_preview_image_url' => 'https://demo.apalodi.com/import/kutak/screenshot.png',
            'import_notice' => esc_html__( 'If there are some problems with import you should update to the latest theme version or maybe our site is down. Import process can take a couple of minutes. It takes some time to download all images.', 'kutak' ),
            'preview_url' => 'https://demo.apalodi.com/kutak/',
        ),
    );

  return $import_files;
}
add_filter( 'pt-ocdi/import_files', 'apalodi_ocdi_import_files' );

/**
 * Decide if the given meta key maps to information we will want to import
 *
 * @since   2.0
 * @access  public
 * @author  apalodi
 * @param   string $key The meta key to check
 * @return  string|bool The key if we do want to import, false if not
 */
function apalodi_ocdi_is_valid_meta_key( $key ) {

    // skip _kutak_image_sizes since we'll regenerate it from scratch
    if ( in_array( $key, array( '_kutak_image_sizes' ) ) ) {
        return false;
    }

    return $key;
}
add_filter( 'import_post_meta_key', 'apalodi_ocdi_is_valid_meta_key' );

/**
 * Remove small images from regenerating in import process.
 *
 * @since   2.0.0
 * @access  public
 * @param   array $sizes Image sizes.
 * @return  array $sizes Image sizes.
 */
function apalodi_ocdi_disable_regenerate_thumbnails( $sizes ) {

    if ( isset( $_POST['action'] ) && 'ocdi_import_demo_data' === $_POST['action'] ) {
        foreach ( $sizes as $key => $size ) {
            if ( ! in_array( $key, array( 'medium_large', 'large' ), true ) ) {
                unset( $sizes[$key] );
            }
        }
    }

    return $sizes;
}
add_filter( 'intermediate_image_sizes_advanced', 'apalodi_ocdi_disable_regenerate_thumbnails', 99 );

/**
 * Before content import.
 *
 * @since   2.0
 * @access  public
 * @author  apalodi
 * @param   array $selected_import
 */
function apalodi_ocdi_before_content_import( $selected_import ) {
    global $wpdb;

    // let's delete the default "Hello World" post and "Sample Page"
    $hello_world = get_page_by_title( 'Hello World!', 'OBJECT', 'post' );
    $sample_page = get_page_by_title( 'Sample Page' );

    if ( $hello_world ) {
        wp_delete_post( $hello_world->ID );
    }

    if ( $sample_page ) {
        wp_delete_post( $sample_page->ID );
    }
}
add_action( 'pt-ocdi/before_content_import', 'apalodi_ocdi_before_content_import' );

/**
 * We need to register custom sidebars before widgets import.
 *
 * @since   2.0
 * @access  public
 * @author  apalodi
 * @param   array $selected_import
 */
function apalodi_ocdi_before_widgets_import( $selected_import ) {

    // let's first remove all widgets
    update_option( 'sidebars_widgets', array() );

    // replace posts content with new generated
    apalodi_generate_demo_posts_content();
}
add_action( 'pt-ocdi/before_widgets_import', 'apalodi_ocdi_before_widgets_import' );

/**
 * Customizations after the import.
 *
 * @since   2.0
 * @access  public
 * @author  apalodi
 * @param   array $selected_import
 */
function apalodi_ocdi_after_import( $selected_import ) {
    global $wpdb;

    $main_menu = get_term_by( 'name', 'Main', 'nav_menu' );
    $secondary_menu = get_term_by( 'name', 'Secondary', 'nav_menu' );

    $menu_locations = array(
        'primary' => $main_menu->term_id,
        'secondary' => $secondary_menu->term_id,
        'footer' => $secondary_menu->term_id
    );

    set_theme_mod( 'nav_menu_locations', $menu_locations );

    // Assign front page and posts page
    update_option( 'posts_per_page', 6 );

    if ( class_exists( 'AP_Popular_Posts', false ) ) {
        // let's simulate few views
        ap_popular_posts()->views->populate_dummy_views( 20, '-1 day' );
    }

}
add_action( 'pt-ocdi/after_import', 'apalodi_ocdi_after_import' );

/**
 * Generate demo posts content gallery.
 *
 * @since   2.1
 * @access  public
 * @author  apalodi
 * @return  string $content
 */
function apalodi_generate_demo_content_gallery( $data, $class, $settings ) {

    $new_data = array();
    $gallery = '';

    $ocdi = OCDI\OneClickDemoImport::get_instance();
    $content_import_data = $ocdi->importer->get_importer_data();
    $post_ids = $content_import_data['mapping']['post'];

    foreach ( $data as $id => $caption ) {
        $new_id = wp_get_attachment_image_src( $post_ids[$id], 'large' );
        if ( $new_id ) {
            $new_data[$post_ids[$id]] = $caption;
        }
    }

    if ( $new_data ) {
        $gallery .= '<!-- wp:gallery {"ids":['. implode( ',', array_keys( $new_data ) ) .'],'. $settings .'} -->
        <ul class="'. $class .'">';

        foreach ( $new_data as $id => $caption ) {
            $img = wp_get_attachment_image_src( $id, 'large' );
            $gallery .= '<li class="blocks-gallery-item"><figure><a href="'. $img[0] .'"><img src="'. $img[0] .'" alt="" data-id="'. $id .'" data-link="'. get_permalink( $id ) .'" class="wp-image-'. $id .'"/></a>';

                if ( '' !== $caption ) {
                    $gallery .= '<figcaption>'. $caption .'</figcaption>';
                }

            $gallery .= '</figure></li>';
        }
        $gallery .= '</ul>
    <!-- /wp:gallery -->';
    }

    return $gallery;
}

/**
 * Generate demo posts content image.
 *
 * @since   2.1
 * @access  public
 * @author  apalodi
 * @return  string $content
 */
function apalodi_generate_demo_content_image( $data, $class, $settings ) {

    $new_data = array();
    $content = '';

    $ocdi = OCDI\OneClickDemoImport::get_instance();
    $content_import_data = $ocdi->importer->get_importer_data();
    $post_ids = $content_import_data['mapping']['post'];

    $id = $data[0];
    $caption = isset( $data[1] ) ? $data[1] : '';
    $img = wp_get_attachment_image_src( $post_ids[$id], 'large' );

    if ( $img ) {
        $content .= '<!-- wp:image {"id":'. $post_ids[$id] .','. $settings .'} -->
        <figure class="'. $class .'"><a href="'. wp_get_attachment_url( $post_ids[$id] ) .'"><img src="'. $img[0] .'" alt="" class="wp-image-'. $post_ids[$id] .'"/></a>';

        if ( '' !== $caption ) {
            $content .= '<figcaption>'. $caption .'</figcaption>';
        }
        $content .= '</figure>
        <!-- /wp:image -->';
    }

    return $content;
}

/**
 * Generate demo posts content.
 *
 * @since   2.1
 * @access  public
 * @author  apalodi
 * @return  string $content
 */
function apalodi_generate_demo_posts_content() {
    global $wpdb;

    // Get import data, with new IDs.
    $ocdi = OCDI\OneClickDemoImport::get_instance();
    $content_import_data = $ocdi->importer->get_importer_data();
    $post_ids = $content_import_data['mapping']['post'];

    $content = '<!-- wp:paragraph -->
    <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
    <!-- /wp:paragraph -->

    <!-- wp:paragraph -->
    <p>She packed her&nbsp;<strong>seven versalia</strong>, put her initial into the belt and made herself on the way.</p>
    <!-- /wp:paragraph -->

    ';

    $content .= apalodi_generate_demo_content_image( 
        array( 83, 'Caption can be used to add info but also credits with link | credits <a href="https://unsplash.com/" target="_blank" rel="noreferrer noopener" aria-label="Unsplash (opens in a new tab)">Unsplash</a>' ),
        'wp-block-image alignwide',
        '"align":"wide","linkDestination":"media"'
    );

    $content .= '

    <!-- wp:paragraph -->
    <p>There were thousands of bad Commas, wild Question Marks and devious Semikoli, but the Little Blind Text didn’t listen.</p>
    <!-- /wp:paragraph -->

    <!-- wp:paragraph -->
    <p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.</p>
    <!-- /wp:paragraph -->
    ';

    $img_95 = wp_get_attachment_image_src( $post_ids[95], 'large' );
    if ( $img_95 ) {
    $content .= '
    <!-- wp:media-text {"mediaPosition":"right","mediaId":'. $post_ids[95] .',"mediaType":"image","isStackedOnMobile":true} -->
    <div class="wp-block-media-text alignwide has-media-on-the-right is-stacked-on-mobile"><figure class="wp-block-media-text__media"><img src="'. $img_95[0] .'" alt="" class="wp-image-'. $post_ids[95] .'"/></figure><div class="wp-block-media-text__content"><!-- wp:paragraph {"placeholder":"Content…","fontSize":"large"} -->
    <p class="has-large-font-size">When she reached the first hills of the Italic Mountains, she had a last <a href="https://themeforest.net/user/apalodi/portfolio">view back</a> on the skyline of her hometown Bookmarksgrove </p>
    <!-- /wp:paragraph --></div></div>
    <!-- /wp:media-text -->
    ';
    }

    $content .= '
    <!-- wp:heading -->
    <h2>And if she hasn’t been rewritten, then they are still using her</h2>
    <!-- /wp:heading -->

    <!-- wp:list -->
    <ul><li>Far far away</li><li>behind the word mountains</li><li>far from the countries Vokalia and Consonantia</li><li>there live the blind texts</li></ul>
    <!-- /wp:list -->

    <!-- wp:paragraph -->
    <p>Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden&nbsp;<a href="https://themeforest.net/user/apalodi/portfolio">flows by their place and supplies it with the necessary regelialia</a>. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.</p>
    <!-- /wp:paragraph -->

    <!-- wp:list {"ordered":true} -->
    <ol><li><strong>Even the all-powerful</strong><br>Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</li><li><strong>The Big Oxmox</strong><br>Advised her not to do so, because there were thousands of bad Commas, wild Question Marks and devious Semikoli, but the Little Blind Text didn’t listen.</li><li><strong>She packed her seven versalia&nbsp;</strong><br>Put her initial into the belt and made herself on the way. When she reached the first hills of the Italic Mountains, she had a last view back on the skyline of her hometown Bookmarksgrove, the headline of Alphabet Village and the subline.</li></ol>
    <!-- /wp:list -->

    <!-- wp:paragraph -->
    <p>Pityful a rethoric question ran over her cheek, then she continued her way. On her way she met a copy.</p>
    <!-- /wp:paragraph -->

    <!-- wp:heading {"level":3} -->
    <h3>Support for the wide align option</h3>
    <!-- /wp:heading -->

    ';

    $content .= apalodi_generate_demo_content_gallery( 
        array(
            102 => '', 
            81 => 'When she reached the first hills of the Italic Mountains, she had a last view back on the skyline of her hometown Bookmarksgrove, the headline of Alphabet Village and the subline of her own road, the Line Lane.', 
            98 => '', 
            100 => '',
            76 => ''
        ),
        'wp-block-gallery alignwide columns-3 is-cropped',
        '"linkTo":"media","align":"wide"'
    );

    $content .= '

    <!-- wp:paragraph {"dropCap":true} -->
    <p class="has-drop-cap">When she reached the first hills of the Italic Mountains, she had a last view back on the skyline of her hometown Bookmarksgrove, the headline of Alphabet Village and the subline of her own road, the Line Lane. Pityful a rethoric question ran over her cheek, then she continued her way. On her way she met a copy.</p>
    <!-- /wp:paragraph -->

    <!-- wp:separator {"className":"is-style-dots"} -->
    <hr class="wp-block-separator is-style-dots"/>
    <!-- /wp:separator -->

    <!-- wp:paragraph -->
    <p>The copy warned the Little Blind Text, that where it came from it would have been rewritten a thousand times and everything that was left from its origin would be the word “and” and the&nbsp;<a href="https://themeforest.net/user/apalodi/portfolio" target="_blank" rel="noreferrer noopener">Little Blind Text</a>&nbsp;should turn around and return to its own, safe country.</p>
    <!-- /wp:paragraph -->

    <!-- wp:paragraph -->
    <p>But nothing the copy said could convince her and so it didn’t take long until a few insidious Copy Writers ambushed her.</p>
    <!-- /wp:paragraph -->

    ';

    $content .= apalodi_generate_demo_content_gallery( 
        array(
            97 => '', 
            99 => 'Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.', 
            100 => '', 
        ),
        'wp-block-gallery columns-3 is-cropped',
        '"linkTo":"media"'
    );

    $content .= '

    <!-- wp:paragraph -->
    <p>Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
    <!-- /wp:paragraph -->

    <!-- wp:quote -->
    <blockquote class="wp-block-quote"><p>Where it came from it would have been rewritten a thousand times and everything that was left from its origin would be the word “and” and the Little Blind Text should turn around and return to its own, safe country.</p><cite> <strong>Cicero</strong> </cite></blockquote>
    <!-- /wp:quote -->

    <!-- wp:paragraph -->
    <p>Pityful a&nbsp;rethoric question&nbsp;ran over her cheek, then she continued her way. On her way she met a copy.</p>
    <!-- /wp:paragraph -->

    <!-- wp:paragraph -->
    <p>When she reached the first hills of the Italic Mountains, she had a last view back on the skyline of her hometown Bookmarksgrove, the headline of Alphabet Village and the subline of her own road, the Line Lane. Pityful a rethoric question ran over her cheek, then she continued her way. On her way she met a copy.</p>
    <!-- /wp:paragraph -->';

    $in_tt_ids = join( ",", $post_ids );

    $wpdb->query(
        $wpdb->prepare(
            "
            UPDATE $wpdb->posts posts
            SET posts.post_content = %s
            WHERE posts.post_type = 'post'
            AND posts.ID IN ($in_tt_ids)
            ",
            $content
        )
    );
}
