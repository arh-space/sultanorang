<?php
/**
 * The main template file.
 *
 * @package     Kutak
 * @since       1.0
 * @author      apalodi
 */

get_header(); ?>

    <div id="content" class="site-content">
        <div id="primary" class="content-area">

            <?php
            while ( have_posts() ) :
                the_post();

                apalodi_get_template( 'content-single' );

            endwhile; // End of the loop.
            ?>

        </div><!-- #primary -->
    </div><!-- #content -->

<?php get_footer();
