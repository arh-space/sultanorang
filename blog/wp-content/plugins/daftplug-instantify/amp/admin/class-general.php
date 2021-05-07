<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('daftplugInstantifyAmpAdminGeneral')) {
    class daftplugInstantifyAmpAdminGeneral {
    	public $name;
        public $description;
        public $slug;
        public $version;
        public $textDomain;
        public $optionName;

        public $pluginFile;
        public $pluginBasename;
        public $pluginUploadDir;

        public $settings;

    	public function __construct($config) {
    		$this->name = $config['name'];
            $this->description = $config['description'];
            $this->slug = $config['slug'];
            $this->version = $config['version'];
            $this->textDomain = $config['text_domain'];
            $this->optionName = $config['option_name'];

            $this->pluginFile = $config['plugin_file'];
            $this->pluginBasename = $config['plugin_basename'];
            $this->pluginUploadDir = $config['plugin_upload_dir'];

            $this->settings = $config['settings'];

            add_action('admin_init', array($this, 'setGeneralSettings'));
            add_action('admin_menu', array($this, 'removeAmpAdminMenu'));
    	}

		public function setGeneralSettings() {
            AMP_Options_Manager::update_option('experiences', array('website'));
            
			if (daftplugInstantify::getSetting('ampMode') == 'both') {
                AMP_Options_Manager::update_option('theme_support', 'transitional');
                AMP_Options_Manager::update_option('mobile_redirect', false);
			} else if (daftplugInstantify::getSetting('ampMode') == 'only') {
				AMP_Options_Manager::update_option('theme_support', 'standard');
			} else {
                AMP_Options_Manager::update_option('theme_support', 'transitional');
                AMP_Options_Manager::update_option('mobile_redirect', true);
			}
            
			if (daftplugInstantify::getSetting('ampOnAll') == 'on') {
                AMP_Options_Manager::update_option('all_templates_supported', true);
                $allPostTypes = array();
                foreach (array_map('get_post_type_object', AMP_Post_Type_Support::get_eligible_post_types()) as $postType) {
                    $allPostTypes[] = $postType->name;
                }
                AMP_Options_Manager::update_option('supported_post_types', (array)$allPostTypes);
			} else {
				AMP_Options_Manager::update_option('all_templates_supported', false);
				AMP_Options_Manager::update_option('supported_post_types', (array)daftplugInstantify::getSetting('ampOnPostTypes'));
				AMP_Options_Manager::update_option('supported_templates', (array)daftplugInstantify::getSetting('ampOnPages'));
            }
            
            if (daftplugInstantify::getSetting('ampPluginSuppression') == 'on') {
                AMP_Options_Manager::update_option('suppressed_plugins', (array)daftplugInstantifyAmpAdminCompatibility::getSuppressedPlugins());
            } else {
                AMP_Options_Manager::update_option('suppressed_plugins', array());
            }
		}

        public function removeAmpAdminMenu() {
            return remove_menu_page('amp-options');
        }
    }
}