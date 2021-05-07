<?php
/**
 * Manage post views.
 *
 * @package     AP_Popular_Posts/Classes
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * AP_Popular_Posts_Views class.
 */
class AP_Popular_Posts_Views {

    /**
     * Constructor.
     *
     * @since   1.0.0
     * @access  public
     */
    public function __construct() {
        add_action( 'wp_footer', array( $this, 'wp_footer' ), 99 );
        add_action( 'after_delete_post', array( $this, 'delete_views_on_post_delete' ) );
        add_action( 'trashed_post', array( $this, 'delete_views_on_post_delete' ) );
    }

    /**
     * Hook into wp_footer to trigger post view on single post
     * if ajax update views is disabled.
     *
     * @since   1.0.0
     * @access  public
     */
    public function wp_footer() {
        if ( is_singular( 'post' ) && ! is_ap_popular_posts_ajax_update_views() && ! is_customize_preview() ) {
            $this->trigger_post_view( get_the_ID() );
        }
    }

    /**
     * Get all available intervals.
     *
     * @since   1.0.0
     * @access  public
     */
    public function get_intervals() {

        /**
         * Filters the view intervals adding option to add new interval.
         *
         * @since   1.0.0
         */
        $intervals = apply_filters( 'ap_popular_posts_views_intervals', array(
            '1' => __( 'Last 24 hours', 'ap-popular-posts' ),
            '3' => __( 'Last 3 days', 'ap-popular-posts' ),
            '7' => __( 'Last 7 days', 'ap-popular-posts' )
        ) );

        return $intervals;
    }

    /**
     * When the post is deleted or trashed delete it from views.
     *
     * @since   1.0.0
     * @access  public
     * @param   int $post_id Post ID.
     */
    public function delete_views_on_post_delete( $post_id ) {
        global $wpdb;

        $wpdb->query( 
            $wpdb->prepare(
                "
                DELETE FROM {$wpdb->prefix}ap_popular_posts 
                WHERE view_time < %d
                AND post_id = %d
                LIMIT 1
                ",
                time(),
                $post_id
            )
        );
    }

    /**
     * Return true if bot is detected. (Not implemented yet)
     *
     * @since   1.0.0
     * @access  public
     * @return  bool
     */
    private function is_bot() {

        if (preg_match('/bot|crawl|curl|dataprovider|search|get|spider|find|java|majesticsEO|google|yahoo|teoma|contaxe|yandex|libwww-perl|facebookexternalhit/i', $_SERVER['HTTP_USER_AGENT'])) {
            // is bot
        }

        if (preg_match('/apple|baidu|bingbot|facebookexternalhit|googlebot|-google|ia_archiver|msnbot|naverbot|pingdom|seznambot|slurp|teoma|twitter|yandex|yeti/i', $_SERVER['HTTP_USER_AGENT'])) {
            // allowed bot
        }

        if ( preg_match('/bot|crawl|slurp|spider|mediapartners/i', $_SERVER['HTTP_USER_AGENT'] ) ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Return true if it everything is ok to trigger post view.
     *
     * Theme developers can use filter ap_popular_posts_trigger_view 
     * to disable constant post views updates on theme demo sites.
     *
     * @since   1.0.0
     * @access  private
     * @param   int     $post_id
     * @return  bool    True on success
     */
    private function can_trigger_post_view( $post_id ) {

        if ( 
            $post_id 
            && 'post' == get_post_type( $post_id ) 
            && apply_filters( 'ap_popular_posts_trigger_view', true, $post_id ) 
        ) {
            return true;
        }

        return false;
    }

    /**
     * Trigger post view.
     *
     * @since   1.0.0
     * @access  public
     * @param   int     $post_id
     * @return  bool    True on success
     */
    public function trigger_post_view( $post_id ) {
        global $wpdb;

        $post_id = absint( $post_id );

        if ( $this->can_trigger_post_view( $post_id ) ) {

            $time = time();
            $use_object_cache = get_option( 'ap_popular_posts_use_object_cache' );
            $sampling_rate = (int) get_option( 'ap_popular_posts_data_sampling_rate' );

            if ( wp_using_ext_object_cache() && $use_object_cache == '1' ) {

                $view = sprintf( '%d,%d', $post_id, $time );

                if ( $sampling_rate > 0 ) {

                    if ( mt_rand( 0, $sampling_rate ) === 0 ) {

                        $sviews = array();

                        for ( $i = 0; $i < $sampling_rate; $i++ ) { 
                            $sviews[] = $view;
                        }

                        $sviews = implode( '|', $sviews );

                        if ( false === ( $views = wp_cache_get( 'views', 'ap_popular_posts' ) ) ) {
                            $views = $sviews;
                        } else {
                            $views = $views . '|' . $sviews;
                        }

                        if ( wp_cache_set( 'views', $views, 'ap_popular_posts' ) ) {
                            $this->transfer_object_cache_views( $views );
                            return true;
                        }
                    }

                } else {

                    if ( false === ( $views = wp_cache_get( 'views', 'ap_popular_posts' ) ) ) {
                        $views = $view;
                    } else {
                        $views = $views . '|' . $view;
                    }

                    if ( wp_cache_set( 'views', $views, 'ap_popular_posts' ) ) {
                        $this->transfer_object_cache_views( $views );
                        return true;
                    }
                }

            } else {

                if ( $sampling_rate > 0 ) {

                    if ( mt_rand( 0, $sampling_rate ) === 0 ) {

                        $prepared_values = array();

                        for ( $i = 0; $i < $sampling_rate; $i++ ) { 
                            $prepared_values[] = $wpdb->prepare( '(%d,%d)', $post_id, $time );
                        }

                        $prepared_values = implode( ',', $prepared_values );

                        if ( $wpdb->query( "INSERT INTO {$wpdb->prefix}ap_popular_posts_cache (post_id, view_time) VALUES $prepared_values" ) ) {
                            $cache_count = $wpdb->insert_id + $sampling_rate - 1;
                            $this->transfer_cache_views( $cache_count );
                            return true;
                        }
                    }

                } else {

                    if ( $wpdb->insert( 
                        "{$wpdb->prefix}ap_popular_posts_cache", 
                        array(
                            'post_id' => $post_id,
                            'view_time' => $time,
                        ),
                        array( 
                            '%d',
                            '%d'
                        )
                    ) ) {
                        $this->transfer_cache_views( $wpdb->insert_id );
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Delete 7100 views older than 7 days.
     *
     * If the cache is bigger than 4600 rows or 20 min is expired.
     *
     * It's limited to 7100 rows because it's better to delete fewer rows at time 
     * than several tens of thousands that a high traffic site can get.
     *
     * @since   1.0.0
     * @access  private
     * @param   int $cache_count Number of rows in cache
     */
    private function delete_old_views( $cache_count ) {
        global $wpdb;

        if ( $cache_count > 4600 || false === ap_popular_posts_get_transient( 'delete_old_views' ) ) {

            $intervals = $this->get_intervals();
            $max_interval = max( array_keys( $intervals ) );

            /**
             * Filters the number of days that are considered old views.
             *
             * By default the largest interval time is used.
             *
             * @since   1.0.0.
             */
            $days = apply_filters( 'ap_popular_posts_old_views_days', $max_interval );

            /**
             * Filters the SQL query limit for deleting old views.
             *
             * @since   1.0.0.
             */
            $limit = apply_filters( 'ap_popular_posts_delete_old_views_limit', 7100 );

            $wpdb->query( 
                $wpdb->prepare(
                    "
                    DELETE FROM {$wpdb->prefix}ap_popular_posts 
                    WHERE view_time < %d
                    LIMIT %d
                    ",
                    strtotime( sprintf( '-%d day', absint( $days ) ) ),
                    $limit
                )
            );

            /**
             * Filters the expiration time for a delete_old_views transient.
             *
             * @since   1.0.0.
             */
            $expiration = apply_filters( 'ap_popular_posts_transient_delete_old_views_expiration', 20 * MINUTE_IN_SECONDS );
            ap_popular_posts_set_transient( 'delete_old_views', 'deleted', $expiration );
        }
    }

    /**
     * Transfer post views from object cache to main table.
     *
     * If the cache is bigger than 4700 rows or 20 min is expired.
     *
     * @since   1.0.0
     * @access  private
     * @param   string $views Views
     */
    private function transfer_object_cache_views( $views ) {
        global $wpdb;

        $views = explode( '|', $views );
        $cache_count = count( $views );

        if ( $cache_count > 4700 || false === wp_cache_get( 'transfer_views', 'ap_popular_posts' ) ) {

            $prepared_values = array();

            foreach ( $views as $key => $view ) {
                $view = explode( ',', $view );
                if ( isset( $view[1] ) ) {
                    $prepared_values[] = $wpdb->prepare( '(%d,%d)', $view[0], $view[1] );
                }
            }

            $prepared_values = implode( ',', $prepared_values );

            $wpdb->query( "INSERT INTO {$wpdb->prefix}ap_popular_posts (post_id, view_time) VALUES $prepared_values" );

            wp_cache_delete( 'views', 'ap_popular_posts' );

            /**
             * Filters the expiration time for a transfer_views transient/cache.
             *
             * @since   1.0.0
             */
            $expiration = apply_filters( 'ap_popular_posts_transient_transfer_views_expiration', 20 * MINUTE_IN_SECONDS );
            wp_cache_set( 'transfer_views', 'transfered', 'ap_popular_posts', $expiration );

        } else {
            $this->delete_old_views( $cache_count );
        }
    }

    /**
     * Transfer post views from cache table to main table.
     *
     * If the cache is bigger than 4700 rows or 20 min is expired.
     *
     * @since   1.0.0
     * @access  private
     * @param   int $cache_count Number of rows in cache
     */
    private function transfer_cache_views( $cache_count ) {
        global $wpdb;

        if ( $cache_count > 4700 || false === ap_popular_posts_get_transient( 'transfer_views' ) ) {

            $wpdb->query( "INSERT INTO {$wpdb->prefix}ap_popular_posts (post_id, view_time) SELECT post_id, view_time FROM {$wpdb->prefix}ap_popular_posts_cache" );

            $wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}ap_popular_posts_cache" );

            /**
             * Filters the expiration time for a transfer_views transient/cache.
             *
             * @since   1.0.0.
             */
            $expiration = apply_filters( 'ap_popular_posts_transient_transfer_views_expiration', 20 * MINUTE_IN_SECONDS );
            ap_popular_posts_set_transient( 'transfer_views', 'transfered', $expiration );

        } else {
            $this->delete_old_views( $cache_count );
        }
    }

    /**
     * Recursive get popular posts ids.
     *
     * @since   1.0.0
     * @access  private
     * @param   int     $interval       Interval in days
     * @param   int     $number         Number of posts to get
     * @param   int     $total_count    Number of posts found
     * @param   array   $ids            Selected ids
     * @return  array   $popular_ids    IDs
     */
    private function get_ids_recursive( $interval, $number, $total_count = 0, $ids = array() ) {
        global $wpdb;

        $additional_ids = array();

        $popular_ids = $wpdb->get_col(
            $wpdb->prepare( 
                "
                SELECT      post_id
                FROM        {$wpdb->prefix}ap_popular_posts
                WHERE       view_time >= %d
                GROUP BY    post_id DESC
                ORDER BY    COUNT(post_id) DESC
                LIMIT       %d
                ",
                strtotime( sprintf( '-%d day', $interval ) ),
                $number
            )
        );

        $new_ids = array_diff( $popular_ids, $ids );
        $count = count( $new_ids );
        $total_count = $total_count + $count;
        $interval = $interval * 2;
        $has_rows = false;

        if ( $total_count < 1 ) {
            if ( $wpdb->get_var( "SELECT post_id FROM {$wpdb->prefix}ap_popular_posts LIMIT 1" ) ) {
                $has_rows = true;
            }
        } else {
            $has_rows = true;
        }

        $intervals = $this->get_intervals();
        $max_interval = max( array_keys( $intervals ) );

        /** 
         * If the returned number is lower than what we asked for
         * run the function again with doubled interval
         * but not more than max days interval
         * and if the table has rows.
         */
        if ( $total_count < $number && $interval <= $max_interval && $has_rows ) {
            $additional_ids = $this->get_ids_recursive( $interval, $number, $total_count, $popular_ids );
        }

        $popular_ids = array_merge( $popular_ids, $additional_ids );

        return array_values( array_unique( $popular_ids ) );
    }

    /**
     * Get popular posts ids.
     *
     * @since   1.0.0
     * @access  public
     * @param   int     $interval   Interval in days
     * @param   int     $number     Number of posts
     * @return  array   $ids        IDs
     */
    public function get_ids( $interval, $number ) {

        $number = absint( $number );
        $interval = absint( $interval );
        $intervals = $this->get_intervals();

        if ( ! $number ) {
            $number = 4;
        }

        if ( $number > 12 ) {
            $number = 12;
        }

        if ( ! $interval ) {
            $interval = 3;
        }

        if ( ! in_array( $interval, array_keys( $intervals ), true ) ) {
            $interval = 3;
        }

        /**
         * Transient expiration time correlated with interval. 
         * 
         * Bigger interval bigger transient expiration time beacuse
         * if the interval is last 7 days it isn't really necessary
         * to change results every 25 minutes. It won't make any significant 
         * difference to the list but it will make more SQL queries through the day.
         *
         * Last 24 hours - 25 min
         * Last 3 days   - 75 min - 1h:15m
         * Last 7 days   - 175 min - 2h:55m
        */
        $expiration = $interval * 25 * MINUTE_IN_SECONDS;

        /**
         * Filters the expiration time for ids_{$interval}_{$number} transient.
         *
         * @since   1.0.0
         * @param   int $interval Selected interval
         */
        $expiration = apply_filters( 'ap_popular_posts_transient_ids_expiration', $expiration, $interval );

        if ( false === ( $ids = ap_popular_posts_get_transient( "ids_{$interval}_{$number}" ) ) ) {

            $ids = $this->get_ids_recursive( $interval, $number );
            ap_popular_posts_set_transient( "ids_{$interval}_{$number}", $ids, $expiration );
        }

        return $ids;
    }

    /**
     * Populate with dummy views to test queries.
     *
     * @since   1.0.0
     * @access  public
     * @param   int     $number_of_rows  Number of rows to generate
     * @param   string  $period          In what time period
     * @param   bool    $truncate        If true truncate tables
     */
    public function populate_dummy_views( $number_of_rows, $period = '-10 day', $truncate = false ) {
        global $wpdb;

        if ( $truncate ) {

            $wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}ap_popular_posts" );
            $wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}ap_popular_posts_cache" );

        } else {

            $values = array();
            $ids = get_posts( array(
                'post_type' => 'post',
                'posts_per_page' => 50,
                'post_status' => 'publish',
                'fields' => 'ids',
            ) );

            for ( $i = 0; $i < $number_of_rows; $i++ ) { 

                $rand = array_rand( $ids );
                $id = $ids[$rand];

                $start_time = strtotime( $period );
                $end_time = time();
                $time = mt_rand( $start_time, $end_time );

                $values[] = $wpdb->prepare( '(%d,%d)', $id, $time );
            }

            $values = implode( ',', $values );

            $wpdb->query( "INSERT INTO {$wpdb->prefix}ap_popular_posts_cache (post_id, view_time) VALUES $values" );
            $wpdb->query( "INSERT INTO {$wpdb->prefix}ap_popular_posts (post_id, view_time) SELECT post_id, view_time FROM {$wpdb->prefix}ap_popular_posts_cache" );
            $wpdb->query( "TRUNCATE TABLE {$wpdb->prefix}ap_popular_posts_cache" );
        }
    }

}
