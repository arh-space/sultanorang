<?php
/**
 * Data related functions and actions.
 *
 * @package     AP_Performance/Classes
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * AP_Performance_Data Class.
 */
class AP_Performance_Data {

    /**
     * Install plugin.
     *
     * @since   1.0.0
     * @access  public
     * @param   bool $network_wide
     */
    public static function install( $network_wide ) {

        if ( is_multisite() && $network_wide ) {
            global $wpdb;

            $current_blog_id = get_current_blog_id();
            $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

            foreach ( $blog_ids as $blog_id ) {
                switch_to_blog( $blog_id );
                self::create_data();
            }

            /** 
             * Switch back to the current blog. Probably preferable, as it is not a hack.
             *
             * @see https://codex.wordpress.org/Function_Reference/restore_current_blog
             */
            switch_to_blog( $current_blog_id );
            $GLOBALS['_wp_switched_stack'] = array();
            $GLOBALS['switched'] = FALSE;

        } else {
            self::create_data();
        }

        // flush_rewrite_rules();
    }

    /**
     * Uninstall plugin.
     *
     * @since   1.0.0
     * @access  public
     */
    public static function uninstall() {

        if ( is_multisite() ) {
            global $wpdb;

            $current_blog_id = get_current_blog_id();
            $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

            foreach ( $blog_ids as $blog_id ) {
                switch_to_blog( $blog_id );
                self::delete_data();
            }

            /** 
             * Switch back to the current blog. Probably preferable, as it is not a hack.
             *
             * @see https://codex.wordpress.org/Function_Reference/restore_current_blog
             */
            switch_to_blog( $current_blog_id );
            $GLOBALS['_wp_switched_stack'] = array();
            $GLOBALS['switched'] = FALSE;

        } else {
            self::delete_data();
        }

        // Clear any cached data that has been removed.
        wp_cache_flush();
    }

    /**
     * Create data.
     *
     * @since   1.0.0
     * @access  private
     */
    private static function create_data() {
        self::create_options();
    }

    /**
     * Delete data.
     *
     * @since   1.0.0
     * @access  private
     */
    private static function delete_data() {
        self::delete_options();
    }

    /**
     * Sets up the default options.
     *
     * @since   1.0.0
     * @access  private
     */
    private static function create_options() {
        add_option( 'ap_performance_defer_scripts', true );
        add_option( 'ap_performance_remove_defer_jquery', false );
        add_option( 'ap_performance_async_scripts_handles', '' );
        add_option( 'ap_performance_disable_defer_scripts_handles', '' );
        add_option( 'ap_performance_disable_jquery_migrate', false );
        add_option( 'ap_performance_disable_emojis', false );
        add_option( 'ap_performance_disable_wp_embed', false );
        add_option( 'ap_performance_disable_scripts_handles', '' );
    }

    /**
     * Delete plugin options.
     *
     * @since   1.0.0
     * @access  private
     */
    private static function delete_options() {
        delete_option( 'ap_performance_defer_scripts' );
        delete_option( 'ap_performance_remove_defer_jquery' );
        delete_option( 'ap_performance_async_scripts_handles' );
        delete_option( 'ap_performance_disable_defer_scripts_handles' );
        delete_option( 'ap_performance_disable_jquery_migrate' );
        delete_option( 'ap_performance_disable_emojis' );
        delete_option( 'ap_performance_disable_wp_embed' );
        delete_option( 'ap_performance_disable_scripts_handles' );
    }
}
