<?php
/**
 * Template part for displaying featured tabs
 *
 * @package     Kutak/Templates
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="posts-featured-sidebar column">

    <div class="featured-tabs">

        <?php

        $tabs = apply_filters( 'apalodi_featured_tabs', array(
            '5' => array(
                'key' => 'recommended',
                'title' => __( 'Recommended', 'kutak' )
            )
        ) );

        ksort( $tabs );

        $is_first = true;
        foreach ( $tabs as $position => $tab ) : 
            $active_class = $is_first ? ' is-active' : '';
            $is_first = false;
            ?>
            <button class="featured-tab<?php echo esc_attr( $active_class ); ?>" data-id="#<?php echo esc_attr( $tab['key'] ); ?>-posts"><?php echo esc_html( $tab['title'] ); ?></button>
        <?php endforeach;
        ?>
                                
    </div>
    
    <?php

    $is_first = true;
    foreach ( $tabs as $position => $tab ) : 
        $active_class = $is_first ? ' is-active' : '';
        $is_first = false;
        ?>

        <div id="<?php echo esc_attr( $tab['key'] ); ?>-posts" class="featured-panel row<?php echo esc_attr( $active_class ); ?>">
            <?php do_action( 'apalodi_featured_section', $tab['key'] ); ?>
        </div>
    
    <?php endforeach; ?>

</div>
