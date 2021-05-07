<?php
/**
 * Template for displaying site navigation
 *
 * @package     Kutak/Templates
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="site-navigation">

    <?php

    if ( is_apalodi_header_type( 'modern' ) ) : ?>

        <div class="site-navigation-container">

            <?php

            wp_nav_menu( array( 
                'theme_location' => 'primary',
                'container' => 'nav',
                'container_class' => 'main-navigation',
                'items_wrap' => '<ul class="menu flex flex-wrap align-center">%3$s</ul>',
                'link_before' => '<span>',
                'link_after' => '</span>',
                'depth' => 1,
                'cache_results' => true,
                'fallback_cb' => false,
            ));

            wp_nav_menu( array( 
                'theme_location' => 'secondary',
                'container' => 'nav',
                'container_class' => 'secondary-navigation',
                'items_wrap' => '<ul class="sec-menu">%3$s</ul>',
                'link_before' => '<span>',
                'link_after' => '</span>',
                'depth' => 1,
                'cache_results' => true,
                'fallback_cb' => false,
            ));        

            apalodi_social_icons(); ?>

        </div>

    <?php else : 

        wp_nav_menu( array( 
            'theme_location' => 'primary',
            'container' => 'nav',
            'container_class' => 'main-navigation',
            'items_wrap' => '<ul class="classic-menu flex">%3$s</ul>',
            'link_before' => '<span>',
            'link_after' => '</span>',
            'cache_results' => true,
            'fallback_cb' => false,
        ));

    endif; ?>

</div>
