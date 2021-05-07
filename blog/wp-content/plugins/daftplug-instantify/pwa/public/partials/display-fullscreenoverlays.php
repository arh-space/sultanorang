<?php

if (!defined('ABSPATH')) exit;

if (daftplugInstantify::getSetting('pwaDynamicManifest')  == 'on' && is_singular()) {
    $appName = get_the_title();
} else {
    $appName = daftplugInstantify::getSetting('pwaName');
}

$appIcon = wp_get_attachment_image_src(daftplugInstantify::getSetting('pwaIcon'), array(150, 150))[0];
$cib = esc_html__('Continue in browser', $this->textDomain);
$tit = esc_html__('To install tap', $this->textDomain);
$ac = esc_html__('and choose', $this->textDomain);
$aths = esc_html__('Add to Home Screen', $this->textDomain);

if ((in_array('chrome', (array)daftplugInstantify::getSetting('pwaOverlaysBrowsers')) || daftplugInstantify::getSetting('pwaInstallButton') == 'on') && daftplugInstantify::isPlatform('chrome')) {
	?>
	<div class="daftplugPublicFullscreenOverlay -chrome">
	    <div class="daftplugPublicFullscreenOverlay_close"><?php echo $cib; ?></div>
	    <div class="daftplugPublicFullscreenOverlay_logo" style="background-image:url(<?php echo $appIcon; ?>)"><?php echo $appName; ?></div>
	    <div class="daftplugPublicFullscreenOverlay_text"><?php echo $tit.' '.$aths; ?></div>
	    <div class="daftplugPublicFullscreenOverlay_icon -pointer"></div>
	    <div class="daftplugPublicFullscreenOverlay_button"><?php echo $aths; ?></div>
	</div>
	<?php 
}

if ((in_array('safari', (array)daftplugInstantify::getSetting('pwaOverlaysBrowsers')) || daftplugInstantify::getSetting('pwaInstallButton') == 'on') && daftplugInstantify::isPlatform('safari')) {
	?>
	<div class="daftplugPublicFullscreenOverlay -safari">
        <div class="daftplugPublicFullscreenOverlay_close"><?php echo $cib; ?></div>
        <div class="daftplugPublicFullscreenOverlay_logo" style="background-image:url(<?php echo $appIcon; ?>)"><?php echo $appName; ?></div>
        <div class="daftplugPublicFullscreenOverlay_text">
        	<?php echo $tit; ?>
        	<div class="daftplugPublicFullscreenOverlay_icon -home"></div>
        	<?php echo $ac; ?><br>
        	<?php echo $aths; ?>   
        </div>
        <div class="daftplugPublicFullscreenOverlay_icon -pointer"></div>
    </div>
	<?php
}

if ((in_array('firefox', (array)daftplugInstantify::getSetting('pwaOverlaysBrowsers')) || daftplugInstantify::getSetting('pwaInstallButton') == 'on') && daftplugInstantify::isPlatform('firefox')) {
	?>
    <div class="daftplugPublicFullscreenOverlay -firefox">
        <div class="daftplugPublicFullscreenOverlay_logo" style="background-image:url(<?php echo $appIcon; ?>)"><?php echo $appName; ?></div>
        <div class="daftplugPublicFullscreenOverlay_text">
        	<?php echo $tit; ?>
        	<div class="daftplugPublicFullscreenOverlay_icon -home"></div>
        	<?php echo $ac; ?><br>
        	<?php echo $aths; ?>
        </div>
		<div class="daftplugPublicFullscreenOverlay_close"><?php echo $cib; ?></div>
		<div class="daftplugPublicFullscreenOverlay_icon -pointer"></div>
    </div>
	<?php 
}

if ((in_array('opera', (array)daftplugInstantify::getSetting('pwaOverlaysBrowsers')) || daftplugInstantify::getSetting('pwaInstallButton') == 'on') && daftplugInstantify::isPlatform('opera')) {
	?>
    <div class="daftplugPublicFullscreenOverlay -opera">
        <div class="daftplugPublicFullscreenOverlay_icon -pointer"></div>
        <div class="daftplugPublicFullscreenOverlay_logo" style="background-image:url(<?php echo $appIcon; ?>)"><?php echo $appName; ?></div>
        <div class="daftplugPublicFullscreenOverlay_text">
        	<?php echo $tit; ?>
        	<div class="daftplugPublicFullscreenOverlay_icon -home"></div>
        	<?php echo $ac; ?><br>
        	<?php echo $aths; ?>
        </div>
        <div class="daftplugPublicFullscreenOverlay_close"><?php echo $cib; ?></div>
    </div>
	<?php 
}

?>