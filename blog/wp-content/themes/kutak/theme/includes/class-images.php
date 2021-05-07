<?php
/**
 * Image sizes functionality.
 *
 * @package     Kutak/Classes
 * @since       2.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Apalodi_Images Class
 */
class Apalodi_Images {

    /**
     * Stores image sizes.
     *
     * @var array
     */
    protected static $sizes = array();

    /**
     * Stores image sizes as hash.
     *
     * @var array
     */
    protected static $sizes_hash = array();

    /**
     * Background process to generate image size.
     *
     * @var Apalodi_Regenerate_Images_Request
     */
    protected static $background_process;

    /**
     * Init function
     *
     * @since   2.0.0
     * @access  public
     */
    public static function init() {

        add_filter( 'intermediate_image_sizes_advanced', array( __CLASS__, 'intermediate_image_sizes_advanced' ) );
        add_filter( 'wp_get_attachment_image_attributes', array( __CLASS__, 'get_attachment_image_attributes' ), 10, 3 );

        // Not required when Jetpack Photon is in use.
        if ( self::is_jetpack_photon_active() ) {
            return;
        }

        self::$background_process = new Apalodi_Regenerate_Images_Request();

        add_filter( 'wp_get_attachment_image_src', array( __CLASS__, 'background_process_resize_images' ), 10, 4 );
        add_action( 'wp_footer', array( __CLASS__, 'background_process_dispatch' ), 99 );
        add_action( 'apalodi_ajax_after_load_more_posts', array( __CLASS__, 'background_process_dispatch' ) );

        add_action( 'added_post_meta', array( __CLASS__, 'regenerate_post_thumbnail_sizes' ), 10, 4 );
        add_action( 'updated_post_meta', array( __CLASS__, 'regenerate_post_thumbnail_sizes' ), 10, 4 );
    }

    /**
     * Check if Jetpack module photon is active.
     *
     * @since   2.0.0
     * @access  public
     * @return  bool
     */
    public static function is_jetpack_photon_active() {
        return ( method_exists( 'Jetpack', 'is_module_active' ) && Jetpack::is_module_active( 'photon' ) ) ? true : false;
    }

    /**
     * Retrieve image sizes.
     *
     * @since   2.0.0
     * @access  public
     * @return  array Images sizes.
     */
    public static function get_sizes() {
        return self::$sizes;
    }

    /**
     * Register a new image size.
     *
     * Cropping behavior for the image size is dependent on the value of $crop:
     * 1. If false (default), images will be scaled, not cropped.
     * 2. If an array in the form of array( x_crop_position, y_crop_position ):
     *    - x_crop_position accepts 'left' 'center', or 'right'.
     *    - y_crop_position accepts 'top', 'center', or 'bottom'.
     *    Images will be cropped to the specified dimensions within the defined crop area.
     * 3. If true, images will be cropped to the specified dimensions using center positions.
     *
     * @since   2.0.0
     * @access  public
     * @param   string      $name           Image size identifier.
     * @param   int         $width          Image width in pixels.
     * @param   int         $height         Image height in pixels.
     * @param   bool|array  $crop           Optional. Whether to crop images to specified width and height or resize.
     *                                      An array can specify positioning of the crop area. Default false.
     * @param   array       $responsive     Optional. Whether to add responsive sizes for the image.
     */
    public static function add_size( $name, $width = 0, $height = 0, $crop = false, $responsive = array() ) {

        self::$sizes[ $name ] = array(
            'width'  => absint( $width ),
            'height' => absint( $height ),
            'crop'   => $crop,
        );

        self::$sizes_hash[ $name ] = $name . '-' . $width . 'x' . $height;

        if ( $responsive ) {
            foreach ( $responsive as $key => $rwidth ) {
                
                $rname = $name . '-' . $rwidth;
                $rheight = $rwidth * ( $height / $width );

                self::$sizes[ $rname ] = array(
                    'width' => absint( round( $rwidth, 0 ) ),
                    'height' => absint( round( $rheight, 0 ) ),
                    'crop' => $crop,
                );

                self::$sizes_hash[ $name ] .= '-' . $rwidth;
            }
        }
    }

    /**
     * Add our custom sizes to the default image sizes when DOING_AJAX or REST_REQUEST.
     *
     * Popular plugins that regenerate thumbnails are using ajax 
     * and we want our sizes to be regenerated also.
     *
     * It will also generate sizes for images that don't need them.
     *
     * @since   2.0.0
     * @access  public
     * @param   array $sizes Image sizes.
     * @return  array $sizes Image sizes.
     */
    public static function intermediate_image_sizes_advanced( $sizes ) {

        if ( isset( $_REQUEST['action'] ) && 'upload-attachment' === $_REQUEST['action'] ) {
            return $sizes;
        }

        if ( 
            ( defined( 'DOING_AJAX' ) && DOING_AJAX ) 
            || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) 
            || self::is_jetpack_photon_active()
        ) {
            return wp_parse_args( self::$sizes, $sizes );    
        }

        return $sizes;
    }

    /**
     * Filter the list of attachment image attributes to add object fit classes.
     *
     * @since   2.0.0
     * @access  public
     * @param   array        $attr       Attributes for the image markup.
     * @param   WP_Post      $attachment Image attachment post.
     * @param   string|array $size       Requested size. Image size or array of width and height values
     *                                   (in that order). Default 'thumbnail'.
     * @return  array        $attr       Attributes for the image markup.
     */
    public static function get_attachment_image_attributes( $attr, $attachment, $size ) {

        if ( ! is_array( $size ) && isset( self::$sizes[ $size ] ) ) {

            $sizes = self::$sizes[ $size ];
            $image = wp_get_attachment_image_src( $attachment->ID, $size );

            if ( ! wp_image_matches_ratio( $sizes['width'], $sizes['height'], $image[1], $image[2] ) ) {

                $ratio = round( $sizes['height'] / $sizes['width'] * 100, 2 );
                $img_ratio = round( $image[2] / $image[1] * 100, 2 );

                $attr['class'] .= ' is-object-fit';

                if ( $ratio > $img_ratio ) {
                    $attr['class'] .= ' object-fit-wider';
                } else {
                    $attr['class'] .= ' object-fit-taller';
                }
            }
        }

        return $attr;
    }

    /**
     * Add attachment ID to background process to generate new image sizes
     * if not already there.
     *
     * @since   2.0.0
     * @access  public
     * @param   array        $image Properties of the image.
     * @param   int          $attachment_id Attachment ID.
     * @param   string|array $size Image size.
     * @param   bool         $icon If icon or not.
     * @return  array
     */
    public static function background_process_resize_images( $image, $attachment_id, $size, $icon ) {

        if ( ! is_array( $size ) && isset( self::$sizes[ $size ] ) ) {
            if ( ! self::is_regenerated( $attachment_id ) && $attachment_id ) {
                self::$background_process->push_to_queue( $attachment_id );
            }
        }

        return $image;
    }

    /**
     * Start the background process.
     *
     * @since   2.0.0
     * @access  public
     */
    public static function background_process_dispatch() {
        self::$background_process->save()->first_dispatch();
    }

    /**
     * Determines whether an attachment can have its thumbnails regenerated.
     *
     * Adapted from Regenerate Thumbnails by Alex Mills.
     *
     * @param   int $attachment_id An attachment ID.
     * @return  bool Whether the given attachment can have its thumbnails regenerated.
     */
    protected static function is_regeneratable( $attachment_id ) {

        if ( 'site-icon' === get_post_meta( $attachment_id, '_wp_attachment_context', true ) ) {
            return false;
        }

        if ( wp_attachment_is_image( $attachment_id ) ) {
            return true;
        }

        return false;
    }

    /**
     * Determines whether an attachment thumbnails are regenerated.
     *
     * @param   int $attachment_id An attachment ID.
     * @return  bool Whether the given attachment has its thumbnails regenerated.
     */
    protected static function is_regenerated( $attachment_id ) {

        $sizes = get_post_meta( $attachment_id, '_kutak_image_sizes', true );
        $sizes_hash = implode( '|', self::$sizes_hash );        

        return ( $sizes == $sizes_hash ) ? true : false;
    }

    /**
     * Regenerate image sizes.
     *
     * @since   2.0.0
     * @access  public
     * @param   int $attachment_id Attachment ID.
     * @return  int|bool Attachment ID or false to remove item from queue.
     */
    public static function regenerate_image_sizes( $attachment_id ) {

        $attachment_id = absint( $attachment_id );
        $attachment = get_post( $attachment_id );

        if ( ! $attachment || 'attachment' !== $attachment->post_type || ! self::is_regeneratable( $attachment_id ) ) {
            return false;
        }

        if ( self::is_regenerated( $attachment_id ) ) {
            return false;
        }

        $file = get_attached_file( $attachment_id );

        // Check if the file exists, if not just remove item from queue.
        if ( false === $file || is_wp_error( $file ) || ! file_exists( $file ) ) {
            return false;
        }

        $metadata = wp_get_attachment_metadata( $attachment_id );
        $imagesize = getimagesize( $file );

        $resizes = array();
        $file_info = pathinfo( $file );
        $extension = '.' . $file_info['extension'];
        $img_path = $file_info['dirname'] . '/' . $file_info['filename']; // the image path without the extension

        foreach ( self::$sizes as $name => $size ) {

            $dimensions = image_resize_dimensions( $imagesize[0], $imagesize[1], $size['width'], $size['height'], $size['crop'] );
            $new_width = $dimensions[4];
            $new_height = $dimensions[5];

            $resized_img_path = $img_path . '-' . $new_width . 'x' . $new_height . $extension;

            if ( ! file_exists( $resized_img_path ) || ! isset( $metadata['sizes'][ $name ] ) ) {
                $resizes[ $name ] = $size;
            }
        }

        $resized_sizes = array();

        if ( $resizes ) {

            $editor = wp_get_image_editor( $file );

            if ( is_wp_error( $editor ) ) {
                return false;
            }

            $resized_sizes = $editor->multi_resize( $resizes );
        }

        $metadata['sizes'] = wp_parse_args( $resized_sizes, $metadata['sizes'] );

        // Update the metadata with the new size values.
        wp_update_attachment_metadata( $attachment_id, $metadata );

        $sizes_hash = implode( '|', self::$sizes_hash );
        $sizes = update_post_meta( $attachment_id, '_kutak_image_sizes', $sizes_hash );

        // error_log( 'generated ID: ' .$attachment_id );

        return false;
    }

    /**
     * Regenerate thumbnail sizes when the post featured image is saved or updated.
     *
     * @since   2.0.0
     * @access  public
     * @param   int    $meta_id    ID of updated metadata entry.
     * @param   int    $object_id  Object ID.
     * @param   string $meta_key   Meta key.
     * @param   mixed  $meta_value Meta value.
     */
    public static function regenerate_post_thumbnail_sizes( $meta_id, $post_id, $meta_key, $meta_value ) {
        if ( $meta_key === '_thumbnail_id' ) {
            // self::regenerate_image_sizes( $meta_value );
            if ( ! self::is_regenerated( $meta_value ) && $meta_value ) {
                self::$background_process->push_to_queue( $meta_value );
                self::background_process_dispatch();
            }
        }
    }
}

add_action( 'init', array( 'Apalodi_Images', 'init' ) );
