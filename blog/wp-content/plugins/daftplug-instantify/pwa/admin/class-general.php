<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('daftplugInstantifyPwaAdminGeneral')) {
    class daftplugInstantifyPwaAdminGeneral {
    	public $name;
        public $description;
        public $slug;
        public $version;
        public $textDomain;
        public $optionName;

        public $pluginFile;
        public $pluginBasename;

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

            $this->settings = $config['settings'];
    	}

        public function getPostTypes() {
            return array_values(
                        get_post_types(
                            array(
                               'public' => true,
                            ),
                            'names'
                        )
                    );
        }

        public function getPageTypes() {
            if (get_option('show_on_front') === 'page') {
                $pageTypes['is_front_page'] = array(
                    'label'  => __('Homepage', $this->textDomain),
                );

                $pageTypes['is_home'] = array(
                    'label' => __('Blog', $this->textDomain),
                );
            } else {
                $pageTypes['is_home'] = array(
                    'label' => __('Homepage', $this->textDomain),
                );
            }
    
            $pageTypes = array_merge(
                $pageTypes,
                array(
                    'is_author'  => array(
                        'label'  => __('Author', $this->textDomain),
                        'parent' => 'is_archive',
                    ),
                    'is_search'  => array(
                        'label' => __('Search', $this->textDomain),
                    ),
                    'is_404'     => array(
                        'label' => __('Not Found (404)', $this->textDomain),
                    ),
                )
            );
    
            if (taxonomy_exists('category')) {
                $pageTypes['is_category'] = array(
                    'label'  => get_taxonomy('category')->labels->name,
                );
            }

            if (taxonomy_exists('post_tag')) {
                $pageTypes['is_tag'] = array(
                    'label'  => get_taxonomy('post_tag')->labels->name,
                );
            }

            return $pageTypes;
        }
    }
}