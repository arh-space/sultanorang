<?php
/**
 * Customizer Functions.
 *
 * @package     Kutak/Admin/Functions
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Add theme customizer options.
 *
 * @since   1.0
 * @access  public
 * @param   WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function apalodi_customize_register( $wp_customize ) {

    $admin_dir = get_template_directory() . '/admin/';
    require_once( $admin_dir . 'includes/controls/class-customize-social-icons-control.php' );

    $wp_customize->register_control_type( 'Apalodi_Customize_Social_Icons_Control' );

    /** 
     * Theme Options Panel
     */
    $wp_customize->add_panel( 'kutak_options', array(
        'title' => esc_html__( 'Theme Options', 'kutak' ),
    ) );

    /** 
     * Mobile logo
     */
    $wp_customize->add_setting( 'mobile_logo', array(
        'default' => '',
        'transport' => 'postMessage',
        'sanitize_callback' => 'absint',
    ) );

    $wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize, 'mobile_logo', array(
        'label' => esc_html__( 'Mobile Logo (optional)', 'kutak' ),
        'section' => 'title_tagline',
        'priority' => 8,
        'flex_width'  => true,
        'flex_height' => false, 
        'width' => 300,
        'height' => 100,
    ) ) );

    /** 
     * Custom colors
     */
    $wp_customize->add_setting( 'accent_color', array(
        'default' => '#d93f7e',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'accent_color', array(
        'label' => esc_html__( 'Accent Color', 'kutak' ),
        'description' => esc_html__( 'Use the color #444 for color neutral style.', 'kutak' ),
        'section' => 'colors',
        'priority' => 5,
    ) ) );

    $wp_customize->add_setting( 'theme_color', array(
        'default' => '#ffffff',
        'transport' => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'theme_color', array(
        'label' => esc_html__( 'Theme Color', 'kutak' ),
        'description' => esc_html__( 'Set the color for mobile toolbar (Only some modern browsers).', 'kutak' ),
        'section' => 'colors',
        'priority' => 5,
    ) ) );

    /** 
     * Blog
     */
    $wp_customize->add_section( 'kutak_header', array(
        'title' => esc_html__( 'Header', 'kutak' ),
        'panel' => 'kutak_options',
    ) );

    $wp_customize->add_setting( 'header_type', array(
        'default' => 'modern',
        'sanitize_callback' => 'apalodi_sanitize_select',
    ) );

    $wp_customize->add_control( 'header_type', array(
        'type' => 'select',
        'label' => esc_html__( 'Header Type', 'kutak' ),
        'section' => 'kutak_header',
        'choices'    => array(
            'modern' => esc_html__( 'Modern', 'kutak' ),
            'classic' => esc_html__( 'Classic', 'kutak' ),
        ),
    ) );

    /** 
     * Blog
     */
    $wp_customize->add_section( 'kutak_blog', array(
        'title' => esc_html__( 'Blog', 'kutak' ),
        'panel' => 'kutak_options',
    ) );

    $wp_customize->add_setting( 'blog_pagination', array(
        'default' => 'infinite-scroll',
        'sanitize_callback' => 'apalodi_sanitize_select',
    ) );

    $wp_customize->add_control( 'blog_pagination', array(
        'type' => 'select',
        'label' => esc_html__( 'Blog Pagination', 'kutak' ),
        'section' => 'kutak_blog',
        'choices'    => array(
            'infinite-scroll' => esc_html__( 'Infinite Scroll', 'kutak' ),
            'load-more' => esc_html__( 'Load More', 'kutak' ),
            'numbers' => esc_html__( 'Numbers', 'kutak' ),
        ),
    ) );

    /** 
     * Media
     */
    $wp_customize->add_section( 'kutak_media', array(
        'title' => esc_html__( 'Media', 'kutak' ),
        'panel' => 'kutak_options',
    ) );

    $wp_customize->add_setting( 'lazy_load_media', array(
        'default' => true,
        'transport' => 'postMessage',
        'sanitize_callback' => 'apalodi_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'lazy_load_media', array(
        'type' => 'checkbox',
        'label' => esc_html__( 'Lazy Load Media', 'kutak' ),
        'description' => esc_html__( 'Lazy load media as you scroll. It makes your site loading speed fast.', 'kutak' ),
        'section' => 'kutak_media',
    ) );

    /** 
     * Social icons
     */
    $wp_customize->add_section( 'kutak_social_icons', array(
        'title' => esc_html__( 'Social Icons', 'kutak' ),
        'panel' => 'kutak_options',
    ) );

    $wp_customize->add_setting( 'social_icons', array(
        'default' => array(),
        'transport' => 'postMessage',
        'sanitize_callback' => 'apalodi_sanitize_social_icons',
    ) );

    $wp_customize->add_control( new Apalodi_Customize_Social_Icons_Control( $wp_customize, 'social_icons', array(
        'label' => esc_html__( 'Social Icons', 'kutak' ),
        'section' => 'kutak_social_icons',
        'choices' => array(
            'facebook' => 'Facebook', 
            'instagram' => 'Instagram', 
            'twitter' => 'Twitter',
            'google-plus' => 'Google Plus', 
            'pinterest' => 'Pinterest', 
            'tumblr' => 'Tumblr', 
            'reddit' => 'Reddit', 
            'linkedin' => 'Linkedin', 
            'vk' => 'VK', 
        ),
        'labels' => array(
            'select' => esc_html__( 'Select social type', 'kutak' ),
            'placeholder' => esc_html__( 'Enter social URL', 'kutak' ),
            'text' => esc_html__( 'Social text (Optional)', 'kutak' ),
            'add' => esc_html__( 'Add new social icon', 'kutak' ),
            'remove' => esc_html__( 'Remove', 'kutak' ),
        ),
    ) ) );

    $wp_customize->selective_refresh->add_partial( 'social_icons', array(
        'selector' => '.social-icons',
        'container_inclusive' => true,
        'render_callback' => function() {
            apalodi_get_template( 'social-icons' );
        }
    ) );

    /** 
     * Translations
     */
    $wp_customize->add_section( 'kutak_translations', array(
        'title' => esc_html__( 'Translations', 'kutak' ),
        'panel' => 'kutak_options',
    ) );

    $wp_customize->add_setting( 'i18n', array(
        'default' => false,
        'sanitize_callback' => 'apalodi_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'i18n', array(
        'type' => 'checkbox',
        'label' => esc_html__( 'Internationalization', 'kutak' ),
        'description' => esc_html__( 'If you want to have a multilingual site you need to enable Internationalization and use the textdomain "kutak" to translate the text.', 'kutak' ),
        'section' => 'kutak_translations',
        'priority' => 1,
    ) );

    $default = '&copy; %s Kutak - WordPress Theme by APALODI';
    $wp_customize->add_setting( 'copyright', array(
        'default' => $default,
        'transport' => 'postMessage',
        'sanitize_callback' => 'wp_kses_post',
    ) );

    $wp_customize->add_control( 'copyright', array(
        'type' => 'textarea',
        'label' => esc_html__( 'Copyright', 'kutak' ),
        'description' => esc_html__( '%s is placeholder for current year', 'kutak' ),
        'section' => 'kutak_translations',
    ) );

    $wp_customize->selective_refresh->add_partial( 'copyright', array(
        'selector' => '.copyright',
        'container_inclusive' => true,
        'render_callback' => function() {
            apalodi_get_template( 'copyright' );
        }
    ) );

}
add_action( 'customize_register', 'apalodi_customize_register' );

/**
 * Load dynamic logic for the customizer controls area.
 *
 * @since   1.0
 * @access  public
 */
function apalodi_customize_controls_enqueue_scripts() {

    $version = apalodi_get_theme_info( 'version' );

    // Enqueue CSS
    wp_enqueue_style( 'kutak-customize-controls', get_theme_file_uri( '/admin/assets/css/customize-controls.css' ), array(), $version );

    // Enqueue JS
    wp_enqueue_script( 'kutak-customize-controls', get_theme_file_uri( '/admin/assets/js/customize-controls.js' ), array(), $version, true );
}
add_action( 'customize_controls_enqueue_scripts', 'apalodi_customize_controls_enqueue_scripts' );

/**
 * Bind JS handlers to instantly live-preview changes.
 *
 * @since   1.0
 * @access  public
 */
function apalodi_customize_preview_scripts() {

    $version = apalodi_get_theme_info( 'version' );

    // Enqueue JS
    wp_enqueue_script( 'kutak-customize-preview', get_theme_file_uri( '/admin/assets/js/customize-preview.js' ), array( 'customize-preview' ), $version, true );
}
add_action( 'customize_preview_init', 'apalodi_customize_preview_scripts' );
