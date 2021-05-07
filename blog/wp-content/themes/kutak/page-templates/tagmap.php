<?php
/**
 * Template Name: Tagmap
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
                    <h1 class="site-title"><?php the_title(); ?></h1>
                </div>
                <div class="term-count"><span><?php echo esc_html( apalodi_count_terms_post_tag() ); ?></span><?php echo esc_html( _n( 'Tag', 'Tags', apalodi_count_terms_post_tag(), 'kutak' ) ); ?></div>
            </div>

        </div>
    </div>

    <div id="content" class="site-content container">
        <div id="primary" class="content-area">
            
            <div class="tagmap">
                <?php 
                $tagmap = apalodi_get_tagmap();

                foreach ( $tagmap as $letter => $tags ) : ?>
                    <div class="tagmap-group">
                        <h6 class="tagmap-title"><?php echo esc_html( $letter ); ?></h6>
                        <?php foreach ( $tags as $key => $tag ) : ?>
                            <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="tagmap-item flex"><?php echo esc_html( $tag->name ); ?><span><?php echo esc_html( $tag->count ); ?></span></a>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>

        </div><!-- #primary -->
    </div><!-- #content -->

<?php get_footer();
