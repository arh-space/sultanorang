<?php
/**
 * Template for displaying the searchform.
 *
 * @package 	Kutak/Templates
 * @since 	    1.0
 * @author 		apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <input type="search" class="search-field" placeholder="<?php esc_attr_e( 'Search...', 'kutak' )  ?>" value="<?php echo get_search_query() ?>" name="s" />
    <button type="submit" class="search-submit" aria-label="<?php esc_attr_e( 'Search', 'kutak' )  ?>"></button>
</form>
