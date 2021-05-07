<?php
/**
 * Template part for displaying posts content in index.php
 *
 * @package     Kutak/Templates
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<article <?php post_class( 'post-featured-link column flex' ); ?>>

        <?php if ( has_post_thumbnail() ) : ?>
            <a href="<?php the_permalink(); ?>" class="post-media post-media-cropped post-featured-media flex">
                <div class="image-wrapper has-aspect-ratio has-aspect-ratio-10-9">
                    <?php the_post_thumbnail( 'kutak-blog', array( 'data-type' => 'small' ) ); ?> 
                </div>
            </a>
        <?php endif; ?>

        <div class="post-featured-content flex-grow">
            <div class="post-cats post-featured-cats">
                <div class="post-cat"><?php the_category( ', ' ); ?></div>
            </div>
            <h3 class="post-featured-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <div class="post-footer post-featured-footer">
                <span class="post-read-time"><?php echo esc_html( sprintf( __( '%s min read', 'kutak' ), apalodi_get_reading_time() ) ); ?></span>
                <span class="post-date"><?php echo esc_html( get_the_date() ); ?></span>
            </div>
        </div>

</article><!-- #post-## -->
