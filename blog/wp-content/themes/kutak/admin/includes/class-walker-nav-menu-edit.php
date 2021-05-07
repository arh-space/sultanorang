<?php
/**
 * Custom Walker Class.
 *
 * Create HTML list of nav menu input items. 
 * This is a copy of Walker_Nav_Menu_Edit class in core.
 *
 * @package     Kutak/Admin/Classes
 * @since       1.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Apalodi_Walker_Nav_Menu_Edit' ) ) :

class Apalodi_Walker_Nav_Menu_Edit extends Walker_Nav_Menu {
    /**
     * Starts the list before the elements are added.
     *
     * @see Walker_Nav_Menu::start_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   Not used.
     */
    public function start_lvl( &$output, $depth = 0, $args = array() ) {}

    /**
     * Ends the list of after the elements are added.
     *
     * @see Walker_Nav_Menu::end_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   Not used.
     */
    public function end_lvl( &$output, $depth = 0, $args = array() ) {}

    /**
     * Start the element output.
     *
     * @see Walker_Nav_Menu::start_el()
     * @since 3.0.0
     *
     * @global int $_wp_nav_menu_max_depth
     *
     * @param string $output Used to append additional content (passed by reference).
     * @param object $item   Menu item data object.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   Not used.
     * @param int    $id     Not used.
     */
    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        global $_wp_nav_menu_max_depth;
        $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

        ob_start();
        $item_id = esc_attr( $item->ID );
        $removed_args = array(
            'action',
            'customlink-tab',
            'edit-menu-item',
            'menu-item',
            'page-tab',
            '_wpnonce',
        );

        $original_title = false;
        if ( 'taxonomy' == $item->type ) {
            $original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
            if ( is_wp_error( $original_title ) )
                $original_title = false;
        } elseif ( 'post_type' == $item->type ) {
            $original_object = get_post( $item->object_id );
            $original_title = get_the_title( $original_object->ID );
        } elseif ( 'post_type_archive' == $item->type ) {
            $original_object = get_post_type_object( $item->object );
            if ( $original_object ) {
                $original_title = $original_object->labels->archives;
            }
        }

        $classes = array(
            'menu-item menu-item-depth-' . $depth,
            'menu-item-' . esc_attr( $item->object ),
            'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
        );

        $title = $item->title;

        if ( ! empty( $item->_invalid ) ) {
            $classes[] = 'menu-item-invalid';
            /* translators: %s: title of menu item which is invalid */
            $title = sprintf( __( '%s (Invalid)', 'kutak' ), $item->title );
        } elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
            $classes[] = 'pending';
            /* translators: %s: title of menu item in draft status */
            $title = sprintf( __('%s (Pending)', 'kutak'), $item->title );
        }

        $title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;

        $submenu_text = '';
        if ( 0 == $depth )
            $submenu_text = 'style="display: none;"';

        ?>
        <li id="menu-item-<?php echo esc_attr( $item->ID ); ?>" class="<?php echo implode(' ', $classes ); ?>">
            <div class="menu-item-bar">
                <div class="menu-item-handle">
                    <span class="item-title"><span class="menu-item-title"><?php echo esc_html( $title ); ?></span> <span class="is-submenu" <?php echo esc_html( $submenu_text ); ?>><?php esc_html_e( 'sub item', 'kutak' ); ?></span></span>
                    <span class="item-controls">
                        <span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
                        <span class="item-order hide-if-js">
                            <a href="<?php
                                echo wp_nonce_url(
                                    add_query_arg(
                                        array(
                                            'action' => 'move-up-menu-item',
                                            'menu-item' => $item_id,
                                        ),
                                        remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) )
                                    ),
                                    'move-menu_item'
                                );
                            ?>" class="item-move-up" aria-label="<?php esc_attr_e( 'Move up', 'kutak' ) ?>">&#8593;</a>
                            |
                            <a href="<?php
                                echo wp_nonce_url(
                                    add_query_arg(
                                        array(
                                            'action' => 'move-down-menu-item',
                                            'menu-item' => $item_id,
                                        ),
                                        remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
                                    ),
                                    'move-menu_item'
                                );
                            ?>" class="item-move-down" aria-label="<?php esc_attr_e( 'Move down', 'kutak' ); ?>">&#8595;</a>
                        </span>
                        <a class="item-edit" id="edit-<?php echo esc_attr( $item->ID ); ?>" href="<?php
                            echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
                        ?>" aria-label="<?php esc_attr_e( 'Edit Menu Item', 'kutak' ); ?>"><span class="screen-reader-text"><?php esc_html_e( 'Edit Menu Item', 'kutak' ); ?></span></a>
                    </span>
                </div>
            </div>

            <div class="menu-item-settings wp-clearfix" id="menu-item-settings-<?php echo esc_attr( $item->ID ); ?>">
                <?php if ( 'custom' == $item->type ) : ?>
                    <p class="field-url description description-wide">
                        <label for="edit-menu-item-url-<?php echo esc_attr( $item->ID ); ?>">
                            <?php esc_html_e( 'URL', 'kutak' ); ?><br />
                            <input type="text" id="edit-menu-item-url-<?php echo esc_attr( $item->ID ); ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo esc_attr( $item->ID ); ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
                        </label>
                    </p>
                <?php endif; ?>
                <p class="description description-wide">
                    <label for="edit-menu-item-title-<?php echo esc_attr( $item->ID ); ?>">
                        <?php esc_html_e( 'Navigation Label', 'kutak' ); ?><br />
                        <input type="text" id="edit-menu-item-title-<?php echo esc_attr( $item->ID ); ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo esc_attr( $item->ID ); ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
                    </label>
                </p>
                <p class="field-title-attribute field-attr-title description description-wide">
                    <label for="edit-menu-item-attr-title-<?php echo esc_attr( $item->ID ); ?>">
                        <?php esc_html_e( 'Title Attribute', 'kutak' ); ?><br />
                        <input type="text" id="edit-menu-item-attr-title-<?php echo esc_attr( $item->ID ); ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo esc_attr( $item->ID ); ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
                    </label>
                </p>
                <p class="field-link-target description">
                    <label for="edit-menu-item-target-<?php echo esc_attr( $item->ID ); ?>">
                        <input type="checkbox" id="edit-menu-item-target-<?php echo esc_attr( $item->ID ); ?>" value="_blank" name="menu-item-target[<?php echo esc_attr( $item->ID ); ?>]"<?php checked( $item->target, '_blank' ); ?> />
                        <?php esc_html_e( 'Open link in a new window/tab', 'kutak' ); ?>
                    </label>
                </p>
                <p class="field-css-classes description description-thin">
                    <label for="edit-menu-item-classes-<?php echo esc_attr( $item->ID ); ?>">
                        <?php esc_html_e( 'CSS Classes (optional)', 'kutak' ); ?><br />
                        <input type="text" id="edit-menu-item-classes-<?php echo esc_attr( $item->ID ); ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo esc_attr( $item->ID ); ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
                    </label>
                </p>
                <p class="field-xfn description description-thin">
                    <label for="edit-menu-item-xfn-<?php echo esc_attr( $item->ID ); ?>">
                        <?php esc_html_e( 'Link Relationship (XFN)', 'kutak' ); ?><br />
                        <input type="text" id="edit-menu-item-xfn-<?php echo esc_attr( $item->ID ); ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo esc_attr( $item->ID ); ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
                    </label>
                </p>
                <p class="field-description description description-wide">
                    <label for="edit-menu-item-description-<?php echo esc_attr( $item->ID ); ?>">
                        <?php esc_html_e( 'Description', 'kutak' ); ?><br />
                        <textarea id="edit-menu-item-description-<?php echo esc_attr( $item->ID ); ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo esc_attr( $item->ID ); ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
                        <span class="description"><?php esc_html_e('The description will be displayed in the menu if the current theme supports it.', 'kutak'); ?></span>
                    </label>
                </p>
                <div class="field-bg-image description description-wide">
                    <?php esc_html_e( 'Background Image', 'kutak' ); ?><br />
                    <label for="edit-menu-item-bg-image-<?php echo esc_attr( $item->ID ); ?>">
                        <input type="hidden" id="edit-menu-item-bg-image-<?php echo esc_attr( $item->ID ); ?>" class="widefat code edit-menu-item-bg-image" name="menu-item-bg-image[<?php echo esc_attr( $item->ID ); ?>]" value="<?php echo esc_attr( $item->bg_image ); ?>" />
                    </label>
                    <div class="field-bg-image-placeholder">
                        <?php if ( '' != $item->bg_image ) {
                            $image_src = wp_get_attachment_image_src( $item->bg_image ); ?>
                            <img src="<?php echo esc_url( $image_src[0] ); ?>">
                        <?php } ?>
                        <span class="field-bg-image-remove">x</span>
                    </div>
                    <button class="button btn-secondary btn-add-menu-item-bg-image"><?php esc_html_e( 'Add Image', 'kutak' ); ?></button>
                </div>

                <fieldset class="field-move hide-if-no-js description description-wide">
                    <span class="field-move-visual-label" aria-hidden="true"><?php esc_html_e( 'Move', 'kutak' ); ?></span>
                    <button type="button" class="button-link menus-move menus-move-up" data-dir="up"><?php esc_html_e( 'Up one', 'kutak' ); ?></button>
                    <button type="button" class="button-link menus-move menus-move-down" data-dir="down"><?php esc_html_e( 'Down one', 'kutak' ); ?></button>
                    <button type="button" class="button-link menus-move menus-move-left" data-dir="left"></button>
                    <button type="button" class="button-link menus-move menus-move-right" data-dir="right"></button>
                    <button type="button" class="button-link menus-move menus-move-top" data-dir="top"><?php esc_html_e( 'To the top', 'kutak' ); ?></button>
                </fieldset>

                <div class="menu-item-actions description-wide submitbox">
                    <?php if ( 'custom' != $item->type && $original_title !== false ) : ?>
                        <p class="link-to-original">
                            <?php printf( esc_html__( 'Original: %s', 'kutak' ), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
                        </p>
                    <?php endif; ?>
                    <a class="item-delete submitdelete deletion" id="delete-<?php echo esc_attr( $item->ID ); ?>" href="<?php
                    echo wp_nonce_url(
                        add_query_arg(
                            array(
                                'action' => 'delete-menu-item',
                                'menu-item' => $item_id,
                            ),
                            admin_url( 'nav-menus.php' )
                        ),
                        'delete-menu_item_' . $item_id
                    ); ?>"><?php esc_html_e( 'Remove', 'kutak' ); ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo esc_attr( $item->ID ); ?>" href="<?php echo esc_url( add_query_arg( array( 'edit-menu-item' => $item_id, 'cancel' => time() ), admin_url( 'nav-menus.php' ) ) );
                        ?>#menu-item-settings-<?php echo esc_attr( $item->ID ); ?>"><?php esc_html_e( 'Cancel', 'kutak' ); ?></a>
                </div>

                <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_attr( $item->ID ); ?>]" value="<?php echo esc_attr( $item->ID ); ?>" />
                <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo esc_attr( $item->ID ); ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
                <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo esc_attr( $item->ID ); ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
                <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo esc_attr( $item->ID ); ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
                <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo esc_attr( $item->ID ); ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
                <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo esc_attr( $item->ID ); ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
            </div><!-- .menu-item-settings-->
            <ul class="menu-item-transport"></ul>
        <?php
        $output .= ob_get_clean();
    }

} // Walker_Nav_Menu_Edit

endif;
