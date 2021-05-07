<?php
/**
 * AP Share Buttons Uninstall.
 *
 * Uninstalling AP Share Buttons deletes tables and options.
 *
 * @package     AP_Share_Buttons/Uninstaller
 * @version     1.0
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit; // Exit if uninstall not called from WordPress

include_once dirname( __FILE__ ) . '/includes/class-data.php';

AP_Share_Buttons_Data::uninstall();
