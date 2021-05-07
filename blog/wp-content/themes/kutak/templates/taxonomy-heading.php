<?php
/**
 * Taxonomy heading.
 *
 * @package     Kutak/Templates
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$taxonomy = $args['taxonomy'];
$category = $args['category'];
$tag = $args['tag'];

$is_valid_tax = false;
$show_children = false;
$name = '';
$term_id = 0;

if ( 'category' === $taxonomy ) {
    if ( '' !== $category ) {
        $term = get_term( $category, 'category' );
        if ( $term ) {
            $is_valid_tax = true;
            $name = $term->name;
            $term_id = $term->term_id;
            if ( $args['category_children'] ) {
                $show_children = true;
            }
        }    
    }
} else {
    if ( '' !== $tag ) {
        $term = get_term( $tag, 'post_tag' );
        if ( $term ) {
            $is_valid_tax = true;
            $name = $term->name;
            $term_id = $term->term_id;
        } 
    }
}

if ( $is_valid_tax ) : ?>
    
    <div class="taxonomy-section-heading container flex flex-wrap align-bottom">
        <div class="taxonomy-title">
            <a href="<?php echo esc_url( get_term_link( $term_id ) ); ?>">
                <h3 class="meta-title"><span><?php echo esc_html( $name ); ?></span></h3>
            </a>
        </div>

        <?php if ( $show_children ) : 

            $children = get_terms( array(
                'taxonomy' => 'category',
                'parent' => $term_id,
            ) );

            if ( $children ) : ?>
                <div class="taxonomy-children taxonomy-meta entry-meta flex flex-wrap">
                    <?php foreach ( $children as $key => $term ) : ?>
                        <span><a href="<?php echo esc_url( get_term_link( $term->term_id ) ); ?>"><?php echo esc_html( $term->name ); ?></a></span>
                    <?php endforeach; ?>
                </div>
            <?php endif;
        endif; ?>
    </div>

<?php endif;
