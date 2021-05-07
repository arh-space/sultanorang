<?php
/**
 * Main Pagination.
 *
 * @package     Kutak/Templates
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( $args['max_num_pages'] > 1 && '' !== $args['type'] ) : ?>

    <nav class="navigation paging-navigation pagination-type-<?php echo esc_attr( $args['type'] ); ?>">
    
    <?php if ( $args['is_paged'] || 'numbers' === $args['type'] ) :

        echo paginate_links( apply_filters( 'apalodi_pagination_numbers_args', array(
            'current'      => apalodi_get_paged(),
            'total'        => $args['max_num_pages'],
            'prev_text'    => '<i class="icon-left-medium" aria-label="'. esc_attr__( 'Go to the previous page', 'kutak' ) .'"></i>',
            'next_text'    => '<i class="icon-right-medium" aria-label="'. esc_attr__( 'Go to the next page', 'kutak' ) .'"></i>',
            'type'         => 'list',
            'end_size'     => 1,
            'mid_size'     => 2
        ), $args['max_num_pages'] ) );

    else :

        if ( 'infinite-scroll' === $args['type'] ) :
            
            echo '<div class="pagination-infinite-scroll has-loader" '. apalodi_array_keys_to_data_attr( $args['load_more_args'] ) .'"><span class="loader"></span><span class="loader-error-text" aria-hidden="true">'. esc_html_x( 'Something went wrong with loading more posts', 'pagination ajax error', 'kutak' ) .'</span></div>';
            
        else :

            echo '<button class="pagination-load-more has-loader has-loader-text" ' . apalodi_array_keys_to_data_attr( $args['load_more_args'] ) . '><span class="loader"></span><span class="loader-text">' . esc_html__( 'Load more', 'kutak' ) . '</span><span class="loader-error-text" aria-hidden="true">'. esc_html_x( 'Something went wrong with loading more posts', 'pagination ajax error', 'kutak' ) .'</span></button>';

        endif;

    endif; ?> 
    </nav><!-- .navigation.paging-navigation -->
<?php endif;
