<?php
/**
 * All functionality to regenerate images in the background.
 *
 * @package     Kutak/Classes
 * @since       2.0
 * @author      apalodi
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class that extends Apalodi_Background_Process to process image regeneration in the background.
 */
class Apalodi_Regenerate_Images_Request extends Apalodi_Background_Process {

    /**
     * Initiate new background process.
     */
    public function __construct() {

        // Uses unique prefix per blog so each blog has separate queue.
        $this->prefix = 'wp_' . get_current_blog_id();
        $this->action = 'kutak_regenerate_images';

        // This is needed to prevent timeouts due to threading. See https://core.trac.wordpress.org/ticket/36534.
        @putenv( 'MAGICK_THREAD_LIMIT=1' ); // @codingStandardsIgnoreLine.

        parent::__construct();
    }

    /**
     * Push to queue
     *
     * @param   int $attachment_id.
     * @return  $this
     */
    public function push_to_queue( $attachment_id ) {
        $this->data[ $attachment_id ] = $attachment_id;
        return $this;
    }

    /**
     * Limit each task ran per batch to 1 for image regeneration.
     *
     * @return  bool
     */
    protected function batch_limit_exceeded() {
        return true;
    }

    /**
     * Code to execute for each item in the queue.
     *
     * @param   int $attachment_id Queue attachment_id to iterate over.
     * @return  int|bool Attachment ID or false
     */
    protected function task( $attachment_id ) {
        return Apalodi_Images::regenerate_image_sizes( $attachment_id );
    }
}
