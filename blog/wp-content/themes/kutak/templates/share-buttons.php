<?php
/**
 * Social Icons.
 *
 * @package     Kutak/Templates/
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

$share_buttons = apalodi_get_share_buttons();

if ( $share_buttons ) :
    
    $default_onclick = "javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;";
    $onclick = array(
        'facebook' => "javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=600');return false;",
        'twitter' => "javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=420,width=550');return false;",
        'google-plus' => "javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=740,width=570'); return false;",
        'pinterest' => $default_onclick,
        'tumblr' => $default_onclick,
        'linkedin' => $default_onclick,
        'reddit' => $default_onclick,
        'vk' => $default_onclick,
        'facebook-messenger' => '',
        'whatsapp' => '',
        'viber' => '',
        'mail' => '',
    );


    $share_text = array(
        'facebook' => 'Facebook',
        'twitter' => 'Twitter',
        'google-plus' => 'Google Plus', 
        'facebook-messenger' => 'Messenger',
        'whatsapp' => 'WhatsApp',
        'viber' => 'Viber',
        'pinterest' => 'Pinterest',
        'tumblr' => 'Tumblr',
        'reddit' => 'Reddit',
        'linkedin' => 'Linkedin',
        'vk' => 'VK',
        'mail' => 'Mail',
    ); ?>

<div class="share-buttons single-meta">
    <h6 class="share-buttons-title single-meta-title"><?php esc_html_e( 'Share this article', 'kutak' ); ?></h6>

    <?php foreach ( $share_buttons as $key => $social ) :
        $donclick = $onclick[$social] != '' ? ' onclick="'. $onclick[$social] .'"' : '';
        echo '<a href="'. esc_url( kutak_get_share_link( $social ) ) .'" class="share-button icon-'. esc_attr( $social ) .'"'. $donclick .'></a>';            
    endforeach; ?> 
</div>

<?php endif;
