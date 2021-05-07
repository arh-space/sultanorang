<?php

if (!defined('ABSPATH')) exit;

if (!class_exists('daftplugInstantifyFbiaAdminFeed')) {
    class daftplugInstantifyFbiaAdminFeed {
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

            add_action('add_meta_boxes', array($this, 'addMetaBoxes'), 10, 2);
            add_action('save_post', array($this, 'saveMetaBox'), 10, 2);
    	}

        public function renderMetaBoxContent($post, $callbackArgs) {
            $excludeFromFbia = get_post_meta($post->ID, 'excludeFromFbia', true);
            wp_nonce_field("{$this->optionName}_articles_meta_nonce", "{$this->optionName}_articles_meta_nonce");
            ?>
            <label>
                <input type="checkbox" name="excludeFromFbia" value="exclude" <?php checked($excludeFromFbia, 'exclude'); ?>>
                <?php esc_html_e('Exclude from FB Instant Articles', $this->textDomain); ?>
            </label>
            <?php
        }

        public function addMetaBoxes($postType, $post)  {
            $types = (array)daftplugInstantify::getSetting('fbiaPostTypes');

            if (in_array($post->post_type, $types)) {
                add_meta_box("{$this->optionName}_articles_meta_box", esc_html__('Instant Articles', $this->textDomain), array($this, 'renderMetaBoxContent'), $postType, 'side', 'default', array());
            }
        }

        public function saveMetaBox($postId) {
            $isAutosave = wp_is_post_autosave($postId);
            $isRevision = wp_is_post_revision($postId);
            $isValidNonce = (isset($_POST["{$this->optionName}_articles_meta_nonce"]) && wp_verify_nonce($_POST["{$this->optionName}_articles_meta_nonce"], $this->pluginBasename)) ? 'true' : 'false';

            if ($isAutosave || $isRevision || !$isValidNonce) {
                return;
            }

            if (isset($_POST['excludeFromFbia'])) {
                $excludeFromFbia = $_POST['excludeFromFbia'];
            } else {
                $excludeFromFbia = 'include';
            }
            
            update_post_meta($postId, 'excludeFromFbia', $excludeFromFbia);
        }
    }
}