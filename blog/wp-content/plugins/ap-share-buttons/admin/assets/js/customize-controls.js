/**
 * Scripts within the customizer controls window.
 *
 * Contextually shows the color hue control and informs the preview
 * when users open or close the front page sections section.
 */

(function( wp, $ ) {

    // save share buttons as array
    wp.customize.controlConstructor['ap-share-buttons'] = wp.customize.Control.extend( {
        ready: function() {
            var control = this;

            this.container.on( 'change', 'input', function() {

                var value = [],
                    i = 0;

                // Build the value as an object using the sub-values from individual checkboxes.
                $.each( control.params.choices, function( key ) {
                    if ( control.container.find( 'input[value="' + key + '"]' ).is( ':checked' ) ) {
                        value[ i ] = key;
                        i++;
                    }
                } );

                control.setting.set( value );

            } );
        }
    } );

})( wp, jQuery );
