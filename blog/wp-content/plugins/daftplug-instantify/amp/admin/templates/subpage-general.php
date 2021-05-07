<?php

if (!defined('ABSPATH')) exit;

?>
<div class="daftplugAdminPage_subpage -general -flex12" data-subpage="general">
	<div class="daftplugAdminPage_content -flex8">
        <div class="daftplugAdminSettings -flexAuto">
            <form name="daftplugAdminSettings_form" class="daftplugAdminSettings_form" data-nonce="<?php echo wp_create_nonce("{$this->optionName}_settings_nonce"); ?>" spellcheck="false" autocomplete="off">
                <fieldset class="daftplugAdminFieldset">
                    <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('AMP Mode', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php esc_html_e('From this section you are able to choose AMP mode. It determines the behavior of your website for serving AMP pages.', $this->textDomain); ?></p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select AMP mode. We recommend to have both PWA and AMP as your website will maintain PWA features and will also have a corresponding AMP version for Google Search Results.', $this->textDomain); ?></p>
                        <label for="ampMode" class="daftplugAdminField_label -flex4"><?php esc_html_e('AMP Mode', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="ampMode" id="ampMode" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('AMP Mode', $this->textDomain); ?>" autocomplete="off" required>
                                <option value="both" <?php selected('both', daftplugInstantify::getSetting('ampMode')) ?>><?php esc_html_e('Have both PWA and AMP', $this->textDomain); ?></option>
                                <option value="only" <?php selected('only', daftplugInstantify::getSetting('ampMode')) ?>><?php esc_html_e('Have only AMP', $this->textDomain); ?></option>
                                <option value="mobile" <?php selected('mobile', daftplugInstantify::getSetting('ampMode')) ?>><?php esc_html_e('Have AMP for mobiles only', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="daftplugAdminFieldset">
                    <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('AMP Support', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php esc_html_e('From this section you are able to enable or disable AMP support on particular post types and pages, including custom post types.', $this->textDomain); ?></p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable AMP on all post types and pages. If turned on, this will allow all content on your site to have a corresponding AMP version. We recommend to enable AMP on all content to serve all pages as AMP.', $this->textDomain); ?></p>
                        <label for="ampOnAll" class="daftplugAdminField_label -flex4"><?php esc_html_e('All Content', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="ampOnAll" id="ampOnAll" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('ampOnAll'), 'on'); ?>>
                        </label>
                    </div>
                    <div class="daftplugAdminField -ampOnAllDependentHideE">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select particular post types where you want AMP version to be available.', $this->textDomain); ?></p>
                        <label for="ampOnPostTypes" class="daftplugAdminField_label -flex4"><?php esc_html_e('Supported Post Types', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select multiple name="ampOnPostTypes" id="ampOnPostTypes" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Supported Post Types', $this->textDomain); ?>" autocomplete="off" required>
                                <?php
                                foreach (array_map('get_post_type_object', AMP_Post_Type_Support::get_eligible_post_types()) as $postType) {
                                ?>
                                <option value="<?php echo $postType->name; ?>" <?php selected(true, in_array($postType->name, (array)daftplugInstantify::getSetting('ampOnPostTypes'))); ?>><?php echo $postType->label; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField -ampOnAllDependentHideE">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select particular pages where you want AMP version to be available.', $this->textDomain); ?></p>
                        <label for="ampOnPages" class="daftplugAdminField_label -flex4"><?php esc_html_e('Supported Pages', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select multiple name="ampOnPages" id="ampOnPages" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Supported Pages', $this->textDomain); ?>" autocomplete="off" required>
                                <?php
                                foreach (AMP_Theme_Support::get_supportable_templates() as $id => $option) {
                                ?>
                                <option value="<?php echo $id; ?>" <?php selected(true, in_array($id, (array)daftplugInstantify::getSetting('ampOnPages'))); ?>><?php echo $option['label'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </fieldset>
                <div class="daftplugAdminSettings_submit">
                    <button type="submit" class="daftplugAdminButton -submit" data-submit="<?php esc_html_e('Save Settings', $this->textDomain); ?>" data-waiting="<?php esc_html_e('Waiting', $this->textDomain); ?>" data-submitted="<?php esc_html_e('Settings Saved', $this->textDomain); ?>" data-failed="<?php esc_html_e('Saving Failed', $this->textDomain); ?>"></button>
                </div>
            </form>
        </div>
    </div>
</div>