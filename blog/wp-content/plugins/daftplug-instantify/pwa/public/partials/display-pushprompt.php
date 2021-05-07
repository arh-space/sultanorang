<?php

if (!defined('ABSPATH')) exit;

$appIcon = wp_get_attachment_image_src(daftplugInstantify::getSetting('pwaIcon'), array(150, 150))[0];
$message = daftplugInstantify::getSetting('pwaPushPromptMessage');
$textColor = daftplugInstantify::getSetting('pwaPushPromptTextColor');
$backgroundColor = daftplugInstantify::getSetting('pwaPushPromptBgColor');
$dismiss = esc_html__('Dismiss', $this->textDomain);
$allow = esc_html__('Allow Notifications', $this->textDomain);

?>

<div class="daftplugPublicPushPrompt" style="background: <?php echo $backgroundColor; ?>;">
    <div class="daftplugPublicPushPrompt_content">
        <img class="daftplugPublicPushPrompt_icon" src="<?php echo $appIcon; ?>">
        <span class="daftplugPublicPushPrompt_msg" style="color: <?php echo $textColor; ?>;"><?php echo $message; ?></span>
    </div>
    <div class="daftplugPublicPushPrompt_buttons">
        <div class="daftplugPublicPushPrompt_dismiss" style="color: <?php echo $textColor; ?>; opacity: 0.7;"><?php echo $dismiss; ?></div>
        <div class="daftplugPublicPushPrompt_allow"><?php echo $allow; ?></div>
    </div>
</div>