<?php
/**
 * Related Post.
 *
 * @package     Kutak/Templates
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="post-related">
    <div class="posts-container container">
        <h2 class="section-title"><?php esc_html_e( 'You might also like', 'kutak' ); ?></h2>
        <div class="row">
            <?php 

            $args = array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => '6',
                'post__in'  => apalodi_get_related_posts_ids(),
                'update_post_thumbnail_cache' => true,
                'orderby' => 'post__in',
                'no_found_rows' => true,
                'ignore_sticky_posts' => true,
            );

            $related = new WP_Query( $args );

            while ( $related->have_posts() ) : 
                $related->the_post();
                apalodi_get_template( 'content-small' );
            endwhile; 

            wp_reset_postdata(); ?>
        </div>
    </div>
</div>