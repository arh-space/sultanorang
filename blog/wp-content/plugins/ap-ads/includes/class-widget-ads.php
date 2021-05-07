<?php
/**
 * Widget API: AP_Ads_Widget class
 *
 * @package     AP_Ads/Widgets
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * AP_Ads_Widget class.
 */
class AP_Ads_Widget extends WP_Widget {

    /**
     * Sets up a new widget instance.
     *
     * @since   1.0.0
     * @access  public
     */
    public function __construct() {
        parent::__construct( 
            'ap-ads', 
            esc_html__( 'AP Ads', 'ap-ads' ),
            array(
                'classname' => 'widget-ap-ads',
                'description' => esc_html__( 'Add ads to your site.', 'ap-ads' ),
                'customize_selective_refresh' => true,
            ) 
        );
    }

    /**
     * Outputs the content for the current widget instance.
     *
     * @since   1.0.0
     * @access  public
     * @param   array   $args       Widgets arguments
     * @param   array   $instance   Settings for the current widget instance
     */
    public function widget( $args, $instance ) {

        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }

        $mobile_ad_size = ( ! empty( $instance['mobile_ad_size'] ) ) ? $instance['mobile_ad_size'] : '300x250';
        $tablet_ad_size = ( ! empty( $instance['tablet_ad_size'] ) ) ? $instance['tablet_ad_size'] : '300x250';
        $desktop_ad_siz = ( ! empty( $instance['desktop_ad_size'] ) ) ? $instance['desktop_ad_size'] : '300x250';

        $ad_code = ( ! empty( $instance['ad_code'] ) ) ? $instance['ad_code'] : '';

        $classes = array(
            'ad-m-' . $mobile_ad_size,
            'ad-t-' . $tablet_ad_size,
            'ad-d-' . $desktop_ad_siz,
        );

        $classes = implode( ' ', $classes );

        echo $args['before_widget'];

        echo '<div class="ad '. esc_attr( $classes ) .'">';
        echo do_shortcode( $ad_code );
        echo '</div>';

        echo $args['after_widget'];
    }

    /**
     * Handles updating the settings for the current widget instance.
     *
     * @since   1.0.0
     * @access  public
     * @param   array   $new_instance     New settings for this instance.
     * @param   array   $old_instance     Old settings for this instance.
     * @return  array   $instance         Updated settings to save.
     */
    public function update( $new_instance, $old_instance ) {

        $instance = $old_instance;

        $instance['mobile_ad_size'] = isset( $new_instance['mobile_ad_size'] ) ? $new_instance['mobile_ad_size'] : '300x250';
        $instance['tablet_ad_size'] = isset( $new_instance['tablet_ad_size'] ) ? $new_instance['tablet_ad_size'] : '300x250';
        $instance['desktop_ad_size'] = isset( $new_instance['desktop_ad_size'] ) ? $new_instance['desktop_ad_size'] : '300x250';

        $instance['ad_code'] = isset( $new_instance['ad_code'] ) ? $new_instance['ad_code'] : '';

        return $instance;
    }

    /**
     * Outputs the settings form for the widget.
     *
     * @since   1.0.0
     * @access  public
     * @param   array $instance Current settings.
     */
    public function form( $instance ) {

        $mobile_ad_size = isset( $instance['mobile_ad_size'] ) ? $instance['mobile_ad_size'] : '300x250';
        $tablet_ad_size = isset( $instance['tablet_ad_size'] ) ? $instance['tablet_ad_size'] : '300x250';
        $desktop_ad_size = isset( $instance['desktop_ad_size'] ) ? $instance['desktop_ad_size'] : '300x250';

        $ad_code = isset( $instance['ad_code'] ) ? $instance['ad_code'] : '';

        ap_ads()->option->select( 'mobile_ad_size', $this, $mobile_ad_size );
        ap_ads()->option->select( 'tablet_ad_size', $this, $tablet_ad_size );
        ap_ads()->option->select( 'desktop_ad_size', $this, $desktop_ad_size );

        ap_ads()->option->textarea( 'ad_code', $this, $ad_code );
    }
}
