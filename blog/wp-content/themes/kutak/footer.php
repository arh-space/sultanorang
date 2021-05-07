<?php
/**
 * The template for displaying the footer.
 *
 * @package     Kutak
 * @since       1.0
 * @author      apalodi
 */
?>
    
        <?php do_action( 'apalodi_after_content' ); ?>

    </main><!-- #main -->

    <footer id="colophon" class="site-footer">

        <div class="site-footer-container">

            <?php if ( has_apalodi_social_icons() ) : ?>
                <div class="footer-social">
                    <?php apalodi_social_icons(); ?>
                </div>
            <?php endif; ?>

            <div class="footer-content">
                
                <?php wp_nav_menu( array( 
                    'theme_location' => 'footer', 
                    'container' => 'nav', 
                    'container_class' => 'footer-menu', 
                    'items_wrap' => '<ul>%3$s</ul>',
                    'depth' => '1',
                    'cache_results' => true,
                    'fallback_cb' => false,
                )); ?>
                
                <?php apalodi_get_template( 'copyright' ); ?>

            </div>

        </div>
    </footer><!-- #colophon -->

</div><!-- #page -->

<?php do_action( 'apalodi_after_page' ); ?>

<?php wp_footer(); ?>

</body>
</html>
