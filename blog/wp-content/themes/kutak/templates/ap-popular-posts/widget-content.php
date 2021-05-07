<?php
/**
 * Template for displaying widget content.
 *
 * @package     Kutak/Templates/
 * @since       2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

$query = $args['query'];

if ( $query->have_posts() ) :
    ?>
    <div class="posts-container">
        <div class="row">
        <?php while ( $query->have_posts() ) : $query->the_post();
            apalodi_get_template( 'content-small' );
        endwhile; ?>
        </div>
    </div>
    <?php 
else :
    ?>
    <p><?php esc_html_e( 'There are currently no popular posts.', 'kutak' ); ?></p>
    <?php
endif;
