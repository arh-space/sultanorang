<?php
/**
 * The template for displaying an attachment.
 *
 * @package     Kutak
 * @since       1.0
 * @author      apalodi
 */

if ( have_posts() ) :

    while ( have_posts() ) : the_post();

        echo wp_get_attachment_image( get_the_ID(), 'full' );

    endwhile;

endif;
