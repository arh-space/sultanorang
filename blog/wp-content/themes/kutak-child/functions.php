<?php
/**
 *	Kutak Child Theme Functions.
 *
 * @package 	Kutak_Child
 * @since 	    2.0
 * @author 		apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * If you need to remove hooks from the parent theme
 * you will need to put the code here on wp_loaded hook.
 *
 * It needs to be execute after the actual hook was added.
 *
 * @since   1.0
 * @access  public
 */
function apalodi_child_override_hooks() {
    // your code here
}
add_action( 'wp_loaded', 'apalodi_child_override_hooks' );

/**
 * Register and Enqueue CSS and JS.
 *
 * @since   1.0
 * @access  public
 */
function apalodi_child_enqueue_scripts() {

    /**
     * If you don't have any CSS cutomizations you can uncomment this
     * so you don't load an empty child style.css
     */
    // wp_dequeue_style( 'kutak-style' );
}
add_action( 'wp_enqueue_scripts', 'apalodi_child_enqueue_scripts', 11 );
