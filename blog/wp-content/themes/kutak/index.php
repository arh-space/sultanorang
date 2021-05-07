<?php
/**
 * The main template file.
 *
 * @package     Kutak
 * @since       1.0
 * @author      apalodi
 */

get_header(); ?>

    <?php 

    if ( ! is_paged() ) {
        apalodi_get_template( 'featured/content' ); 
    }

    ?>

    <div id="content" class="site-content container">
        <div id="primary" class="content-area">

            <?php if ( have_posts() ) : ?>

                <div class="posts-container">

                    <div class="row">

                    <?php while ( have_posts() ) : the_post();

                        apalodi_get_template( 'content', array( 'classes' => 'column flex' ) );

                    endwhile; ?>

                    </div>

                    <?php the_posts_navigation(); ?>

                </div> 

            <?php else :

                apalodi_get_template( 'content-none' );

            endif; ?>

        </div><!-- #primary -->
    </div><!-- #content -->

<?php get_footer();
