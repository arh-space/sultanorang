<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @package     Kutak/Templates
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php if ( is_search() ) : ?>
    
    <p class="nothing-found"><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'kutak' ); ?></p>

<?php else: ?>

    <p class="nothing-found"><?php esc_html_e( 'There are no posts.', 'kutak' ); ?></p>

<?php endif;
