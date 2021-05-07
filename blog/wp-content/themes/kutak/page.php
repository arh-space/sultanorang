<?php
/**
 * The main template file.
 *
 * @package     Kutak
 * @since       1.0
 * @author      apalodi
 */

get_header(); ?>
    
    <div id="heading" class="site-heading">
        <div class="site-heading-container container">
            <h1 class="site-title"><?php the_title(); ?></h1>
        </div>
    </div><!-- .site-heading -->

    <div id="content" class="site-content container">
        <div id="primary" class="content-area">
            
            <?php
            while ( have_posts() ) :
                the_post();

                apalodi_get_template( 'content-page' );

                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;

            endwhile; // End of the loop.
            ?>

        </div><!-- #primary -->
    </div><!-- #content -->

<?php get_footer();
