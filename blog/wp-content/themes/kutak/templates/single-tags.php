<?php
/**
 * Single Post Tags.
 *
 * @package     Kutak/Templates
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

$tags_list = get_the_tag_list();

if ( $tags_list ) {
    $title = '<h6 class="tags-title meta-title">'. esc_html__( 'Tags', 'kutak' ) .'</h6>';
    printf( '<div class="tagcloud single-tags meta-container">%s %s</div>', $title, $tags_list );
} 
