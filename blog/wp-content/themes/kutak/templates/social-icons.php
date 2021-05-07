<?php
/**
 * Social Icons.
 *
 * @package     Kutak/Templates/
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

$social_icons = apalodi_get_social_icons();

if ( $social_icons ) : ?> 
    <div class="social-icons"> 
        <?php foreach ( $social_icons as $key => $social ) : ?>
        <a class="social-icon icon-<?php echo esc_attr( $social['type'] ); ?>" href="<?php echo esc_url( $social['url'] ); ?>" target="_blank"><?php echo '' != $social['text'] ? '<span>'. esc_html( $social['text'] ) .'</span>' : ''; ?></a>
    <?php endforeach; ?> 
    </div>
<?php endif; 