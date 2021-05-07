<?php
/**
 * Template part for displaying featured posts in index.php
 *
 * @package     Kutak/Templates
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

 <div class="posts-featured">
    <div class="container">
        <div class="row posts-featured-row">

            <?php
            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => '1',
                'no_found_rows' => true,
                'ignore_sticky_posts' => true,
            );

            $first = new WP_Query( $args );

            while ( $first->have_posts() ) : 
                $first->the_post();
                apalodi_get_template( 'content', array( 'classes' => 'post-featured column flex' ) );
            endwhile; 

            wp_reset_postdata(); 

            apalodi_get_template( 'featured/tabs' ); ?>

        </div>
    </div>
</div>
