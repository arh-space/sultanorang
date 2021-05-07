<?php
/**
 * Template part for displaying posts content in index.php
 *
 * @package     Kutak/Templates
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

$thumbnail_atts = array(
    'data-type' => 'block'
);

if ( false !== strpos( $args['classes'], 'post-featured' ) ) {
    $thumbnail_atts['data-type'] = 'featured';
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $args['classes'] ); ?>>
    <div class="post-inner flex flex-column">

        <?php if ( has_post_thumbnail() ) : ?>
            <a href="<?php the_permalink(); ?>" class="post-media">
                <div class="image-wrapper has-aspect-ratio has-aspect-ratio-16-9">
                    <?php the_post_thumbnail( 'kutak-blog', $thumbnail_atts ); ?> 
                </div>
            </a>
        <?php endif; ?>

        <div class="post-content flex-grow">
            <div class="post-cats">
                <div class="post-cat"><?php the_category( ', ' ); ?></div>
            </div>
            <a href="<?php the_permalink(); ?>">
            <h2 class="post-title"><?php the_title(); ?></h2>
            <?php the_excerpt(); ?>
            </a>
        </div>

        <div class="post-footer">
            <span class="post-read-time"><?php echo esc_html( sprintf( __( '%s min read', 'kutak' ), apalodi_get_reading_time() ) ); ?></span>
            <span class="post-date"><?php echo esc_html( get_the_date() ); ?></span>
        </div>
    </div>

</article><!-- #post-## -->
