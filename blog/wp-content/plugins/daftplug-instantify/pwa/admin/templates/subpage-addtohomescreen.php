<?php

if (!defined('ABSPATH')) exit;

?>
<div class="daftplugAdminPage_subpage -addtohomescreen -flex12" data-subpage="addtohomescreen">
	<div class="daftplugAdminPage_content -flex8">
        <div class="daftplugAdminSettings -flexAuto">
            <form name="daftplugAdminSettings_form" class="daftplugAdminSettings_form" data-nonce="<?php echo wp_create_nonce("{$this->optionName}_settings_nonce"); ?>" spellcheck="false" autocomplete="off">
                <fieldset class="daftplugAdminFieldset">
                    <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Web App Manifest', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php esc_html_e('The web app manifest is a simple JSON file that tells the browser about your web application and how it should behave when "installed" on the user\'s mobile device or desktop. Having a manifest is required by browsers to show the Add to Home Screen prompt.', $this->textDomain); ?></p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Dynamic Manifest option gives you ability to make each of your pages and posts installable individually. That means that your app name, short name, description and URL will be automatically fetched from the current singular page that the user is accessing. This will apply only on singular pages, so on the homepage app name, short name, description and URL will be taken from the settings below.', $this->textDomain); ?></p>
                        <label for="pwaDynamicManifest" class="daftplugAdminField_label -flex4"><?php esc_html_e('Dynamic Manifest', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaDynamicManifest" id="pwaDynamicManifest" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaDynamicManifest'), 'on'); ?>>
                        </label>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enter the name of your web app.', $this->textDomain); ?></p>
                        <label for="pwaName" class="daftplugAdminField_label -flex4"><?php esc_html_e('Name', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputText -flexAuto">
                            <input type="text" name="pwaName" id="pwaName" class="daftplugAdminInputText_field" value="<?php echo daftplugInstantify::getSetting('pwaName'); ?>" data-placeholder="<?php esc_html_e('Name', $this->textDomain); ?>" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enter the shortened name of your web app. Maximum 12 characters.', $this->textDomain); ?></p>
                        <label for="pwaShortName" class="daftplugAdminField_label -flex4"><?php esc_html_e('Short Name', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputText -flexAuto">
                            <input type="text" name="pwaShortName" id="pwaShortName" class="daftplugAdminInputText_field" maxlength="12" value="<?php echo daftplugInstantify::getSetting('pwaShortName'); ?>" data-placeholder="<?php esc_html_e('Short Name', $this->textDomain); ?>" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the start page of your web application.', $this->textDomain); ?></p>
                        <label for="pwaStartPage" class="daftplugAdminField_label -flex4"><?php esc_html_e('Start Page', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaStartPage" id="pwaStartPage" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Start Page', $this->textDomain); ?>" autocomplete="off" required>
                                <option value="<?php echo trailingslashit(strtok(home_url('/', 'https'), '?')); ?>" <?php selected(daftplugInstantify::getSetting('pwaStartPage'), trailingslashit(strtok(home_url('/', 'https'), '?'))) ?>><?php esc_html_e('Home Page', $this->textDomain); ?></option>
                            	<?php foreach (get_pages() as $page) { ?>
                                <option value="<?php echo get_page_link($page->ID) ?>" <?php selected(daftplugInstantify::getSetting('pwaStartPage'), get_page_link($page->ID)) ?>><?php echo $page->post_title ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Describe your web application. The description should contain your web app\'s purpose and goals.', $this->textDomain); ?></p>
                        <label for="pwaDescription" class="daftplugAdminField_label -flex4"><?php esc_html_e('Description', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputTextarea -flexAuto">
                            <textarea name="pwaDescription" id="pwaDescription" class="daftplugAdminInputTextarea_field" data-placeholder="<?php esc_html_e('Description', $this->textDomain); ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" rows="4" required><?php echo daftplugInstantify::getSetting('pwaDescription'); ?></textarea>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select icon of your web application. Your web app icon should be the logo of your website.', $this->textDomain); ?></p>
                        <label for="pwaIcon" class="daftplugAdminField_label -flex4"><?php esc_html_e('Icon', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputUpload -flexAuto">
							<input type="text" name="pwaIcon" id="pwaIcon" class="daftplugAdminInputUpload_field" value="<?php echo daftplugInstantify::getSetting('pwaIcon'); ?>" data-mimes="png" data-min-width="512" data-max-width="" data-min-height="512" data-max-height="" data-attach-url="<?php echo wp_get_attachment_image_src(daftplugInstantify::getSetting('pwaIcon'), array(512, 512))[0]; ?>" required>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the display mode of your web application. We recommend to choose Standalone, as it provides a native app feeling.', $this->textDomain); ?></p>
                        <label for="pwaDisplayMode" class="daftplugAdminField_label -flex4"><?php esc_html_e('Display Mode', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaDisplayMode" id="pwaDisplayMode" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Display Mode', $this->textDomain); ?>" autocomplete="off" required>
                                <option value="fullscreen" <?php selected(daftplugInstantify::getSetting('pwaDisplayMode'), 'fullscreen') ?>><?php esc_html_e('Fullscreen - App takes whole display', $this->textDomain); ?></option>
                                <option value="standalone" <?php selected(daftplugInstantify::getSetting('pwaDisplayMode'), 'standalone') ?>><?php esc_html_e('Standalone - Native app feeling', $this->textDomain); ?></option>
                                <option value="minimal-ui" <?php selected(daftplugInstantify::getSetting('pwaDisplayMode'), 'minimal-ui') ?>><?php esc_html_e('Minimal UI - App with browser controls', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select orientation of your web application. We recommend to choose Portrait, as it provides a more native app feeling.', $this->textDomain); ?></p>
                        <label for="pwaOrientation" class="daftplugAdminField_label -flex4"><?php esc_html_e('Orientation', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaOrientation" id="pwaOrientation" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Orientation', $this->textDomain); ?>" autocomplete="off" required>
                                <option value="any" <?php selected(daftplugInstantify::getSetting('pwaOrientation'), 'any') ?>><?php esc_html_e('Both', $this->textDomain); ?></option>
                                <option value="portrait" <?php selected(daftplugInstantify::getSetting('pwaOrientation'), 'portrait') ?>><?php esc_html_e('Portrait', $this->textDomain); ?></option>
                                <option value="landscape" <?php selected(daftplugInstantify::getSetting('pwaOrientation'), 'landscape') ?>><?php esc_html_e('Landscape', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the iOS status bar style for your web application when accessed from Apple devices.', $this->textDomain); ?></p>
                        <label for="pwaIosStatusBarStyle" class="daftplugAdminField_label -flex4"><?php esc_html_e('iOS Status Bar Style', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select name="pwaIosStatusBarStyle" id="pwaIosStatusBarStyle" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('iOS Status Bar Style', $this->textDomain); ?>" autocomplete="off" required>
                                <option value="default" <?php selected(daftplugInstantify::getSetting('pwaIosStatusBarStyle'), 'default') ?>><?php esc_html_e('White bar with black text', $this->textDomain); ?></option>
                                <option value="black" <?php selected(daftplugInstantify::getSetting('pwaIosStatusBarStyle'), 'black') ?>><?php esc_html_e('Black bar with white text', $this->textDomain); ?></option>
                                <option value="black-translucent" <?php selected(daftplugInstantify::getSetting('pwaIosStatusBarStyle'), 'black-translucent') ?>><?php esc_html_e('Transparent bar with white text', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the theme color of your web application. It should be same as the main color palette of your website.', $this->textDomain); ?></p>
                        <label for="pwaThemeColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Theme Color', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputColor -flexAuto">
                            <input type="text" name="pwaThemeColor" id="pwaThemeColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaThemeColor'); ?>" data-placeholder="<?php esc_html_e('Theme Color', $this->textDomain); ?>" required>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the background color of your web application. It should be same as the background color of your website.', $this->textDomain); ?></p>
                        <label for="pwaBackgroundColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Background Color', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputColor -flexAuto">
                            <input type="text" name="pwaBackgroundColor" id="pwaBackgroundColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaBackgroundColor'); ?>" data-placeholder="<?php esc_html_e('Background Color', $this->textDomain); ?>" required>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the categories of your web application. The categories describe the expected application categories to which the web application belongs. It\'s used as a hint to catalogs or store listing web applications and it is expected that these will make a best effort to find appropriate categories (or category) under which to list the web application. We recommend no to choose more than 3 categories.', $this->textDomain); ?></p>
                        <label for="pwaCategories" class="daftplugAdminField_label -flex4"><?php esc_html_e('Categories', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select multiple name="pwaCategories" id="pwaCategories" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Categories', $this->textDomain); ?>" autocomplete="off" required>
                                <option value="books" <?php selected(true, in_array('books', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Books', $this->textDomain); ?></option>
                                <option value="business" <?php selected(true, in_array('business', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Business', $this->textDomain); ?></option>
                                <option value="education" <?php selected(true, in_array('education', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Education', $this->textDomain); ?></option>
                                <option value="entertainment" <?php selected(true, in_array('entertainment', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Entertainment', $this->textDomain); ?></option>
                                <option value="finance" <?php selected(true, in_array('finance', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Finance', $this->textDomain); ?></option>
                                <option value="fitness" <?php selected(true, in_array('fitness', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Fitness', $this->textDomain); ?></option>
                                <option value="food" <?php selected(true, in_array('food', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Food', $this->textDomain); ?></option>
                                <option value="games" <?php selected(true, in_array('games', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Games', $this->textDomain); ?></option>
                                <option value="government" <?php selected(true, in_array('government', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Government', $this->textDomain); ?></option>
                                <option value="health" <?php selected(true, in_array('health', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Health', $this->textDomain); ?></option>
                                <option value="kids" <?php selected(true, in_array('kids', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Kids', $this->textDomain); ?></option>
                                <option value="lifestyle" <?php selected(true, in_array('lifestyle', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Lifestyle', $this->textDomain); ?></option>
                                <option value="magazines" <?php selected(true, in_array('magazines', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Magazines', $this->textDomain); ?></option>
                                <option value="medical" <?php selected(true, in_array('medical', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Medical', $this->textDomain); ?></option>
                                <option value="music" <?php selected(true, in_array('music', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Music', $this->textDomain); ?></option>
                                <option value="navigation" <?php selected(true, in_array('navigation', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Navigation', $this->textDomain); ?></option>
                                <option value="news" <?php selected(true, in_array('news', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('News', $this->textDomain); ?></option>
                                <option value="personalization" <?php selected(true, in_array('personalization', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Personalization', $this->textDomain); ?></option>
                                <option value="photo" <?php selected(true, in_array('photo', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Photo', $this->textDomain); ?></option>
                                <option value="politics" <?php selected(true, in_array('politics', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Politics', $this->textDomain); ?></option>
                                <option value="productivity" <?php selected(true, in_array('productivity', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Productivity', $this->textDomain); ?></option>
                                <option value="security" <?php selected(true, in_array('security', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Security', $this->textDomain); ?></option>
                                <option value="shopping" <?php selected(true, in_array('shopping', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Shopping', $this->textDomain); ?></option>
                                <option value="social" <?php selected(true, in_array('social', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Social', $this->textDomain); ?></option>
                                <option value="sports" <?php selected(true, in_array('sports', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Sports', $this->textDomain); ?></option>
                                <option value="travel" <?php selected(true, in_array('travel', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Travel', $this->textDomain); ?></option>
                                <option value="utilities" <?php selected(true, in_array('utilities', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Utilities', $this->textDomain); ?></option>
                                <option value="weather" <?php selected(true, in_array('weather', (array)daftplugInstantify::getSetting('pwaCategories'))); ?>><?php esc_html_e('Weather', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php _e('IARC Rating ID is a unique code that represents the <a class="daftplugAdminLink" href="https://www.globalratings.com/for-developers.aspx" target="_blank">International Age Rating Coalition (IARC)</a> certification code of your web application. It is intended to be used to determine which ages the web application is appropriate for. An IARC certificate can be obtained via participating storefronts, intended to be used for distributing the web app.', $this->textDomain); ?></p>
                        <label for="pwaIarcRatingId" class="daftplugAdminField_label -flex4"><?php esc_html_e('IARC Rating ID', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputText -flexAuto">
                            <input type="text" name="pwaIarcRatingId" id="pwaIarcRatingId" class="daftplugAdminInputText_field" value="<?php echo daftplugInstantify::getSetting('pwaIarcRatingId'); ?>" data-placeholder="<?php esc_html_e('IARC Rating ID', $this->textDomain); ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php _e('Related application option gives you the ability to let users quickly and seamlessly install your native app on their device directly from the app store, without leaving the browser, and without showing an annoying interstitial. So if you will relate your native application to your PWA, browser will prompt the user with your native app instead of the PWA web app. If you don\'t have a native application for your web app, you can <span class="daftplugAdminButton -miniGenerateApp" data-open-popup="generateAppModal">Get Android App</span> now.', $this->textDomain); ?></p>
                        <label for="pwaRelatedApplication" class="daftplugAdminField_label -flex4"><?php esc_html_e('Related Applications', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputAddField -flexAuto">
                            <span class="daftplugAdminButton -addField" data-add="pwaRelatedApplication"><?php esc_html_e('+ Add Related Application', $this->textDomain); ?></span>
                        </div>
                    </div>
                    <?php for ($ra = 1; $ra <= 3; $ra++) { ?>
                    <fieldset class="daftplugAdminFieldset -miniFieldset -pwaRelatedApplication<?php echo $ra; ?>">
                        <h5 class="daftplugAdminFieldset_title"><?php printf(__('Related Application %s', $this->textDomain), $ra); ?></h5>
	                    <label class="daftplugAdminInputCheckbox -flexAuto -hidden">
							<input type="checkbox" name="pwaRelatedApplication<?php echo $ra; ?>" id="pwaRelatedApplication<?php echo $ra; ?>" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting(sprintf('pwaRelatedApplication%s', $ra)), 'on'); ?>>
						</label>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Example: Android', $this->textDomain); ?></p>
                            <label for="pwaRelatedApplication<?php echo $ra; ?>Platform" class="daftplugAdminField_label -flex4"><?php esc_html_e('Platform', $this->textDomain); ?></label>
                            <div class="daftplugAdminInputSelect -flexAuto">
                                <select name="pwaRelatedApplication<?php echo $ra; ?>Platform" id="pwaRelatedApplication<?php echo $ra; ?>Platform" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Platform', $this->textDomain); ?>" autocomplete="off" required>
                                    <option value="play" <?php selected(daftplugInstantify::getSetting(sprintf('pwaRelatedApplication%sPlatform', $ra)), 'play') ?>><?php esc_html_e('Android', $this->textDomain); ?></option>
                                    <option value="itunes" <?php selected(daftplugInstantify::getSetting(sprintf('pwaRelatedApplication%sPlatform', $ra)), 'itunes') ?>><?php esc_html_e('iOS', $this->textDomain); ?></option>
                                    <option value="windows" <?php selected(daftplugInstantify::getSetting(sprintf('pwaRelatedApplication%sPlatform', $ra)), 'windows') ?>><?php esc_html_e('Windows', $this->textDomain); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Example: https://play.google.com/store/apps/details?id=sdm.apps.twademo', $this->textDomain); ?></p>
                            <label for="pwaRelatedApplication<?php echo $ra; ?>Url" class="daftplugAdminField_label -flex4"><?php esc_html_e('URL', $this->textDomain); ?></label>
                            <div class="daftplugAdminInputText -flexAuto">
                                <input type="url" name="pwaRelatedApplication<?php echo $ra; ?>Url" id="pwaRelatedApplication<?php echo $ra; ?>Url" class="daftplugAdminInputText_field" value="<?php echo daftplugInstantify::getSetting(sprintf('pwaRelatedApplication%sUrl', $ra)); ?>" data-placeholder="<?php esc_html_e('URL', $this->textDomain); ?>" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Example: sdm.apps.twademo', $this->textDomain); ?></p>
                            <label for="pwaRelatedApplication<?php echo $ra; ?>Id" class="daftplugAdminField_label -flex4"><?php esc_html_e('ID', $this->textDomain); ?></label>
                            <div class="daftplugAdminInputText -flexAuto">
                                <input type="text" name="pwaRelatedApplication<?php echo $ra; ?>Id" id="pwaRelatedApplication<?php echo $ra; ?>Id" class="daftplugAdminInputText_field" value="<?php echo daftplugInstantify::getSetting(sprintf('pwaRelatedApplication%sId', $ra)); ?>" data-placeholder="<?php esc_html_e('ID', $this->textDomain); ?>" autocomplete="off" required>
                            </div>
                        </div>
                    </fieldset>
                    <?php } ?>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('App shortcuts help users quickly start common or recommended tasks within your web app. Easy access to those tasks from anywhere the app icon is displayed will enhance users productivity as well as increase their engagement with the web app. The app shortcuts menu is invoked by right-clicking the app icon in the taskbar (Windows) or dock (macOS) on the user\'s desktop, or long pressing the app\'s launcher icon on Android.', $this->textDomain); ?></p>
                        <label for="pwaAppShortcut" class="daftplugAdminField_label -flex4"><?php esc_html_e('App Shortcuts', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputAddField -flexAuto">
                            <span class="daftplugAdminButton -addField" data-add="pwaAppShortcut"><?php esc_html_e('+ Add App Shortcut', $this->textDomain); ?></span>
                        </div>
                    </div>
                    <?php for ($as = 1; $as <= 4; $as++) { ?>
                    <fieldset class="daftplugAdminFieldset -miniFieldset -pwaAppShortcut<?php echo $as; ?>">
                        <h5 class="daftplugAdminFieldset_title"><?php printf(__('App Shortcut %s', $this->textDomain), $as); ?></h5>
	                    <label class="daftplugAdminInputCheckbox -flexAuto -hidden">
							<input type="checkbox" name="pwaAppShortcut<?php echo $as; ?>" id="pwaAppShortcut<?php echo $as; ?>" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting(sprintf('pwaAppShortcut%s', $as)), 'on'); ?>>
						</label>
                        <div class="daftplugAdminField">
                            <div class="daftplugAdminInputText -flexAuto">
                                <input type="text" name="pwaAppShortcut<?php echo $as; ?>Name" id="pwaAppShortcut<?php echo $as; ?>Name" class="daftplugAdminInputText_field" value="<?php echo daftplugInstantify::getSetting(sprintf('pwaAppShortcut%sName', $as)); ?>" data-placeholder="<?php esc_html_e('Name', $this->textDomain); ?>" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <div class="daftplugAdminInputText -flexAuto">
                                <input type="url" name="pwaAppShortcut<?php echo $as; ?>Url" id="pwaAppShortcut<?php echo $as; ?>Url" class="daftplugAdminInputText_field" value="<?php echo daftplugInstantify::getSetting(sprintf('pwaAppShortcut%sUrl', $as)); ?>" data-placeholder="<?php esc_html_e('URL', $this->textDomain); ?>" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <div class="daftplugAdminInputUpload -flexAuto">
                                <input type="text" name="pwaAppShortcut<?php echo $as; ?>Icon" id="pwaAppShortcut<?php echo $as; ?>Icon" class="daftplugAdminInputUpload_field" value="<?php echo daftplugInstantify::getSetting(sprintf('pwaAppShortcut%sIcon', $as)); ?>" data-mimes="png" data-min-width="192" data-max-width="" data-min-height="192" data-max-height="" data-attach-url="<?php echo wp_get_attachment_image_src(daftplugInstantify::getSetting(sprintf('pwaAppShortcut%sIcon', $as)), array(192, 192))[0]; ?>">
                            </div>
                        </div>
                    </fieldset>
                    <?php } ?>
                </fieldset>
                <fieldset class="daftplugAdminFieldset">
                    <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Installation Overlays', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php esc_html_e('Display an "Add to Home Screen" overlays for major mobile browsers to make your website installable and grant a prominent place on the user\'s home screen, right next to the native apps.', $this->textDomain); ?></p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable installation overlays.', $this->textDomain); ?></p>
                        <label for="pwaOverlays" class="daftplugAdminField_label -flex4"><?php esc_html_e('Installation Overlays', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaOverlays" id="pwaOverlays" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaOverlays'), 'on'); ?>>
                        </label>
                    </div>
                    <div class="daftplugAdminField -pwaOverlaysDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the supported web browsers for installation overlays. We recommend to choose all the browsers available.', $this->textDomain); ?></p>
                        <label for="pwaOverlaysBrowsers" class="daftplugAdminField_label -flex4"><?php esc_html_e('Supported Browsers', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select multiple name="pwaOverlaysBrowsers" id="pwaOverlaysBrowsers" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Supported Browsers', $this->textDomain); ?>" autocomplete="off" required>
                                <option value="chrome" <?php selected(true, in_array('chrome', (array)daftplugInstantify::getSetting('pwaOverlaysBrowsers'))); ?>><?php esc_html_e('Google Chrome', $this->textDomain); ?></option>
                                <option value="firefox" <?php selected(true, in_array('firefox', (array)daftplugInstantify::getSetting('pwaOverlaysBrowsers'))); ?>><?php esc_html_e('Mozilla Firefox', $this->textDomain); ?></option>
                                <option value="opera" <?php selected(true, in_array('opera', (array)daftplugInstantify::getSetting('pwaOverlaysBrowsers'))); ?>><?php esc_html_e('Opera', $this->textDomain); ?></option>
                                <option value="safari" <?php selected(true, in_array('safari', (array)daftplugInstantify::getSetting('pwaOverlaysBrowsers'))); ?>><?php esc_html_e('Apple Safari', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaOverlaysDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the installation banner types for your overlays. Try not to enable them all to avoid spamming users with the installation banners.', $this->textDomain); ?></p>
                        <label for="pwaOverlaysTypes" class="daftplugAdminField_label -flex4"><?php esc_html_e('Overlay Types', $this->textDomain); ?></label>
                        <div class="-flexAuto" style="display: inline-table; margin-bottom: -30px;">
                            <label class="daftplugAdminInputCheckbox -flex4 -imgcustom" data-title="<?php esc_html_e('Fullscreen', $this->textDomain); ?>" data-img="<?php echo plugins_url('pwa/admin/assets/img/icon-overlay-fullscreen.png', $this->pluginFile); ?>" data-edit="<?php esc_html_e('disabled', $this->textDomain); ?>">
                                <input type="checkbox" name="pwaOverlaysTypeFullscreen" id="pwaOverlaysTypeFullscreen" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaOverlaysTypeFullscreen'), 'on'); ?>>
                            </label>
                            <label class="daftplugAdminInputCheckbox -flex4 -imgcustom" data-title="<?php esc_html_e('Header Banner', $this->textDomain); ?>" data-img="<?php echo plugins_url('pwa/admin/assets/img/icon-overlay-header.png', $this->pluginFile); ?>" data-edit="<?php esc_html_e('Edit', $this->textDomain); ?>">
                                <input type="checkbox" name="pwaOverlaysTypeHeader" id="pwaOverlaysTypeHeader" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaOverlaysTypeHeader'), 'on'); ?>>
                            </label>
                            <label class="daftplugAdminInputCheckbox -flex4 -imgcustom" data-title="<?php esc_html_e('Snackbar', $this->textDomain); ?>" data-img="<?php echo plugins_url('pwa/admin/assets/img/icon-overlay-snackbar.png', $this->pluginFile); ?>" data-edit="<?php esc_html_e('Edit', $this->textDomain); ?>">
                                <input type="checkbox" name="pwaOverlaysTypeSnackbar" id="pwaOverlaysTypeSnackbar" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaOverlaysTypeSnackbar'), 'on'); ?>>
                            </label>
                            <label class="daftplugAdminInputCheckbox -flex4 -imgcustom" data-title="<?php esc_html_e('Navigation Menu', $this->textDomain); ?>" data-img="<?php echo plugins_url('pwa/admin/assets/img/icon-overlay-menu.png', $this->pluginFile); ?>" data-edit="<?php esc_html_e('Edit', $this->textDomain); ?>">
                                <input type="checkbox" name="pwaOverlaysTypeMenu" id="pwaOverlaysTypeMenu" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaOverlaysTypeMenu'), 'on'); ?>>
                            </label>
                            <label class="daftplugAdminInputCheckbox -flex4 -imgcustom" data-title="<?php esc_html_e('In Feed', $this->textDomain); ?>" data-img="<?php echo plugins_url('pwa/admin/assets/img/icon-overlay-feed.png', $this->pluginFile); ?>" data-edit="<?php esc_html_e('Edit', $this->textDomain); ?>">
                                <input type="checkbox" name="pwaOverlaysTypeFeed" id="pwaOverlaysTypeFeed" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaOverlaysTypeFeed'), 'on'); ?>>
                            </label>
                            <label class="daftplugAdminInputCheckbox -flex4 -imgcustom" data-title="<?php esc_html_e('Post Popup', $this->textDomain); ?>" data-img="<?php echo plugins_url('pwa/admin/assets/img/icon-overlay-post.png', $this->pluginFile); ?>" data-edit="<?php esc_html_e('disabled', $this->textDomain); ?>">
                                <input type="checkbox" name="pwaOverlaysTypePost" id="pwaOverlaysTypePost" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaOverlaysTypePost'), 'on'); ?>>
                            </label>
                            <?php if (daftplugInstantify::isWoocommerceActive()) { ?>
                            <label class="daftplugAdminInputCheckbox -flex4 -imgcustom" data-title="<?php esc_html_e('WooCommerce Checkout', $this->textDomain); ?>" data-img="<?php echo plugins_url('pwa/admin/assets/img/icon-overlay-checkout.png', $this->pluginFile); ?>" data-edit="<?php esc_html_e('Edit', $this->textDomain); ?>">
                                <input type="checkbox" name="pwaOverlaysTypeCheckout" id="pwaOverlaysTypeCheckout" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaOverlaysTypeCheckout'), 'on'); ?>>
                            </label>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('If enabled, users who are visiting the website for the first time will not get the installation overlays.', $this->textDomain); ?></p>
                        <label for="pwaOverlaysSkip" class="daftplugAdminField_label -flex4"><?php esc_html_e('Skip First Visit', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaOverlaysSkip" id="pwaOverlaysSkip" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaOverlaysSkip'), 'on'); ?>>
                        </label>
                    </div>
                    <div class="daftplugAdminField -pwaOverlaysDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Choose how many seconds to wait after page loads to show installation overlays.', $this->textDomain); ?></p>
                        <label for="pwaOverlaysDelay" class="daftplugAdminField_label -flex4"><?php esc_html_e('Delay', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputNumber -flexAuto">
                            <input type="number" name="pwaOverlaysDelay" id="pwaOverlaysDelay" class="daftplugAdminInputNumber_field" value="<?php echo daftplugInstantify::getSetting('pwaOverlaysDelay'); ?>" min="1" step="1" max="500" data-placeholder="<?php esc_html_e('Delay', $this->textDomain); ?>" required>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaOverlaysDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Choose how many days to wait to show installation overlays again if they were dismissed.', $this->textDomain); ?></p>
                        <label for="pwaOverlaysShowAgain" class="daftplugAdminField_label -flex4"><?php esc_html_e('Timeout', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputNumber -flexAuto">
                            <input type="number" name="pwaOverlaysShowAgain" id="pwaOverlaysShowAgain" class="daftplugAdminInputNumber_field" value="<?php echo daftplugInstantify::getSetting('pwaOverlaysShowAgain'); ?>" min="1" step="1" max="60" data-placeholder="<?php esc_html_e('Timeout', $this->textDomain); ?>" required>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="daftplugAdminFieldset">
                    <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Installation Button', $this->textDomain); ?></h4>
                    <p class="daftplugAdminFieldset_description"><?php esc_html_e('You are able to insert an installation button anywhere on your website using the shortcode.', $this->textDomain); ?></p>
                    <div class="daftplugAdminField">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Enable or disable installation button.', $this->textDomain); ?></p>
                        <label for="pwaInstallButton" class="daftplugAdminField_label -flex4"><?php esc_html_e('Installation Button', $this->textDomain); ?></label>
                        <label class="daftplugAdminInputCheckbox -flexAuto">
                            <input type="checkbox" name="pwaInstallButton" id="pwaInstallButton" class="daftplugAdminInputCheckbox_field" <?php checked(daftplugInstantify::getSetting('pwaInstallButton'), 'on'); ?>>
                        </label>
                    </div>
                    <div class="daftplugAdminField -pwaInstallButtonDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('You can insert an installation button anywhere on your website using the shortcode below.', $this->textDomain); ?></p>
                        <label for="pwaInstallButtonShortcode" class="daftplugAdminField_label -flex4"><?php esc_html_e('Button Shortcode', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputText -flexAuto">
                            <input type="text" name="pwaInstallButtonShortcode" id="pwaInstallButtonShortcode" class="daftplugAdminInputText_field" value="<?php echo daftplugInstantify::getSetting('pwaInstallButtonShortcode'); ?>" data-placeholder="<?php esc_html_e('Button Shortcode', $this->textDomain); ?>" readonly disabled>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaInstallButtonDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the supported web browsers for installation button. We recommend to choose all the browsers available.', $this->textDomain); ?></p>
                        <label for="pwaInstallButtonBrowsers" class="daftplugAdminField_label -flex4"><?php esc_html_e('Supported Browsers', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputSelect -flexAuto">
                            <select multiple name="pwaInstallButtonBrowsers" id="pwaInstallButtonBrowsers" class="daftplugAdminInputSelect_field" data-placeholder="<?php esc_html_e('Supported Browsers', $this->textDomain); ?>" autocomplete="off" required>
                                <option value="chrome" <?php selected(true, in_array('chrome', (array)daftplugInstantify::getSetting('pwaInstallButtonBrowsers'))); ?>><?php esc_html_e('Google Chrome', $this->textDomain); ?></option>
                                <option value="firefox" <?php selected(true, in_array('firefox', (array)daftplugInstantify::getSetting('pwaInstallButtonBrowsers'))); ?>><?php esc_html_e('Mozilla Firefox', $this->textDomain); ?></option>
                                <option value="opera" <?php selected(true, in_array('opera', (array)daftplugInstantify::getSetting('pwaInstallButtonBrowsers'))); ?>><?php esc_html_e('Opera', $this->textDomain); ?></option>
                                <option value="safari" <?php selected(true, in_array('safari', (array)daftplugInstantify::getSetting('pwaInstallButtonBrowsers'))); ?>><?php esc_html_e('Apple Safari', $this->textDomain); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaInstallButtonDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Insert your installation text which will be displayed on the button.', $this->textDomain); ?></p>
                        <label for="pwaInstallButtonText" class="daftplugAdminField_label -flex4"><?php esc_html_e('Button Text', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputText -flexAuto">
                            <input type="text" name="pwaInstallButtonText" id="pwaInstallButtonText" class="daftplugAdminInputText_field" value="<?php echo daftplugInstantify::getSetting('pwaInstallButtonText'); ?>" data-placeholder="<?php esc_html_e('Button Text', $this->textDomain); ?>" required>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaInstallButtonDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the background color of installation button.', $this->textDomain); ?></p>
                        <label for="pwaInstallButtonBackgroundColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Background Color', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputColor -flexAuto">
                            <input type="text" name="pwaInstallButtonBackgroundColor" id="pwaInstallButtonBackgroundColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaInstallButtonBackgroundColor'); ?>" data-placeholder="<?php esc_html_e('Background Color', $this->textDomain); ?>" required>
                        </div>
                    </div>
                    <div class="daftplugAdminField -pwaInstallButtonDependentDisableD">
                        <p class="daftplugAdminField_description"><?php esc_html_e('Select the text color of installation button.', $this->textDomain); ?></p>
                        <label for="pwaInstallButtonTextColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Text Color', $this->textDomain); ?></label>
                        <div class="daftplugAdminInputColor -flexAuto">
                            <input type="text" name="pwaInstallButtonTextColor" id="pwaInstallButtonTextColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaInstallButtonTextColor'); ?>" data-placeholder="<?php esc_html_e('Text Color', $this->textDomain); ?>" required>
                        </div>
                    </div>
                </fieldset>
                <div class="daftplugAdminSettings_submit">
                    <button type="submit" class="daftplugAdminButton -submit" data-submit="<?php esc_html_e('Save Settings', $this->textDomain); ?>" data-waiting="<?php esc_html_e('Waiting', $this->textDomain); ?>" data-submitted="<?php esc_html_e('Settings Saved', $this->textDomain); ?>" data-failed="<?php esc_html_e('Saving Failed', $this->textDomain); ?>"></button>
                </div>
            </form>
        </div>
        <div class="daftplugAdminPopup" data-popup="pwaOverlaysTypeHeader">
            <div class="daftplugAdminPopup_container">
                <form name="daftplugAdminSettings_form" class="daftplugAdminSettings_form" data-nonce="<?php echo wp_create_nonce("{$this->optionName}_settings_nonce"); ?>" spellcheck="false" autocomplete="off">
                    <fieldset class="daftplugAdminFieldset">
                        <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Edit Header Banner Overlay', $this->textDomain); ?></h4>
                        <div class="daftplugAdminField" style="justify-content: center;">
                            <img src="<?php echo plugins_url('pwa/admin/assets/img/image-overlay-header.png', $this->pluginFile); ?>"/>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Enter the message displayed on the header banner overlay.', $this->textDomain); ?></p>
                            <label for="pwaOverlaysTypeHeaderMessage" class="daftplugAdminField_label -flex4"><?php esc_html_e('Message', $this->textDomain); ?></label>
                            <div class="daftplugAdminInputTextarea -flexAuto">
                                <textarea name="pwaOverlaysTypeHeaderMessage" id="pwaOverlaysTypeHeaderMessage" class="daftplugAdminInputTextarea_field" data-placeholder="<?php esc_html_e('Message', $this->textDomain); ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" rows="4" required><?php echo daftplugInstantify::getSetting('pwaOverlaysTypeHeaderMessage'); ?></textarea>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Select the background color of header banner overlay.', $this->textDomain); ?></p>
                            <label for="pwaOverlaysTypeHeaderBackgroundColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Background Color', $this->textDomain); ?></label>
                            <div class="daftplugAdminInputColor -flexAuto">
                                <input type="text" name="pwaOverlaysTypeHeaderBackgroundColor" id="pwaOverlaysTypeHeaderBackgroundColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaOverlaysTypeHeaderBackgroundColor'); ?>" data-placeholder="<?php esc_html_e('Background Color', $this->textDomain); ?>" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Select the text color of header banner overlay.', $this->textDomain); ?></p>
                            <label for="pwaOverlaysTypeHeaderTextColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Text Color', $this->textDomain); ?></label>
                            <div class="daftplugAdminInputColor -flexAuto">
                                <input type="text" name="pwaOverlaysTypeHeaderTextColor" id="pwaOverlaysTypeHeaderTextColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaOverlaysTypeHeaderTextColor'); ?>" data-placeholder="<?php esc_html_e('Text Color', $this->textDomain); ?>" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <div class="daftplugAdminField_response -flex7"></div>
                            <div class="daftplugAdminField_submit -flex5">
                                <button type="submit" class="daftplugAdminButton -submit" data-submit="<?php esc_html_e('Edit Overlay', $this->textDomain); ?>" data-waiting="<?php esc_html_e('Editing', $this->textDomain); ?>" data-submitted="<?php esc_html_e('Overlay Edited', $this->textDomain); ?>" data-failed="<?php esc_html_e('Editing Failed', $this->textDomain); ?>"></button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>       
        </div>
        <div class="daftplugAdminPopup" data-popup="pwaOverlaysTypeSnackbar">
            <div class="daftplugAdminPopup_container">
                <form name="daftplugAdminSettings_form" class="daftplugAdminSettings_form" data-nonce="<?php echo wp_create_nonce("{$this->optionName}_settings_nonce"); ?>" spellcheck="false" autocomplete="off">
                    <fieldset class="daftplugAdminFieldset">
                        <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Edit Snackbar Overlay', $this->textDomain); ?></h4>
                        <div class="daftplugAdminField" style="justify-content: center;">
                            <img src="<?php echo plugins_url('pwa/admin/assets/img/image-overlay-snackbar.png', $this->pluginFile); ?>"/>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Enter the message displayed on the snackbar overlay.', $this->textDomain); ?></p>
                            <label for="pwaOverlaysTypeSnackbarMessage" class="daftplugAdminField_label -flex4"><?php esc_html_e('Message', $this->textDomain); ?></label>
                            <div class="daftplugAdminInputTextarea -flexAuto">
                                <textarea name="pwaOverlaysTypeSnackbarMessage" id="pwaOverlaysTypeSnackbarMessage" class="daftplugAdminInputTextarea_field" data-placeholder="<?php esc_html_e('Message', $this->textDomain); ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" rows="4" required><?php echo daftplugInstantify::getSetting('pwaOverlaysTypeSnackbarMessage'); ?></textarea>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Select the background color of snackbar overlay.', $this->textDomain); ?></p>
                            <label for="pwaOverlaysTypeSnackbarBackgroundColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Background Color', $this->textDomain); ?></label>
                            <div class="daftplugAdminInputColor -flexAuto">
                                <input type="text" name="pwaOverlaysTypeSnackbarBackgroundColor" id="pwaOverlaysTypeSnackbarBackgroundColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaOverlaysTypeSnackbarBackgroundColor'); ?>" data-placeholder="<?php esc_html_e('Background Color', $this->textDomain); ?>" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Select the text color of snackbar overlay.', $this->textDomain); ?></p>
                            <label for="pwaOverlaysTypeSnackbarTextColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Text Color', $this->textDomain); ?></label>
                            <div class="daftplugAdminInputColor -flexAuto">
                                <input type="text" name="pwaOverlaysTypeSnackbarTextColor" id="pwaOverlaysTypeSnackbarTextColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaOverlaysTypeSnackbarTextColor'); ?>" data-placeholder="<?php esc_html_e('Text Color', $this->textDomain); ?>" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <div class="daftplugAdminField_response -flex7"></div>
                            <div class="daftplugAdminField_submit -flex5">
                                <button type="submit" class="daftplugAdminButton -submit" data-submit="<?php esc_html_e('Edit Overlay', $this->textDomain); ?>" data-waiting="<?php esc_html_e('Editing', $this->textDomain); ?>" data-submitted="<?php esc_html_e('Overlay Edited', $this->textDomain); ?>" data-failed="<?php esc_html_e('Editing Failed', $this->textDomain); ?>"></button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>       
        </div>
        <div class="daftplugAdminPopup" data-popup="pwaOverlaysTypeMenu">
            <div class="daftplugAdminPopup_container">
                <form name="daftplugAdminSettings_form" class="daftplugAdminSettings_form" data-nonce="<?php echo wp_create_nonce("{$this->optionName}_settings_nonce"); ?>" spellcheck="false" autocomplete="off">
                    <fieldset class="daftplugAdminFieldset">
                        <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Edit Navigation Menu Overlay', $this->textDomain); ?></h4>
                        <div class="daftplugAdminField" style="justify-content: center;">
                            <img src="<?php echo plugins_url('pwa/admin/assets/img/image-overlay-menu.png', $this->pluginFile); ?>"/>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Enter the message displayed on the navigation menu overlay.', $this->textDomain); ?></p>
                            <label for="pwaOverlaysTypeMenuMessage" class="daftplugAdminField_label -flex4"><?php esc_html_e('Message', $this->textDomain); ?></label>
                            <div class="daftplugAdminInputTextarea -flexAuto">
                                <textarea name="pwaOverlaysTypeMenuMessage" id="pwaOverlaysTypeMenuMessage" class="daftplugAdminInputTextarea_field" data-placeholder="<?php esc_html_e('Message', $this->textDomain); ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" rows="4" required><?php echo daftplugInstantify::getSetting('pwaOverlaysTypeMenuMessage'); ?></textarea>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Select the background color of navigation menu overlay.', $this->textDomain); ?></p>
                            <label for="pwaOverlaysTypeMenuBackgroundColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Background Color', $this->textDomain); ?></label>
                            <div class="daftplugAdminInputColor -flexAuto">
                                <input type="text" name="pwaOverlaysTypeMenuBackgroundColor" id="pwaOverlaysTypeMenuBackgroundColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaOverlaysTypeMenuBackgroundColor'); ?>" data-placeholder="<?php esc_html_e('Background Color', $this->textDomain); ?>" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Select the text color of navigation menu overlay.', $this->textDomain); ?></p>
                            <label for="pwaOverlaysTypeMenuTextColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Text Color', $this->textDomain); ?></label>
                            <div class="daftplugAdminInputColor -flexAuto">
                                <input type="text" name="pwaOverlaysTypeMenuTextColor" id="pwaOverlaysTypeMenuTextColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaOverlaysTypeMenuTextColor'); ?>" data-placeholder="<?php esc_html_e('Text Color', $this->textDomain); ?>" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <div class="daftplugAdminField_response -flex7"></div>
                            <div class="daftplugAdminField_submit -flex5">
                                <button type="submit" class="daftplugAdminButton -submit" data-submit="<?php esc_html_e('Edit Overlay', $this->textDomain); ?>" data-waiting="<?php esc_html_e('Editing', $this->textDomain); ?>" data-submitted="<?php esc_html_e('Overlay Edited', $this->textDomain); ?>" data-failed="<?php esc_html_e('Editing Failed', $this->textDomain); ?>"></button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>       
        </div>
        <div class="daftplugAdminPopup" data-popup="pwaOverlaysTypeFeed">
            <div class="daftplugAdminPopup_container">
                <form name="daftplugAdminSettings_form" class="daftplugAdminSettings_form" data-nonce="<?php echo wp_create_nonce("{$this->optionName}_settings_nonce"); ?>" spellcheck="false" autocomplete="off">
                    <fieldset class="daftplugAdminFieldset">
                        <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Edit In Feed Overlay', $this->textDomain); ?></h4>
                        <div class="daftplugAdminField" style="justify-content: center;">
                            <img src="<?php echo plugins_url('pwa/admin/assets/img/image-overlay-feed.png', $this->pluginFile); ?>"/>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Enter the message displayed on the in feed overlay.', $this->textDomain); ?></p>
                            <label for="pwaOverlaysTypeFeedMessage" class="daftplugAdminField_label -flex4"><?php esc_html_e('Message', $this->textDomain); ?></label>
                            <div class="daftplugAdminInputTextarea -flexAuto">
                                <textarea name="pwaOverlaysTypeFeedMessage" id="pwaOverlaysTypeFeedMessage" class="daftplugAdminInputTextarea_field" data-placeholder="<?php esc_html_e('Message', $this->textDomain); ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" rows="4" required><?php echo daftplugInstantify::getSetting('pwaOverlaysTypeFeedMessage'); ?></textarea>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Select the background color of in feed overlay.', $this->textDomain); ?></p>
                            <label for="pwaOverlaysTypeFeedBackgroundColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Background Color', $this->textDomain); ?></label>
                            <div class="daftplugAdminInputColor -flexAuto">
                                <input type="text" name="pwaOverlaysTypeFeedBackgroundColor" id="pwaOverlaysTypeFeedBackgroundColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaOverlaysTypeFeedBackgroundColor'); ?>" data-placeholder="<?php esc_html_e('Background Color', $this->textDomain); ?>" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Select the text color of in feed overlay.', $this->textDomain); ?></p>
                            <label for="pwaOverlaysTypeFeedTextColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Text Color', $this->textDomain); ?></label>
                            <div class="daftplugAdminInputColor -flexAuto">
                                <input type="text" name="pwaOverlaysTypeFeedTextColor" id="pwaOverlaysTypeFeedTextColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaOverlaysTypeFeedTextColor'); ?>" data-placeholder="<?php esc_html_e('Text Color', $this->textDomain); ?>" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <div class="daftplugAdminField_response -flex7"></div>
                            <div class="daftplugAdminField_submit -flex5">
                                <button type="submit" class="daftplugAdminButton -submit" data-submit="<?php esc_html_e('Edit Overlay', $this->textDomain); ?>" data-waiting="<?php esc_html_e('Editing', $this->textDomain); ?>" data-submitted="<?php esc_html_e('Overlay Edited', $this->textDomain); ?>" data-failed="<?php esc_html_e('Editing Failed', $this->textDomain); ?>"></button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>       
        </div>
        <?php if (daftplugInstantify::isWoocommerceActive()) { ?>
        <div class="daftplugAdminPopup" data-popup="pwaOverlaysTypeCheckout">
            <div class="daftplugAdminPopup_container">
                <form name="daftplugAdminSettings_form" class="daftplugAdminSettings_form" data-nonce="<?php echo wp_create_nonce("{$this->optionName}_settings_nonce"); ?>" spellcheck="false" autocomplete="off">
                    <fieldset class="daftplugAdminFieldset">
                        <h4 class="daftplugAdminFieldset_title"><?php esc_html_e('Edit Checkout Overlay', $this->textDomain); ?></h4>
                        <div class="daftplugAdminField" style="justify-content: center;">
                            <img src="<?php echo plugins_url('pwa/admin/assets/img/image-overlay-checkout.png', $this->pluginFile); ?>"/>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Enter the message displayed on the checkout overlay.', $this->textDomain); ?></p>
                            <label for="pwaOverlaysTypeCheckoutMessage" class="daftplugAdminField_label -flex4"><?php esc_html_e('Message', $this->textDomain); ?></label>
                            <div class="daftplugAdminInputTextarea -flexAuto">
                                <textarea name="pwaOverlaysTypeCheckoutMessage" id="pwaOverlaysTypeCheckoutMessage" class="daftplugAdminInputTextarea_field" data-placeholder="<?php esc_html_e('Message', $this->textDomain); ?>" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" rows="4" required><?php echo daftplugInstantify::getSetting('pwaOverlaysTypeCheckoutMessage'); ?></textarea>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Select the background color of checkout overlay.', $this->textDomain); ?></p>
                            <label for="pwaOverlaysTypeCheckoutBackgroundColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Background Color', $this->textDomain); ?></label>
                            <div class="daftplugAdminInputColor -flexAuto">
                                <input type="text" name="pwaOverlaysTypeCheckoutBackgroundColor" id="pwaOverlaysTypeCheckoutBackgroundColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaOverlaysTypeCheckoutBackgroundColor'); ?>" data-placeholder="<?php esc_html_e('Background Color', $this->textDomain); ?>" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <p class="daftplugAdminField_description"><?php esc_html_e('Select the text color of checkout overlay.', $this->textDomain); ?></p>
                            <label for="pwaOverlaysTypeCheckoutTextColor" class="daftplugAdminField_label -flex4"><?php esc_html_e('Text Color', $this->textDomain); ?></label>
                            <div class="daftplugAdminInputColor -flexAuto">
                                <input type="text" name="pwaOverlaysTypeCheckoutTextColor" id="pwaOverlaysTypeCheckoutTextColor" class="daftplugAdminInputColor_field" value="<?php echo daftplugInstantify::getSetting('pwaOverlaysTypeCheckoutTextColor'); ?>" data-placeholder="<?php esc_html_e('Text Color', $this->textDomain); ?>" required>
                            </div>
                        </div>
                        <div class="daftplugAdminField">
                            <div class="daftplugAdminField_response -flex7"></div>
                            <div class="daftplugAdminField_submit -flex5">
                                <button type="submit" class="daftplugAdminButton -submit" data-submit="<?php esc_html_e('Edit Overlay', $this->textDomain); ?>" data-waiting="<?php esc_html_e('Editing', $this->textDomain); ?>" data-submitted="<?php esc_html_e('Overlay Edited', $this->textDomain); ?>" data-failed="<?php esc_html_e('Editing Failed', $this->textDomain); ?>"></button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>       
        </div>
        <?php } ?>
    </div>
</div>