( function( $ ) {
    'use strict';

    $( document ).on( 'click', '.notice[data-apalodi-dismissible] .notice-dismiss', function ( event ) {
        event.preventDefault();

        var $this = $( this ),
            notice = $this.parent().attr( 'data-apalodi-dismissible' ),
            expiration = $this.parent().attr( 'data-expiration' );

        var data = {
            'action': 'apalodi_dismiss_admin_notice',
            'notice': notice,
            'expiration': expiration,
            'nonce': apalodi_dismissible_notices.nonce
        };

        $.post( ajaxurl, data );
    });

} )( jQuery );
