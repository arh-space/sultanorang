jQuery(function() {
    'use strict';
	var daftplugAdmin = jQuery('.daftplugAdmin[data-daftplug-plugin="daftplug_instantify"]');
	var optionName = daftplugAdmin.attr('data-daftplug-plugin');
	var objectName = window[optionName + '_admin_js_vars'];
});