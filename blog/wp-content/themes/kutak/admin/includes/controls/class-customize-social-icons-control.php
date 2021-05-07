<?php
/**
 * Customize API: Apalodi_Customize_Social_Icons_Control class
 *
 * @package     Kutak/Admin/Controls
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * A Social Icons control.
 */
class Apalodi_Customize_Social_Icons_Control extends WP_Customize_Control {

    /**
    * The type of control being rendered
    */
    public $type = 'apalodi-social-icons';

    /**
     * Labels
     */
    public $labels = array();

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
        $this->json['labels'] = $this->labels;
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

        <div class="apalodi-social-icons-wrapper">
        <# _.each( data.value, function( social ) { #>
            <div class="apalodi-social-icons">
                <select value="{{ social.type }}">
                    <option value="">{{{ data.labels.select }}}</option>
                    <# _.each( data.choices, function( label, choice ) { #>
                    <option value="{{ choice }}"<# if ( social.type == choice ) { #> selected="selected"<# } #>>{{{ label }}}</option>
                    <# } ) #>
                </select>
                <label>
                    {{{ data.labels.placeholder }}}
                    <input type="text" value="{{ social.url }}" class="url" placeholder="https://example.com" />
                </label>
                <label>
                    {{{ data.labels.text }}}
                    <input type="text" value="{{ social.text }}" class="text" placeholder="Follow" />
                </label>
                <button class="button-link button-link-delete apalodi-social-icons-delete">{{{ data.labels.remove }}}</button>
            </div>
        <# } ) #>

        </div>

        <button class="button apalodi-social-icons-add">{{{ data.labels.add }}}</button>

        <?php
    }
}
