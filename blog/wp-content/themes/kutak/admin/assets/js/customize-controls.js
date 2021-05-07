/**
 * Scripts within the customizer controls window.
 *
 * Contextually shows the color hue control and informs the preview
 * when users open or close the front page sections section.
 */

(function( wp, $ ) {

    // save social icons as array with type and url
    wp.customize.controlConstructor['apalodi-social-icons'] = wp.customize.Control.extend( {
        ready: function() {
            var control = this;

            // Make our fields sortable
            $( '.apalodi-social-icons-wrapper' ).sortable({
                update: function(event, ui) {
                    $( '.apalodi-social-icons:first input' ).trigger( 'change' );
                }
            });

            this.container.on( 'change', 'select, input', function() {

                var value = [],
                    i = 0;

                var $this = $( this );
                var $parent = $this.parents( '.apalodi-social-icons-wrapper' );
                var $rep = $parent.find( '.apalodi-social-icons' );

                $rep.each( function( index, element ) {
                    var type = $( element ).find( 'select' ).val();
                    var link = $( element ).find( 'input.url' ).val();
                    var text = $( element ).find( 'input.text' ).val();

                    if ( '' != type && '' != link ) {
                        value.push({'type': type, 'url': link, 'text': text });
                    }
                });

                control.setting.set( value );
                // this.container.trigger('change');

            } );

            control.container.on( 'click', '.apalodi-social-icons-add', function( e ) {
                e.preventDefault();

                var choices = control.params.choices;
                var labels = control.params.labels;
                // console.log( control.params );
                var $this = $( this ),
                    $wrapper = $this.prev();

                var options = '';

                for (var type in choices ) {
                    options += '<option value="'+ type +'">'+ choices[type] +'</option>';
                }

                var social_icons = '\
                <div class="apalodi-social-icons">\
                    <select value="">\
                        <option value="">'+ labels.select +'</option>\
                        '+ options +'\
                    </select>\
                    <label>\
                        ' + labels.placeholder + '\
                        <input type="text" value="" class="url" placeholder="https://example.com" />\
                    </label>\
                    <label>\
                        ' + labels.text + '\
                        <input type="text" value="" class="text" placeholder="Follow" />\
                    </label>\
                    <button class="button-link button-link-delete apalodi-social-icons-delete">' + labels.remove + '</button>\
                </div>'

                $wrapper.append( social_icons );
            });

            control.container.on( 'click', '.apalodi-social-icons-delete', function( e ) {
                e.preventDefault();

                $( this ).parents( '.apalodi-social-icons' ).remove();
                control.setting.set( [] );
                $( '.apalodi-social-icons:first input' ).trigger( 'change' );
            });
        }
    } );

})( wp, jQuery );
