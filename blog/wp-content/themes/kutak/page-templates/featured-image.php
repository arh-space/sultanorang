<?php
/**
  * Template Name: Featured Image Page
 *
 * @package     Kutak
 * @since       1.0
 * @author      apalodi
 */

get_header(); ?>

    <div id="content" class="site-content">
        <div id="primary" class="content-area">

            <?php while ( have_posts() ) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                    <div class="article-heading">
                        <div class="article-heading-container flex flex-wrap">

                            <?php if ( has_post_thumbnail() ) : ?>
                            <div class="article-heading-image-wrapper flex">
                                <div class="post-media article-heading-image flex align-middle">
                                    <?php echo apalodi_get_attachment_bg_image( get_post_thumbnail_id(), apalodi_get_image_size( 'single' ), array( 'class' => 'post-single-image preload-bg-image bg-image' ) ); ?> 
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="article-heading-content-wrapper flex align-middle">
                                <div class="article-heading-content last-child-nomargin">
                                    <h1 class="article-title"><?php the_title(); ?></h1>
                                    <?php 
                                    if ( has_excerpt() ) :
                                        the_excerpt(); 
                                    endif;
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="article-content-wrapper">
                        <div class="article-content entry-content">
                            <?php
                            the_content();

                            wp_link_pages(
                                array(
                                    'before' => '<div class="page-links single-meta"><h6 class="page-links-title single-meta-title">' . esc_html__( 'Pages', 'kutak' ) . '</h6><p>',
                                    'after' => '</p></div>',
                                    'link_before' => '<span>',
                                    'link_after' => '</span>',
                                )
                            );
                            ?>
                        </div>

                    </div>
                   
                </article><!-- #post-## -->

                <?php

                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;

                ?>

            <?php endwhile; // End of the loop ?>

        </div><!-- #primary -->
    </div><!-- #content -->

<?php get_footer();
