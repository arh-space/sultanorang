<?php
/**
 * The template for displaying search results pages
 *
 * @package     Kutak
 * @since       1.0
 * @author      apalodi
 */

get_header(); ?>

    <div class="site-heading">
        <div class="container">

            <div class="post-cats"><div class="post-cat post-tag"><?php esc_html_e( 'Search results for', 'kutak' ); ?></div></div>

            <div class="term-heading align-justify">
                <div class="term-header">
                    <h1><?php echo esc_html( get_search_query() ); ?></h1>
                </div>
                <div class="term-count"><span><?php echo esc_html( apalodi_get_found_posts() ); ?></span><?php echo esc_html( sprintf( _n( 'Result', 'Results', apalodi_get_found_posts(), 'kutak' ) ) ); ?></div>
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
