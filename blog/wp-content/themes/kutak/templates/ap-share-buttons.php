<?php
/**
 * Social Icons.
 *
 * @package     Kutak/Templates/
 * @since       2.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

$share_buttons = ap_share_buttons_get_values();

if ( $share_buttons ) : ?>

    <div class="share-buttons single-meta">
        <h6 class="share-buttons-title single-meta-title"><?php esc_html_e( 'Share with friends', 'kutak' ); ?></h6>

        <?php foreach ( $share_buttons as $key => $social ) :
            $onclick = ap_share_buttons_get_onclick( $social );
            $donclick = $onclick != '' ? ' onclick="'. $onclick .'"' : '';
            echo '<a href="'. esc_url( ap_share_buttons_get_link( $social ) ) .'" aria-label="'. sprintf( esc_attr__( 'Share with %s', 'kutak' ), $social ) .'" class="share-button icon-'. esc_attr( $social ) .'"'. $donclick .'></a>';
        endforeach; ?> 
    </div>

<?php endif;
