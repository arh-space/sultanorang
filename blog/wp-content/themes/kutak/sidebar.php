<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package     Kutak
 * @since       1.0
 * @author      apalodi
 */

if ( ! is_active_sidebar( 'sidebar-posts' ) ) { return; } ?>

<div id="secondary" class="main-widget-area widget-area sidebar" role="complementary">
	<?php dynamic_sidebar( 'sidebar-posts' ); ?>
</div><!-- #secondary -->
