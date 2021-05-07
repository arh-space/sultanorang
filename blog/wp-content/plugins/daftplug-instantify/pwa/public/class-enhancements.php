<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('daftplugInstantifyPwaPublicEnhancements')) {
    class daftplugInstantifyPwaPublicEnhancements {
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

        public $daftplugInstantifyPwaPublic;

    	public function __construct($config, $daftplugInstantifyPwaPublic) {
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

            $this->daftplugInstantifyPwaPublic = $daftplugInstantifyPwaPublic;

            if (daftplugInstantify::getSetting('pwaBackgroundSync') == 'on') {
                add_filter("{$this->optionName}_pwa_serviceworker_workbox", array($this, 'addBackgroundSyncToServiceWorker'));
            }

            if (daftplugInstantify::getSetting('pwaWebShareTarget') == 'on') {
                add_filter("{$this->optionName}_pwa_manifest", array($this, 'addWebShareTargetToManifest'));
            }
        }
        
        public function addBackgroundSyncToServiceWorker($serviceWorker) {
            $serviceWorker .= "
            workbox.routing.registerRoute(
                new RegExp('/*'),
                new workbox.strategies.NetworkOnly({
                    plugins: [
                        new workbox.backgroundSync.BackgroundSyncPlugin('bgSyncQueue', {
                            maxRetentionTime: 24 * 60
                        })
                    ]
                }),
                'POST'
            );";

            return $serviceWorker;
        }

        public function addWebShareTargetToManifest($manifest) {
            $manifest['share_target'] = array(
                'action' => daftplugInstantify::getSetting('pwaWebShareTargetAction'),
                'method' => 'GET',
                'enctype' => 'application/x-www-form-urlencoded',
                'params' => array(
                    'title' => 'title',
                    'text' => 'text',
                    'url' => daftplugInstantify::getSetting('pwaWebShareTargetUrlQuery'),
                ),
            );

            return $manifest;
        }
    }
}