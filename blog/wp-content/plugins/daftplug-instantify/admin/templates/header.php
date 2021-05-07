<?php

if (!defined('ABSPATH')) exit;

?>
<header class="daftplugAdminHeader">
	<a class="daftplugAdminHeader_logo" href="<?php echo admin_url('admin.php?page=' . $this->slug); ?>">
        <img class="daftplugAdminHeader_img" src="<?php echo plugins_url('admin/assets/img/icon-logo.png', $this->pluginFile); ?>" width="48" height="48" alt="<?php esc_html_e($this->name . ' Plugin', $this->textDomain); ?>">
    </a>
    <span class="daftplugAdminHeader_title"><?php esc_html_e($this->name . ' Plugin', $this->textDomain); ?></span>
    <span class="daftplugAdminHeader_versionText"><?php printf(__('Version %s', $this->textDomain), $this->version); ?></span>
    <div class="daftplugAdminHeader_support">
        <a class="daftplugAdminButton" href="#/support/" data-page="support"><?php esc_html_e('Need Help?', $this->textDomain); ?></a>
    </div>
</header>