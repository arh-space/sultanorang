<?php
/**
 * Template part for displaying single post content in single.php
 *
 * @package     Kutak/Templates
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="article-heading">
        <div class="article-heading-container flex flex-wrap">

            <?php if ( has_post_thumbnail() ) : ?>
            <div class="article-heading-image-wrapper flex">
                <div class="post-media post-media-cropped article-heading-image flex">
                    <div class="image-wrapper has-aspect-ratio has-aspect-ratio-16-9">
                        <?php the_post_thumbnail( 'kutak-blog-1248', array( 'data-type' => 'single' ) ); ?> 
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="article-heading-content-wrapper flex align-middle">
                <div class="article-heading-content last-child-nomargin">
                    <div class="post-cats article-heading-cats">
                        <div class="post-cat"><?php the_category( ', ' ); ?></div>
                    </div>
                    <h1 class="article-title"><?php the_title(); ?></h1>
                    <?php 
                    if ( has_excerpt() ) :
                        the_excerpt(); 
                    endif;
                    ?>
                    <div class="post-footer article-heading-footer">
                        <span class="post-read-time"><?php echo esc_html( sprintf( __( '%s min read', 'kutak' ), apalodi_get_reading_time() ) ); ?></span>
                        <span class="post-date"><?php echo esc_html( get_the_date() ); ?></span>
                    </div>
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
    
        <?php do_action( 'apalodi_after_single_content' ); ?>

        <?php apalodi_single_tags() ?>

    </div>

    <?php get_sidebar(); ?>
    
    <?php apalodi_related_posts() ?>

    <?php
    // If comments are open or we have at least one comment, load up the comment template.
    if ( comments_open() || get_comments_number() ) : ?>

        <div class="post-comments-section">
            <div class="container">
                <div class="row">

                    <div class="post-comment post-featured column">
                        <?php comments_template(); ?>
                    </div>
        
                    <?php apalodi_get_template( 'featured/tabs' ); ?>

                </div>

            </div>

        </div>

    <?php endif; ?>
   
</article><!-- #post-## -->
