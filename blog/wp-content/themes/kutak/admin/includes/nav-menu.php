<?php
/**
 * Theme Custom Menu Options.
 *
 * @package     Kutak/Admin/Functions
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Define new Walker edit.
 *
 * @since   1.0
 * @access  public
 * @param   string $walker - Class name
 * @param   int $menu_id
 * @return  string $walker
 */
function apalodi_edit_nav_menu_walker( $walker, $menu_id ) {
    return 'Apalodi_Walker_Nav_Menu_Edit';
}
add_filter( 'wp_edit_nav_menu_walker', 'apalodi_edit_nav_menu_walker', 10, 2 );

/**
 * Get all menu items postmeta in a global variable so we can import menu custom fields.
 *
 * @since   1.0
 * @access  public
 * @param   array $post
 * @return  array $post
 */
function apalodi_get_import_custom_nav_fields( $post ) {

    if ( 'nav_menu_item' == $post['post_type'] && isset( $post['postmeta'] ) ) {
        foreach ( $post['postmeta'] as $meta ) {
            if ( '_apalodi_menu_item_bg_image' == $meta['key'] ) {
                $GLOBALS['apalodi_wp_importer_menu_custom_fields'] = $meta['value'];
            }
        }
    }
    return $post;
}
// add_action( 'wp_import_post_data_raw', 'apalodi_get_import_custom_nav_fields' );

/**
 * Save menu custom fields.
 *
 * @since   1.0
 * @access  public
 * @param   int $menu_id
 * @param   int $menu_item_db_id
 * @param   array $args
 */
function apalodi_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {

    if ( isset( $_POST['menu-item-bg-image'][$menu_item_db_id] ) ) {
        $value = $_POST['menu-item-bg-image'][$menu_item_db_id];
        update_post_meta( $menu_item_db_id, '_apalodi_menu_item_bg_image', $value );
    } else {
        delete_post_meta( $menu_item_db_id, '_apalodi_menu_item_bg_image' );
    }

    // for wp importer
    // if ( isset( $GLOBALS['apalodi_wp_importer_menu_custom_fields'] ) ) {
    //     $value = maybe_unserialize( $GLOBALS['apalodi_wp_importer_menu_custom_fields'] );
    //     update_post_meta( $menu_item_db_id, '_apalodi_menu_item_bg_image', $value );
    //     unset( $GLOBALS['apalodi_wp_importer_menu_custom_fields'] );
    // }

}
add_action( 'wp_update_nav_menu_item', 'apalodi_update_custom_nav_fields', 10, 3 );
