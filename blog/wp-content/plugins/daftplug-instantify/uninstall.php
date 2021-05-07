<?php 

if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) exit;

$optionName = 'daftplug_instantify';
$options = array('purchase_code', 'settings', 'subscribed_devices');

if (get_option("{$optionName}_purchase_code")) {
    $params = array(
        'body' => array(
            'action' => 'deactivate',
            'purchase_code' => get_option("{$optionName}_purchase_code")
        ),
        'user-agent' => 'WordPress/'.get_bloginfo('version').'; '.get_bloginfo('url')
    );
        
    wp_remote_post('https://daftplug.com/purchase-verify/', $params);
}

foreach ($options as $option) {
	delete_option("{$optionName}_{$option}");
}