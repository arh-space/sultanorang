<?php
/**
 * Customize API: AP_Share_Buttons_Customize_Control class
 *
 * @package     AP_Share_Buttons/Admin/Controls
 * @since       1.0.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * A Share Buttons control.
 */
class AP_Share_Buttons_Customize_Control extends WP_Customize_Control {

   /**
    * The type of control being rendered
    */
   public $type = 'ap-share-buttons';

   /**
     * Refresh the parameters passed to the JavaScript via JSON.
     *
     * @since   1.0.0
     * @uses    WP_Customize_Control::to_json()
     * @access  public
     */
    public function to_json() {
        parent::to_json();
        $this->json['id'] = $this->id;
        $this->json['link'] = $this->get_link();
        $this->json['choices'] = $this->choices;
        $this->json['value'] = $this->value();
        $this->json['default'] = $this->setting->default;
        if ( isset( $this->default ) ) {
            $this->json['default'] = $this->default;
        }
    }

    /**
     * Don't render the control content from PHP, as it's rendered via JS on load.
     *
     * @since   1.0.0
     * @access  public
     */
    public function render_content() {}

   /**
     * An Underscore (JS) template for this control's content (but not its container).
     *
     * Class variables for this control class are available in the `data` JS object;
     * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
     *
     * @since   1.0.0
     * @see     WP_Customize_Control::print_template()
     * @access  protected
     */
    protected function content_template() {
        ?>

        <# if ( ! data.choices ) { return; } #>

        <# if ( data.label ) { #><span class="customize-control-title">{{{ data.label }}}</span><# } #>
        <# if ( data.description ) { #><span class="description customize-control-description">{{{ data.description }}}</span><# } #>

        <ul>
        <# _.each( data.choices, function( label, choice ) { #>
            <li>
                <input id="{{ data.id }}-{{ choice }}" type="checkbox" value="{{ choice }}" <# if ( _.contains( data.value, choice ) ) { #> checked="checked"<# } #> />
                <label for="{{ data.id }}-{{ choice }}">{{ label }}</label>
            </li>
        <# } ) #>

        </ul>

        <?php
    }
}
