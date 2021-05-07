<?php
/**
 * The template for displaying category archive
 *
 * @package     Kutak
 * @since       1.0
 * @author      apalodi
 */

get_header(); ?>

    <div class="site-heading">
        <div class="container">

            <div class="term-heading align-middle align-justify">
                <div class="term-header">
                    <div class="post-cats"><div class="post-cat post-tag"><?php esc_html_e( 'Category', 'kutak' ); ?></div></div>
                    <h1><?php echo esc_html( single_term_title( '', true ) ); ?></h1>
                    <?php the_archive_description(); ?>
                </div>
                <div class="term-count"><span><?php echo esc_html( apalodi_get_found_posts() ); ?></span><?php echo esc_html( sprintf( _n( 'Article', 'Articles', apalodi_get_found_posts(), 'kutak' ) ) ); ?></div>
            </div>

        </div>
    </div>

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
