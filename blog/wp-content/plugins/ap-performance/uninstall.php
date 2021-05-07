<?php
/**
 * AP Performance Uninstall.
 *
 * Uninstalling AP Performance deletes options.
 *
 * @package     AP_Performance/Uninstaller
 * @version     1.0
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit; // Exit if uninstall not called from WordPress

include_once dirname( __FILE__ ) . '/includes/class-data.php';

AP_Performance_Data::uninstall();
