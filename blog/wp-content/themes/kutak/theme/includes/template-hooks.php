<?php
/**
 * Template Hooks.
 *
 * @package     Kutak/Hooks
 * @since       2.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* After Page */
add_action( 'wp_footer', 'apalodi_photoswipe_template' );
