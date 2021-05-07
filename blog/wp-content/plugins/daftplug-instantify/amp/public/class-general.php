<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('daftplugInstantifyAmpPublicGeneral')) {
    class daftplugInstantifyAmpPublicGeneral {
    	public $name;
        public $description;
        public $slug;
        public $version;
        public $textDomain;
        public $optionName;

        public $pluginFile;
        public $pluginBasename;

        public $settings;

        public $daftplugInstantifyAmpPublic;

    	public function __construct($config, $daftplugInstantifyAmpPublic) {
    		$this->name = $config['name'];
            $this->description = $config['description'];
            $this->slug = $config['slug'];
            $this->version = $config['version'];
            $this->textDomain = $config['text_domain'];
            $this->optionName = $config['option_name'];

            $this->pluginFile = $config['plugin_file'];
            $this->pluginBasename = $config['plugin_basename'];

            $this->settings = $config['settings'];

            add_action('admin_bar_menu', array($this, 'getPairedBrowsingLink'), 200);
    	}

        public function getPairedBrowsingLink($adminBar) {
            if (amp_is_canonical() || !amp_is_available()) {
                return;
            }

            $adminBar->add_node([
                'parent' => 'amp',
                'id' => 'amp-paired-browsing',
                'title' => esc_html__('Paired Browsing', $this->textDomain),
                'href' => AMP_Theme_Support::get_paired_browsing_url(),
            ]);

            $adminBar->remove_node('amp-settings');
        }
    }
}