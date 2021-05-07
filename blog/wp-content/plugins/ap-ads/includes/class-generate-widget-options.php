<?php
/**
 * Generate Widget Options.
 *
 * @package     AP_Ads/Classes
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * AP_Ads_Generate_Widget_Options class.
 */
class AP_Ads_Generate_Widget_Options {

    /**
     * Constructor.
     *
     * @since   1.0.0
     * @access  public
     */
    public function __construct() { }

    /**
     * Get label option.
     *
     * @since   1.0.0
     * @access  private
     * @param   string $type - Option type
     * @return  string $label
     */
    private function get_label( $type ) {

        $label = '';

        switch ( $type ) {
            case 'mobile_ad_size':
                $label = __( 'Mobile Ad size:', 'ap-ads' );
                break;
            case 'tablet_ad_size':
                $label = __( 'Tablet Ad size:', 'ap-ads' );
                break;
            case 'desktop_ad_size':
                $label = __( 'Desktop Ad size:', 'ap-ads' );
                break;
            case 'ad_code':
                $label = __( 'Enter custom Ad code', 'ap-ads' );
                break;
            default:
                break;
        }

        return $label;
    }

    /**
     * Get choices option.
     *
     * @since   1.0.0
     * @access  private
     * @param   string $type - Option type
     * @return  array $choices
     */
    private function get_choices( $type ) {

        $choices = array();

        switch ( $type ) {
            case 'mobile_ad_size':
            case 'tablet_ad_size':
            case 'desktop_ad_size':
                $choices = array( 
                    'responsive' => __( 'Responsive full width size', 'ap-ads' ),
                    '300x250' => __( '300x250 (Top performing ad size)', 'ap-ads' ),
                    '300x600' => __( '300x600 (Top performing ad size)', 'ap-ads' ),
                    '320x100' => __( '320x100 (Top performing ad size)', 'ap-ads' ),
                    '728x90' => __( '728x90 (Top performing ad size)', 'ap-ads' ),
                    '320x50' => __( '320x50 (Mobile-optimized banner)', 'ap-ads' ),
                    '970x90' => __( '970x90 (Large leaderboard)', 'ap-ads' ),
                    '970x250' => __( '970x250 (Billboard)', 'ap-ads' ),
                    '240x400' => __( '240x400 (Most popular size in Russia)', 'ap-ads' ),
                    '930x180' => __( '930x180 (Very popular size in Denmark)', 'ap-ads' ),
                    '980x120' => __( '980x120 (Most popular size in Sweden and Finland)', 'ap-ads' ),
                    '250x360' => __( '250x360 (Second most popular size in Sweden)', 'ap-ads' ),
                );
            default:
                break;
        }

        return $choices;
    }

    /**
     * Get form checkbox.
     *
     * @since   1.0.0
     * @access  public
     * @param   string $id - Option id
     * @param   object $widget - Widget instance object
     * @param   string $value - Option value
     * @return  string $html - Option html
     */
    public function checkbox( $id, $widget, $value ) {

        $label = $this->get_label( $id ); ?>
        
        <p>
            <input class="checkbox" type="checkbox"<?php checked( $value ); ?> id="<?php echo $widget->get_field_id( $id ); ?>" name="<?php echo $widget->get_field_name( $id ); ?>" />
            <label for="<?php echo $widget->get_field_id( $id ); ?>"><?php echo esc_html( $label ); ?></label>
        </p>

        <?php
    }

    /**
     * Get form text.
     *
     * @since   1.0.0
     * @access  public
     * @param   string $id - Option id
     * @param   object $widget - Widget instance object
     * @param   string $value - Option value
     * @return  string $html - Option html
     */
    public function text( $id, $widget, $value ) {

        $label = $this->get_label( $id ); ?>

        <p>
            <label for="<?php echo esc_attr( $widget->get_field_id( $id ) ); ?>"><?php echo esc_html( $label ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $widget->get_field_id( $id ) ); ?>" name="<?php echo esc_attr( $widget->get_field_name( $id ) ); ?>" type="text" value="<?php echo esc_attr( $value ); ?>" />
        </p>

        <?php
    }

    /**
     * Get form textarea.
     *
     * @since   1.0.0
     * @access  public
     * @param   string $id - Option id
     * @param   object $widget - Widget instance object
     * @param   string $value - Option value
     * @return  string $html - Option html
     */
    public function textarea( $id, $widget, $value ) {

        $label = $this->get_label( $id ); ?>

        <p>
            <label for="<?php echo esc_attr( $widget->get_field_id( $id ) ); ?>"><?php echo esc_html( $label ); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $widget->get_field_id( $id ) ); ?>" name="<?php echo esc_attr( $widget->get_field_name( $id ) ); ?>" rows="6"><?php echo wp_kses_post( $value ); ?></textarea>
        </p>

        <?php
    }

    /**
     * Get form number.
     *
     * @since   1.0.0
     * @access  public
     * @param   string $id - Option id
     * @param   object $widget - Widget instance object
     * @param   string $value - Option value
     * @param   int $min - Min value
     * @param   int $max - Max value
     * @param   int $step - Step increase
     * @return  string $html - Option html
     */
    public function number( $id, $widget, $value, $min = 1, $max = 24, $step = 1 ) {

        $label = $this->get_label( $id ); ?>

        <p>
            <label for="<?php echo esc_attr( $widget->get_field_id( $id ) ); ?>"><?php echo esc_html( $label ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $widget->get_field_id( $id ) ); ?>" name="<?php echo esc_attr( $widget->get_field_name( $id ) ); ?>" type="number" step="<?php echo esc_attr( $step ); ?>" min="<?php echo esc_attr( $min ); ?>" max="<?php echo esc_attr( $max ); ?>" size="3" value="<?php echo esc_attr( $value ); ?>" />
        </p>

        <?php
    }

    /**
     * Get form select.
     *
     * @since   1.0.0
     * @access  public
     * @param   string $id - Option id
     * @param   object $widget - Widget instance object
     * @param   string $value - Option value
     * @param   string $remove_choices - Remove choice
     * @return  string $html - Option html
     */
    public function select( $id, $widget, $value, $remove_choices = array() ) {

        $label = $this->get_label( $id ); 
        $choices = $this->get_choices( $id );

        foreach ( $remove_choices as $remove ) {
            unset( $choices[$remove] );
        }
        ?>

        <p>
            <label for="<?php echo esc_attr( $widget->get_field_id( $id ) ); ?>"><?php echo esc_html( $label ); ?></label>
            <select name="<?php echo esc_attr( $widget->get_field_name( $id ) ); ?>" id="<?php echo esc_attr( $widget->get_field_id( $id ) ); ?>" class="widefat">
                <?php foreach ( $choices as $key => $title ) {
                    ?>
                    <option value="<?php echo esc_attr( $key ); ?>"<?php selected( $value, $key ); ?>><?php echo esc_html( $title ); ?></option>
                    <?php
                }
                ?>
            </select>
        </p>

        <?php
    }

}
