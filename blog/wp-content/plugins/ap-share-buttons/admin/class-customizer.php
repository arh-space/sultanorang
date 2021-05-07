<?php
/**
 * Customizer settings.
 *
 * @package     AP_Share_Buttons/Classes
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * AP_Share_Buttons_Customizer class.
 */
class AP_Share_Buttons_Customizer {

    /**
     * Hook in methods.
     *
     * @since   1.0.0
     * @access  public
     */
    public static function init() {
        add_action( 'customize_register', array( __CLASS__, 'customize_register' ) );
        add_action( 'customize_controls_enqueue_scripts', array( __CLASS__, 'customize_controls_enqueue_scripts' ) );
        // add_action( 'customize_preview_init', array( __CLASS__, 'customize_preview_scripts' ) );
    }

    /**
     * Add customizer options.
     *
     * @since   1.0.0
     * @access  public
     * @param   WP_Customize_Manager $wp_customize Theme Customizer object.
     */
    public static function customize_register( $wp_customize ) {

        $dir = ap_share_buttons()->get_plugin_path();
        require_once( $dir . 'admin/class-customize-share-buttons-control.php' );

        $wp_customize->register_control_type( 'AP_Share_Buttons_Customize_Control' );

        $wp_customize->add_section( 'ap_share_buttons', array(
            'title' => esc_html__( 'AP Share Buttons', 'ap-share-buttons' ),
        ) );

        $wp_customize->add_setting( 'ap_share_buttons_open_graph', array(
            'default' => true,
            'type' => 'option',
            'transport' => 'postMessage',
            'sanitize_callback' => array( __CLASS__, 'sanitize_checkbox' ),
        ) );

        $wp_customize->add_control( 'ap_share_buttons_open_graph', array(
            'type' => 'checkbox',
            'label' => esc_html__( 'Add Open Graph Tags', 'ap-share-buttons' ),
            'description' => esc_html__( 'Disable this option if you are using some other plugin that adds Open Graph tags.', 'ap-share-buttons' ),
            'section' => 'ap_share_buttons',
            'priority' => 1,
        ) );

        $wp_customize->add_setting( 'ap_share_buttons', array(
            'default' => array( 'facebook', 'twitter', 'mail' ),
            'type' => 'option',
            'transport' => 'postMessage',
            'sanitize_callback' => array( __CLASS__, 'sanitize_share_buttons' ),
        ) );

        $wp_customize->add_control( new AP_Share_Buttons_Customize_Control( $wp_customize, 'ap_share_buttons', array(
            'label' => esc_html__( 'Share Buttons', 'ap-share-buttons' ),
            'description' => esc_html__( 'Add sharing buttons.', 'ap-share-buttons' ),
            'section' => 'ap_share_buttons',
            'choices' => ap_share_buttons()->get_choices(),
        ) ) );

        $wp_customize->selective_refresh->add_partial( 'ap_share_buttons', array(
            'selector' => '.share-buttons',
            'container_inclusive' => true,
            'render_callback' => function() {
                ap_share_buttons_template();
            }
        ) );
    }

    /**
     * Sanitize checkbox.
     *
     * @since   1.0
     * @access  public
     * @param   bool $checked Whether the checkbox is checked.
     * @return  bool Whether the checkbox is checked.
     */
    public static function sanitize_checkbox( $checked ) {
        return $checked ? true : false;
    }

    /**
     * Sanitize share buttons.
     *
     * @since   1.0
     * @access  public
     * @param   array   $buttons    Values to sanitize.
     * @param   object  $setting    Setting instance.
     * @return  array   $buttons    Sanitized buttons.
     */
    public static function sanitize_share_buttons( $buttons, $setting ) {

        $_buttons = array();
        $choices = array_keys( $setting->manager->get_control( $setting->id )->choices );

        foreach ( $buttons as $key => $button ) {
            if ( in_array( $button, $choices ) ) {
                $_buttons[] = $button;
            }
        }

        return $_buttons;
    }

    /**
     * Load dynamic logic for the customizer controls area.
     *
     * @since   1.0
     * @access  public
     */
    public static function customize_controls_enqueue_scripts() {

        $version = ap_share_buttons()->get_version();
        $dir = ap_share_buttons()->get_plugin_url();

        wp_enqueue_script( 'ap-share-buttons-customize-controls', $dir . 'admin/assets/js/customize-controls.js', array(), $version, true );
    }

    /**
     * Bind JS handlers to instantly live-preview changes.
     *
     * @since   1.0
     * @access  public
     */
    public static function customize_preview_scripts() {

    }

}

AP_Share_Buttons_Customizer::init();
