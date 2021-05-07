<?php
/**
 * Option Functions.
 *
 * @package     Kutak/Functions
 * @since       2.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Get the value of a transient. 
 *
 * A better way of handling transients that executes fewer SQL queries.
 *
 * @since   2.0.0
 * @access  public
 * @param   string  $transient      Transient name.
 * @param   bool    $use_old_value  If the transient is expired get old value if available instead of false.
 *                                  E.g. if some external API is down use old data.
 *                                  IMPORTANT: doesn't work when external object cache is being used.
 *                                  You will need to check if wp_using_ext_object_cache() and then get old data.
 * @return  mixed   $value          Value of transient or false.
 */
function apalodi_get_transient( $transient, $use_old_value = false ) {

    $transient = 'apalodi_' . $transient;
    $value = false;

    /**
     * Filters the value of an existing transient.
     *
     * The dynamic portion of the hook name, `$transient`, refers to the transient name.
     *
     * Passing a truthy value to the filter will effectively short-circuit retrieval
     * of the transient, returning the passed value instead.
     *
     * @since   2.0.0
     * @param   mixed   $pre_transient  The default value to return if the transient does not exist.
     *                                  Any value other than false will short-circuit the retrieval
     *                                  of the transient, and return the returned value.
     * @param   string  $transient      Transient name.
     */
    $pre = apply_filters( "pre_transient_{$transient}", false, $transient );
    
    if ( false !== $pre ) {
        return $pre;
    }

    if ( wp_using_ext_object_cache() ) {

        $value = wp_cache_get( $transient, 'transient' );

    } else {

        $transient_option = '_transient_' . $transient;
        $transient_value = get_option( $transient_option );

        if ( isset( $transient_value['timeout'] ) ) {

            $timeout = $transient_value['timeout'];

            if ( $timeout < time() ) {

                if ( $use_old_value ) {
                    $value = $transient_value['value'];
                }

            } else {
                $value = $transient_value['value'];
            }

        }
    }

    /**
     * Filters an existing transient's value.
     *
     * The dynamic portion of the hook name, `$transient`, refers to the transient name.
     *
     * @since   2.0.0     
     * @param   mixed   $value      Value of transient.
     * @param   string  $transient  Transient name.
     */
    return apply_filters( "transient_{$transient}", $value, $transient );
}

/**
 * Set/update the value of a transient. 
 * 
 * A better way of handling transients that executes fewer SQL queries.
 *
 * @since   2.0.0
 * @access  public
 * @param   string  $transient      Transient name. Must be 172 characters or fewer in length.
 * @param   mixed   $value          Transient value.
 * @param   int     $expiration     Optional. Time until expiration in seconds. Default 0 (no expiration).
 * @return  bool    $result         False if value was not set and true if value was set.
 */
function apalodi_set_transient( $transient, $value, $expiration = 0 ) {
    
    $transient = 'apalodi_' . $transient;
    $expiration = (int) $expiration;

    /**
     * Filters a specific transient before its value is set.
     *
     * The dynamic portion of the hook name, `$transient`, refers to the transient name.
     *
     * @since   2.0.0
     * @param   mixed   $value          New value of transient.
     * @param   int     $expiration     Time until expiration in seconds.
     * @param   string  $transient      Transient name.
     */
    $value = apply_filters( "pre_set_transient_{$transient}", $value, $expiration, $transient );

    /**
     * Filters the expiration for a transient before its value is set.
     *
     * The dynamic portion of the hook name, `$transient`, refers to the transient name.
     *
     * @since   2.0.0
     * @param   int     $expiration     Time until expiration in seconds. Use 0 for no expiration.
     * @param   mixed   $value          New value of transient.
     * @param   string  $transient      Transient name.
     */
    $expiration = apply_filters( "expiration_of_transient_{$transient}", $expiration, $value, $transient );

    if ( wp_using_ext_object_cache() ) {

        $result = wp_cache_set( $transient, $value, 'transient', $expiration );

    } else {

        $transient_option = '_transient_' . $transient;
        $transient_value = array(
            'timeout' => time() + $expiration,
            'value' => $value
        );

        $result = update_option( $transient_option, $transient_value );
    }

    if ( $result ) {

        /**
         * Fires after the value for a specific transient has been set.
         *
         * The dynamic portion of the hook name, `$transient`, refers to the transient name.
         *
         * @since   2.0.0
         * @param   mixed   $value          Transient value.
         * @param   int     $expiration     Time until expiration in seconds.
         * @param   string  $transient      The name of the transient.
         */
        do_action( "set_transient_{$transient}", $value, $expiration, $transient );

        /**
         * Fires after the value for a transient has been set.
         *
         * @since   2.0.0
         * @param   string  $transient      The name of the transient.
         * @param   mixed   $value          Transient value.
         * @param   int     $expiration     Time until expiration in seconds.
         */
        do_action( 'setted_transient', $transient, $value, $expiration );
    }

    return $result;
}

/**
 * Delete a transient.
 *
 * A better way of handling transients that executes fewer SQL queries.
 *
 * @since   2.0.0
 * @access  public
 * @param   string  $transient  Transient name.
 * @return  bool    $result     True if successful, false otherwise
 */
function apalodi_delete_transient( $transient ) {

    $transient = 'apalodi_' . $transient;

    /**
     * Fires immediately before a specific transient is deleted.
     *
     * The dynamic portion of the hook name, `$transient`, refers to the transient name.
     *
     * @since   2.0.0
     * @param   string $transient Transient name.
     */
    do_action( "delete_transient_{$transient}", $transient );

    if ( wp_using_ext_object_cache() ) {
        $result = wp_cache_delete( $transient, 'transient' );
    } else {
        $option = '_transient_' . $transient;
        $result = delete_option( $option );
    }

    if ( $result ) {

        /**
         * Fires after a transient is deleted.
         *
         * @since   2.0.0
         * @param   string $transient Deleted transient name.
         */
        do_action( 'deleted_transient', $transient );
    }

    return $result;
}

/**
 * Delete all transients.
 *
 * If param $all is true delete all transients otherwise 
 * delete only transients with key apalodi.
 *
 * @since   2.0.0
 * @access  public
 * @param   bool $all If true delete all transients
 */
function apalodi_delete_transients( $all = false ) {
    global $wpdb;

    if ( $all ) {
        $like = $wpdb->esc_like( '_transient_' ) . '%';
    } else {
        $like = $wpdb->esc_like( '_transient_apalodi_' ) . '%';
    }

    $wpdb->query( 
        $wpdb->prepare(
            "
            DELETE FROM {$wpdb->options}
            WHERE option_name LIKE %s
            ",
            $like
        )
    );
}

/**
 * Get the value of a post meta transient. 
 *
 * On sites that have huge number of posts it's not a good idea to use regular
 * transients to cache post specific things because the wp_options table will get large,
 * or external object cache memory will get large and maybe remove things from it if
 * there is not enough memory to store so many options.
 *
 * @since   2.0.0
 * @access  public
 * @param   int     $post_id        Post ID.
 * @param   string  $transient      Transient name.
 * @param   bool    $use_old_value  If the transient is expired get old value instead of false.
 *                                  E.g. if some external API is down use old data.
 * @return  mixed   $value          Value of transient or false.
 */
function apalodi_get_post_meta_transient( $post_id, $transient, $use_old_value = false ) {

    $transient = 'apalodi_' . $transient;
    $value = false;

    /**
     * Filters the value of an existing post meta transient.
     *
     * The dynamic portion of the hook name, `$transient`, refers to the transient name.
     *
     * Passing a truthy value to the filter will effectively short-circuit retrieval
     * of the transient, returning the passed value instead.
     *
     * @since   2.0.0
     * @param   mixed   $pre_transient  The default value to return if the transient does not exist.
     *                                  Any value other than false will short-circuit the retrieval
     *                                  of the transient, and return the returned value.
     * @param   int     $post_id        Post ID.
     * @param   string  $transient      Transient name.
     */
    $pre = apply_filters( "pre_post_meta_transient_{$transient}", false, $post_id, $transient );

    if ( false !== $pre ) {
        return $pre;
    }

    $transient_option = '_transient_' . $transient;
    $transient_value = get_post_meta( $post_id, $transient_option, true );

    if ( isset( $transient_value['timeout'] ) ) {

        $timeout = $transient_value['timeout'];

        if ( $timeout < time() ) {

            if ( $use_old_value ) {
                $value = $transient_value['value'];
            }

        } else {
            $value = $transient_value['value'];
        }

    }

    /**
     * Filters an existing post meta transient's value.
     *
     * The dynamic portion of the hook name, `$transient`, refers to the transient name.
     *
     * @since   2.0.0     
     * @param   mixed   $value      Value of transient.
     * @param   int     $post_id    Post ID.
     * @param   string  $transient  Transient name.
     */
    return apply_filters( "post_meta_transient_{$transient}", $value, $post_id, $transient );
}

/**
 * Set/update the value of a post meta transient. 
 * 
 * On sites that have huge number of posts it's not a good idea to use regular
 * transients to cache post specific things because the wp_options table will get large,
 * or external object cache memory will get large and maybe remove things from it if
 * there is not enough memory to store so many options.
 *
 * @since   2.0.0
 * @access  public
 * @param   int     $post_id        Post ID.
 * @param   string  $transient      Transient name. Must be 172 characters or fewer in length.
 * @param   mixed   $value          Transient value.
 * @param   int     $expiration     Optional. Time until expiration in seconds. Default 0 (no expiration).
 * @return  bool    $result         False if value was not set and true if value was set.
 */
function apalodi_set_post_meta_transient( $post_id, $transient, $value, $expiration = 0 ) {
    
    $transient = 'apalodi_' . $transient;
    $expiration = (int) $expiration;

    /**
     * Filters a specific post meta transient before its value is set.
     *
     * The dynamic portion of the hook name, `$transient`, refers to the transient name.
     *
     * @since   2.0.0
     * @param   mixed   $value          New value of transient.
     * @param   int     $post_id        Post ID.
     * @param   int     $expiration     Time until expiration in seconds.
     * @param   string  $transient      Transient name.
     */
    $value = apply_filters( "pre_set_post_meta_transient_{$transient}", $value, $post_id, $expiration, $transient );

    /**
     * Filters the expiration for a post meta transient before its value is set.
     *
     * The dynamic portion of the hook name, `$transient`, refers to the transient name.
     *
     * @since   2.0.0
     * @param   int     $expiration     Time until expiration in seconds. Use 0 for no expiration.
     * @param   int     $post_id        Post ID.
     * @param   mixed   $value          New value of transient.
     * @param   string  $transient      Transient name.
     */
    $expiration = apply_filters( "expiration_of_post_meta_transient_{$transient}", $expiration, $post_id, $value, $transient );

    $transient_option = '_transient_' . $transient;
    $transient_value = array(
        'timeout' => time() + $expiration,
        'value' => $value
    );

    $result = update_post_meta( $post_id, $transient_option, $transient_value );

    if ( $result ) {

        /**
         * Fires after the value for a specific post meta transient has been set.
         *
         * The dynamic portion of the hook name, `$transient`, refers to the transient name.
         *
         * @since   2.0.0
         * @param   mixed   $value          Transient value.
         * @param   int     $post_id        Post ID.
         * @param   int     $expiration     Time until expiration in seconds.
         * @param   string  $transient      The name of the transient.
         */
        do_action( "set_post_meta_transient_{$transient}", $value, $post_id, $expiration, $transient );

        /**
         * Fires after the value for a post meta transient has been set.
         *
         * @since   2.0.0
         * @param   string  $transient      The name of the transient.
         * @param   int     $post_id        Post ID.
         * @param   mixed   $value          Transient value.
         * @param   int     $expiration     Time until expiration in seconds.
         */
        do_action( 'setted_post_meta_transient', $transient, $post_id, $value, $expiration );
    }

    return $result;
}

/**
 * Delete a post meta transient.
 *
 * On sites that have huge number of posts it's not a good idea to use regular
 * transients to cache post specific things because the wp_options table will get large,
 * or external object cache memory will get large and maybe remove things from it if
 * there is not enough memory to store so many options.
 *
 * @since   2.0.0
 * @access  public
 * @param   int     $post_id    Post ID.
 * @param   string  $transient  Transient name.
 * @return  bool    $result     True if successful, false otherwise
 */
function apalodi_delete_post_meta_transient( $post_id, $transient ) {

    $transient = 'apalodi_' . $transient;

    /**
     * Fires immediately before a specific post meta transient is deleted.
     *
     * The dynamic portion of the hook name, `$transient`, refers to the transient name.
     *
     * @since   2.0.0
     * @param   string  $transient  Transient name.
     * @param   int     $post_id    Post ID.
     */
    do_action( "delete_post_meta_transient_{$transient}", $transient, $post_id );

    $transient_option = '_transient_' . $transient;
    $result = delete_post_meta( $post_id, $transient_option );

    if ( $result ) {

        /**
         * Fires after a post meta transient is deleted.
         *
         * @since   2.0.0
         * @param   string  $transient  Deleted transient name.
         * @param   int     $post_id    Post ID.
         */
        do_action( 'deleted_post_meta_transient', $transient, $post_id );
    }

    return $result;
}

/**
 * Delete all post meta transients.
 *
 * @since   2.0.0
 * @access  public
 */
function apalodi_delete_post_meta_transients() {
    global $wpdb;

    $like = $wpdb->esc_like( '_transient_apalodi_' ) . '%';

    $wpdb->query( 
        $wpdb->prepare(
            "
            DELETE FROM {$wpdb->postmeta}
            WHERE meta_key LIKE %s
            ",
            $like
        )
    );
}
