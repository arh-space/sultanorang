<?php
/**
 * Template part for displaying page content in page.php
 *
 * @package     Kutak/Templates
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-content">
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
    </div><!-- .entry-content -->
</article><!-- #post-## -->
