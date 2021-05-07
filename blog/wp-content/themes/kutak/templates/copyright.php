<?php
/**
 * Template for displaying copyright.
 *
 * @package 	Kutak/Templates
 * @since 	    1.0
 * @author 		apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="copyright">
    <?php printf( apalodi_get_translate( 'copyright', __( '&copy; %s Kutak - WordPress Theme by APALODI', 'kutak' ), 'wp_kses' ), date( 'Y' ) ); ?>
</div>
