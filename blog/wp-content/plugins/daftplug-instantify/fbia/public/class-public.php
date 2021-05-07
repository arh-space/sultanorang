<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('daftplugInstantifyFbiaPublic')) {
    class daftplugInstantifyFbiaPublic {
    	public $name;
        public $description;
        public $slug;
        public $version;
        public $textDomain;
        public $optionName;

        public $pluginFile;
        public $pluginBasename;

        public $dependencies;

        public $settings;

        public $partials;

        public $daftplugInstantifyFbiaSanitizer;

    	public function __construct($config, $daftplugInstantifyFbiaSanitizer) {
    		$this->name = $config['name'];
            $this->description = $config['description'];
            $this->slug = $config['slug'];
            $this->version = $config['version'];
            $this->textDomain = $config['text_domain'];
            $this->optionName = $config['option_name'];

            $this->pluginFile = $config['plugin_file'];
            $this->pluginBasename = $config['plugin_basename'];

            $this->dependencies = array();

            $this->settings = $config['settings'];

            $this->partials = $this->generatePartials();

            $this->daftplugInstantifyFbiaSanitizer = $daftplugInstantifyFbiaSanitizer;

            add_action('wp_enqueue_scripts', array($this, 'loadAssets'));
            add_action('init', array($this, 'addFeed'));
            add_filter("{$this->optionName}_articles_content", array($this, 'sanitizeContent'), 10, 2);
    	}

        public function loadAssets() {
            if (daftplugInstantify::isAmpPage()) {
                return;
            }
            
            $this->dependencies[] = 'jquery';
            $this->dependencies[] = "{$this->slug}-public";

            wp_enqueue_style("{$this->slug}-fbia-public", plugins_url('fbia/public/assets/css/style-fbia.min.css', $this->pluginFile), array(), $this->version);
            wp_enqueue_script("{$this->slug}-fbia-public", plugins_url('fbia/public/assets/js/script-fbia.min.js', $this->pluginFile), $this->dependencies, $this->version, true);
        }

    	public function generatePartials() {
            $partials = array(
                'instantArticles' => plugin_dir_path(__FILE__) . implode(DIRECTORY_SEPARATOR, array('partials', 'display-instantarticles.php')),
            );

            return $partials;
    	}

        public function addFeed() {
            $added = false;

            add_feed("{$this->slug}-articles", array($this, 'renderInstantArticles'));

            $rules = (array)get_option('rewrite_rules');
            $feeds = array_keys($rules, 'index.php?&feed=$matches[1]' );

            foreach ($feeds as $feed) {
                if (strpos($feed, "{$this->slug}-articles") !== false) {
                    $added = true;
                }
            }

            if (!$added) {
                flush_rewrite_rules(false);
            }
        }

        public function sanitizeContent($content) {
            $content = $this->daftplugInstantifyFbiaSanitizer->sanitize('<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>'.$content.'</body><html>');

            return $content;
        }   

        public function renderInstantArticles() {
            include_once($this->partials['instantArticles']);
        }
    }
}