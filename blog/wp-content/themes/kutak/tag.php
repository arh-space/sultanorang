<?php
/**
 * The template for displaying tag archive
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
                    <div class="post-cats"><div class="post-cat post-tag"><?php esc_html_e( 'Tag', 'kutak' ); ?></div></div>
                    <h1><?php echo esc_html( single_term_title( '', true ) ); ?></h1>
                    <?php the_archive_description(); ?>

                    <?php
                    $tagmap_page_id = apalodi_get_tagmap_page_ID();
                    if ( $tagmap_page_id ) : ?>
                        <p class="view-all-tags"><?php echo wp_kses( sprintf( __( 'View %sall tags%s', 'kutak' ), '<a href="'. esc_url( get_permalink( $tagmap_page_id ) ). '">', '</a>' ), wp_kses_allowed_html() ); ?></p>
                    <?php endif; ?>
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
