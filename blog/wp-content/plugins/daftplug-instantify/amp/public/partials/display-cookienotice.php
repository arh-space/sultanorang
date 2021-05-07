<?php

if (!defined('ABSPATH')) exit;

$message = daftplugInstantify::getSetting('ampCookieNoticeMessage');
$buttonText = daftplugInstantify::getSetting('ampCookieNoticeButtonText');

?>

<amp-user-notification layout="nodisplay" id="daftplugPublicCookieNotice" class="daftplugPublicCookieNotice">
   <p class="daftplugPublicCookieNotice_message"><?php echo $message; ?></p>
   <button class="daftplugPublicCookieNotice_button" on="tap:daftplugPublicCookieNotice.dismiss"><?php esc_html_e($buttonText); ?></button>
</amp-user-notification>