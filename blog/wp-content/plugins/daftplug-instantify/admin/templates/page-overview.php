<?php

if (!defined('ABSPATH')) exit;

?>
<article class="daftplugAdminPage -overview" data-page="overview">
    <div class="daftplugAdminPage_heading -flex12">
        <img class="daftplugAdminPage_illustration" src="<?php echo plugins_url('admin/assets/img/illustration-overview.png', $this->pluginFile)?>"/>
        <h2 class="daftplugAdminPage_title"><?php esc_html_e('Overview', $this->textDomain); ?></h2>
        <h5 class="daftplugAdminPage_subheading"><?php printf(__('Welcome to <strong>%s</strong> plugin. Here you may find status, analytics, warnings or any other information related to the plugin.', $this->textDomain), $this->name); ?></h5>
    </div>
    <div class="daftplugAdminPage_content -flex10">
        <div class="daftplugAdminContentWrapper -notice">
            <div class="daftplugAdminNotice -flexAuto">
                <div class="daftplugAdminNotice_container">
                    <div class="daftplugAdminNotice_icon -flex6">
                        <h6 class="daftplugAdminNotice_appname"><?php echo daftplugInstantify::getSetting('pwaName'); ?></h6>
                        <p class="daftplugAdminNotice_appdesc"><?php echo daftplugInstantify::getSetting('pwaDescription'); ?></p>
                        <img class="daftplugAdminNotice_appicon" src="<?php echo wp_get_attachment_image_src(daftplugInstantify::getSetting('pwaIcon'), 'full')[0] ?? ''; ?>"/>
                        <img class="daftplugAdminNotice_img" src="<?php echo plugins_url('admin/assets/img/image-playstore-frame.png', $this->pluginFile); ?>"/>
                    </div>
                    <div class="daftplugAdminNotice_text -flex6 -textCenter">
                    	<h3 class="daftplugAdminNotice_title"><?php esc_html_e('Want your website published on Google Play Store?', $this->textDomain); ?></h3>
                    	<p class="daftplugAdminNotice_desc"><?php esc_html_e('Get your PWA website in Google Play store as a native Android application. We can convert your PWA website into Google Play ready APK package on top of TWA (Trusted Web Activity) technology for $19. You will get a ready-made APK file with a simple guide how to submit it to Google Play store.', $this->textDomain); ?></p>
                    	<span class="daftplugAdminButton -generateApp" data-open-popup="generateAppModal"><?php esc_html_e('Get Android App', $this->textDomain); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php $this->renderNotice(); ?>
    <div class="daftplugAdminPage_content -flex6">
        <div class="daftplugAdminContentWrapper">
            <fieldset class="daftplugAdminPluginFeatures -flexAuto">
	            <h4 class="daftplugAdminPluginFeatures_title"><?php esc_html_e('Plugin Features', $this->textDomain); ?></h4>
	            <div class="daftplugAdminField">
	                <label for="pwa" class="daftplugAdminField_label -flex9"><?php esc_html_e('Progressive Web Apps (PWA)', $this->textDomain); ?></label>
	                <label class="daftplugAdminInputCheckbox -flexAuto -featuresCheckbox" data-nonce="<?php echo wp_create_nonce("{$this->optionName}_settings_nonce"); ?>">
	                    <input type="checkbox" name="pwa" id="pwa" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwa'), 'on'); ?>>
	                </label>
	            </div>
	            <div class="daftplugAdminField">
	                <label for="amp" class="daftplugAdminField_label -flex9"><?php esc_html_e('Google Accelerated Mobile Pages‎ (AMP)', $this->textDomain); ?></label>
	                <label class="daftplugAdminInputCheckbox -flexAuto -featuresCheckbox" data-nonce="<?php echo wp_create_nonce("{$this->optionName}_settings_nonce"); ?>">
	                    <input type="checkbox" name="amp" id="amp" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('amp'), 'on'); ?>>
	                </label>
	            </div>
	            <div class="daftplugAdminField">
	                <label for="fbia" class="daftplugAdminField_label -flex9"><?php esc_html_e('Facebook Instant Articles (FBIA)', $this->textDomain); ?></label>
	                <label class="daftplugAdminInputCheckbox -flexAuto -featuresCheckbox" data-nonce="<?php echo wp_create_nonce("{$this->optionName}_settings_nonce"); ?>">
	                    <input type="checkbox" name="fbia" id="fbia" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('fbia'), 'on'); ?>>
	                </label>
	            </div>
	        </fieldset>   
        </div>
    </div>
    <div class="daftplugAdminPage_content -flex6">
        <div class="daftplugAdminContentWrapper">
            <div class="daftplugAdminLicenseInfo -flexAuto">
    		    <h4 class="daftplugAdminLicenseInfo_title"><?php esc_html_e('License Information', $this->textDomain); ?></h4>
                <div class="daftplugAdminStatus_container">
                    <div class="daftplugAdminStatus_label -flex4"><?php esc_html_e('License Status', $this->textDomain); ?></div>
                    <div class="daftplugAdminStatus_text -flex8"><svg class="daftplugAdminStatus_icon -iconCheck" style="margin-left: 0;"><use href="#iconCheck"></use></svg> <?php esc_html_e('Active', $this->textDomain); ?></div>                
                </div>
                <div class="daftplugAdminStatus_container">
                    <div class="daftplugAdminStatus_label -flex4"><?php esc_html_e('Purchase Code', $this->textDomain); ?></div>
                    <div class="daftplugAdminStatus_text -flex8" style="filter: blur(2.5px);">your-license-code-is-hidden</div>                
                </div>
                <div class="daftplugAdminStatus_container">
                    <div class="daftplugAdminStatus_label -flex4"><?php esc_html_e('Action', $this->textDomain); ?></div>
                    <div class="daftplugAdminStatus_text -flex8">
						<button type="submit" class="daftplugAdminButton -submit -confirm -deactivateLicense" data-submit="<?php esc_html_e('Deactivate License', $this->textDomain); ?>" data-waiting="<?php esc_html_e('Waiting', $this->textDomain); ?>" data-submitted="<?php esc_html_e('License Deactivated', $this->textDomain); ?>" data-failed="<?php esc_html_e('Deactivation Failed', $this->textDomain); ?>" data-sure="<?php esc_html_e('Are You Sure?', $this->textDomain); ?>" data-duration="2000ms" data-nonce="<?php echo wp_create_nonce("{$this->optionName}_deactivate_license_nonce"); ?>" data-tooltip="<?php esc_html_e('Press & Hold to deactivate license', $this->textDomain); ?>" data-tooltip-flow="top"></button>
                    </div>                
                </div>
            </div>
    	</div>
    </div>
    <div class="daftplugAdminPage_content -flex5">
        <div class="daftplugAdminContentWrapper">
            <div class="daftplugAdminStatus -flexAuto">
                <h4 class="daftplugAdminStatus_title"><?php esc_html_e('Progressive Web Apps', $this->textDomain); ?></h4>
                <div class="daftplugAdminStatus_container">
                    <div class="daftplugAdminStatus_label -flex4"><?php esc_html_e('Validate PWA', $this->textDomain); ?></div>
                    <div class="daftplugAdminStatus_text -flexAuto"><?php if(daftplugInstantify::getSetting('pwa')=='off'){echo'None';}else{printf('<a class="daftplugAdminLink" href="%s" target="_blank">%s</a>',esc_url(add_query_arg(array('psiurl'=>trailingslashit(strtok(home_url('/', 'https'), '?')),'strategy'=>'mobile','category'=>'pwa'),'https://googlechrome.github.io/lighthouse/viewer/')),esc_html__('View PWA Validation', $this->textDomain));} ?></div>
                </div>
                <?php foreach ($this->getPwaStatus() as $status) { ?>
                <div class="daftplugAdminStatus_container">
                    <div class="daftplugAdminStatus_label -flex4"><?php echo $status['title'] ?></div>
                    <?php if ($status['condition']) {
                        echo '<div class="daftplugAdminStatus_text -flexAuto"><svg class="daftplugAdminStatus_icon -iconCheck"><use href="#iconCheck"></use></svg> '.$status['true'].'</div>';
                    } else {
                        echo '<div class="daftplugAdminStatus_text -flexAuto"><svg class="daftplugAdminStatus_icon -iconX"><use href="#iconX"></use></svg> '.$status['false'].'</div>';
                    } ?>
                </div>
                <?php } ?>
            </div>
    	</div>
    </div>
    <div class="daftplugAdminPage_content -flex7">
        <div class="daftplugAdminContentWrapper">
            <div class="daftplugAdminInstallationAnalytics -flexAuto <?php if (daftplugInstantify::getSetting('pwa') == 'off') { echo '-disabled'; } ?>">
                <div class="daftplugAdminInstallationAnalytics_header">
                    <h4 class="daftplugAdminInstallationAnalytics_title"><?php esc_html_e('PWA Installation Analytics', $this->textDomain); ?></h4>
                    <div class="daftplugAdminInstallationAnalytics_buttons">
                        <button class="daftplugAdminButton -analyticsButton -active" data-period="1week">1 Week</button>
                        <button class="daftplugAdminButton -analyticsButton" data-period="1month">1 Month</button>
                        <button class="daftplugAdminButton -analyticsButton" data-period="3month">3 Month</button>
                        <button class="daftplugAdminButton -analyticsButton" data-period="6month">6 Month</button>
                        <button class="daftplugAdminButton -analyticsButton" data-period="1year">1 Year</button>                  
                    </div>
                </div>
                <div class="daftplugAdminInstallationAnalytics_chartArea">
                    <canvas id="daftplugAdminInstallationAnalytics_chart"></canvas>
                </div>
            </div>    
    	</div>
    </div>
    <div class="daftplugAdminPage_content -flex5">
        <div class="daftplugAdminContentWrapper">
            <div class="daftplugAdminAmpInfo -flexAuto <?php if (daftplugInstantify::getSetting('amp') == 'off' || (daftplugInstantify::getSetting('amp') == 'on' && daftplugInstantify::isAmpPluginActive())) { echo '-disabled'; } ?>">
                <h4 class="daftplugAdminFbiaInfo_title"><?php esc_html_e('Google AMP', $this->textDomain); ?></h4>
                <div class="daftplugAdminStatus_container">
                    <div class="daftplugAdminStatus_label -flex4"><?php esc_html_e('AMP URL', $this->textDomain); ?></div>
                    <div class="daftplugAdminStatus_text -flex8"><a class="daftplugAdminLink" href="<?php if(daftplugInstantify::getSetting('amp')=='off'){echo'#';}else{echo trailingslashit(strtok(home_url('/', 'https'), '?')).'?amp';} ?>" target="_blank"><?php if(daftplugInstantify::getSetting('amp')=='off'){echo'None';}else{esc_html_e('View AMP URL', $this->textDomain);} ?></a></div>                
                </div>
                <div class="daftplugAdminStatus_container">
                    <div class="daftplugAdminStatus_label -flex4"><?php esc_html_e('Validated URLs', $this->textDomain); ?></div>
                    <div class="daftplugAdminStatus_text -flex8"><?php if(daftplugInstantify::getSetting('amp')=='off'){echo'None';}else{printf('<a class="daftplugAdminLink" href="%s">%s</a>',esc_url(add_query_arg('post_type',AMP_Validated_URL_Post_Type::POST_TYPE_SLUG,admin_url('edit.php'))),esc_html__('View Validated URLs', $this->textDomain));} ?></div>                
                </div>
                <div class="daftplugAdminStatus_container">
                    <div class="daftplugAdminStatus_label -flex4"><?php esc_html_e('Error Index', $this->textDomain); ?></div>
                    <div class="daftplugAdminStatus_text -flex8"><?php if(daftplugInstantify::getSetting('amp')=='off'){echo'None';}else{printf('<a class="daftplugAdminLink" href="%s">%s</a>',esc_url(get_admin_url(null,'edit-tags.php?taxonomy='.AMP_Validation_Error_Taxonomy::TAXONOMY_SLUG.'&post_type=amp_validated_url')),esc_html__('View Error Index',$this->textDomain));} ?></div>               
                </div>
            </div>    
        </div>
    </div>
    <div class="daftplugAdminPage_content -flex7">
        <div class="daftplugAdminContentWrapper">
            <div class="daftplugAdminFbiaInfo -flexAuto <?php if (daftplugInstantify::getSetting('fbia') == 'off') { echo '-disabled'; } ?>">
                <h4 class="daftplugAdminFbiaInfo_title"><?php esc_html_e('Facebook Instant Articles', $this->textDomain); ?></h4>
                <div class="daftplugAdminStatus_container">
                    <div class="daftplugAdminStatus_label -flex4"><?php esc_html_e('RSS Feed URL', $this->textDomain); ?></div>
                    <div class="daftplugAdminStatus_text -flex8"><a class="daftplugAdminLink" href="<?php if(daftplugInstantify::getSetting('fbia')=='off'){echo'#';}else{echo$this->daftplugInstantifyFbia->feedUrl;} ?>" target="_blank"><?php if(daftplugInstantify::getSetting('fbia')=='off'){echo'None';}else{esc_html_e('View RSS Feed URL', $this->textDomain);} ?></a></div>                
                </div>
                <div class="daftplugAdminStatus_container">
                    <div class="daftplugAdminStatus_label -flex4"><?php esc_html_e('Validate Feed', $this->textDomain); ?></div>
                    <div class="daftplugAdminStatus_text -flex8"><?php if(daftplugInstantify::getSetting('fbia')=='off'){echo'None';}else{printf('<a class="daftplugAdminLink" href="%s" target="_blank">%s</a>',esc_url(add_query_arg('url',$this->daftplugInstantifyFbia->feedUrl,'https://podba.se/validate/')),esc_html__('View Feed Validation', $this->textDomain));} ?></div>               
                </div>
                <div class="daftplugAdminStatus_container">
                    <div class="daftplugAdminStatus_label -flex4"><?php esc_html_e('Article Count', $this->textDomain); ?></div>
                    <div class="daftplugAdminStatus_text -flex8"><?php if(daftplugInstantify::getSetting('fbia') == 'off'){echo'None';}else{echo$this->daftplugInstantifyFbia->getArticleCount();} ?></div>                
                </div>
            </div>    
        </div>
    </div>
</article>