<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package     Kutak
 * @since       1.0
 * @author      apalodi
 */

get_header(); ?>
    
    <div id="content" class="site-content container">
        <div id="primary" class="content-area">

            <h1><?php esc_html_e( 'Page not found', 'kutak' ); ?></h1>
            <span class="error404-sign"><?php esc_html_e( '404', 'kutak' ); ?></span>

        </div><!-- #primary -->
    </div><!-- #content -->

<?php get_footer();
