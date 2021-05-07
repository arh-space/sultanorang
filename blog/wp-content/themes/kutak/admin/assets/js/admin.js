( function( $ ) {
    'use strict';

    // Upload media
    $( document ).on( 'click', '.btn-add-menu-item-bg-image', function( event ) {
        event.preventDefault();

        var $this = $( this ),
            $parent = $this.parents( '.field-bg-image' ),
            $image_id = $parent.find( '.edit-menu-item-bg-image' ),
            $image_wrapper = $parent.find( '.field-bg-image-placeholder' ),
            $image = $parent.find( '.field-bg-image-placeholder img' ),
            library = 'image',
            size = 'thumbnail',
            frame;

        // If the frame already exists, re-open it.
        if ( frame ) {
            frame.open();
            return;
        }

        // Create the media frame.
        frame = wp.media( {
            title: 'Media',
            library: { type: library }
        } );

        // Runs when media is selected.
        frame.on( 'select', function() {

            var selection = frame.state().get( 'selection' );

            selection.map( function( attachment ) {

                // Grabs the attachment selection and creates a JSON representation of the model.
                var attachment = attachment.toJSON();
                var image_url = attachment.sizes.thumbnail.url;

                // Change image src
                if ( $image.length ) {
                    $image.attr( 'src', image_url );
                } else {
                    $image_wrapper.prepend( '<img src="' + image_url + '" />' );
                }

                // Sends the attachment ID to our custom input field.
                $image_id.val( attachment.id );

            } );
        } );

        // Finally, open the modal.
        frame.open();

    } );

    // Remove media
    $( document ).on( 'click', '.field-bg-image-remove', function( event ) {
        event.preventDefault();

        var $this = $( this ),
            $parent = $this.parents( '.field-bg-image' ),
            $image_id = $parent.find( '.edit-menu-item-bg-image' ),
            $image = $parent.find( '.field-bg-image-placeholder img' );

        $image.remove();
        $image_id.val( '' );

    } );

} )( jQuery );
