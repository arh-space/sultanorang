/**
 * File customize-preview.js.
 *
 * Instantly live-update customizer settings in the preview for improved user experience.
 */

(function( $ ) {

    // Accent color
    wp.customize( 'accent_color', function( value ) {
        value.bind( function( to ) {

            // Update custom color CSS.
            var style = $( '#kutak-custom-colors' ),
                color = style.data( 'color' ),
                css = style.html();

            css = css.replace( new RegExp( color, 'g' ), to );
            style.html( css ).data( 'color', to );
        });
    });

} )( jQuery );
