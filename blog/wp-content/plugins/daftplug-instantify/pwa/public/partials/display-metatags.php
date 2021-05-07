<?php

if (!defined('ABSPATH')) exit;

$appIcon = preg_replace('/(\.[^.]+)$/', sprintf('%s$1', '-192x192'), wp_get_attachment_image_src(daftplugInstantify::getSetting('pwaIcon'), 'full')[0] ?? '');

?>

<link rel="manifest" crossorigin="use-credentials" href="<?php echo $this->getManifestUrl(false); ?>">
<!-- <script async src="https://unpkg.com/pwacompat" crossorigin="anonymous"></script> -->
<meta name="theme-color" content="<?php echo daftplugInstantify::getSetting('pwaThemeColor'); ?>">
<meta name="mobile-web-app-capable" content="yes">
<meta name="application-name" content="<?php echo daftplugInstantify::getSetting('pwaShortName'); ?>">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-touch-fullscreen" content="yes">
<meta name="apple-mobile-web-app-title" content="<?php echo daftplugInstantify::getSetting('pwaShortName'); ?>">
<meta name="apple-mobile-web-app-status-bar-style" content="<?php echo daftplugInstantify::getSetting('pwaIosStatusBarStyle'); ?>">
<?php if (daftplugInstantify::getSetting('pwaRelatedApplication') == 'on' && daftplugInstantify::getSetting('pwaRelatedApplicationPlatform') == 'itunes') { ?>
<meta name="apple-itunes-app" content="app-id=<?php echo daftplugInstantify::getSetting('pwaRelatedApplicationId'); ?>">
<?php } ?>
<link rel="apple-touch-icon" href="<?php echo $appIcon; ?>">
<?php

if (file_exists($this->pluginUploadDir . 'img-pwa-apple-launch.png')) {
    $devices = array(
        'iPhone X' => array(
            'device-width'               => '375px',
            'device-height'              => '812px',
            '-webkit-device-pixel-ratio' => '3',
            'launch-width'               => '1125',
            'launch-height'              => '2436',
        ),
        
        'iPhone 8, 7, 6, s6' => array(
            'device-width'               => '375px',
            'device-height'              => '667px',
            '-webkit-device-pixel-ratio' => '2',
            'launch-width'               => '750',
            'launch-height'              => '1334',
        ),
        
        'iPhone 8 Plus, 7 Plus, 6s Plus, 6 Plus' => array(
            'device-width'               => '414px',
            'device-height'              => '736px',
            '-webkit-device-pixel-ratio' => '3',
            'launch-width'               => '1242',
            'launch-height'              => '2208',
        ),

        'iPhone 5' => array(
            'device-width'               => '320px',
            'device-height'              => '568px',
            '-webkit-device-pixel-ratio' => '2',
            'launch-width'               => '640',
            'launch-height'              => '1136',
        ),

        'iPad Mini, Air' => array(
            'device-width'               => '768px',
            'device-height'              => '1024px',
            '-webkit-device-pixel-ratio' => '2',
            'launch-width'               => '1536',
            'launch-height'              => '2048',
        ),
        
        'iPad Pro 10.5' => array(
            'device-width'               => '834px',
            'device-height'              => '1112px',
            '-webkit-device-pixel-ratio' => '2',
            'launch-width'               => '1668',
            'launch-height'              => '2224',
        ),

        'iPad Pro 12.9' => array(
            'device-width'               => '1024px',
            'device-height'              => '1366px',
            '-webkit-device-pixel-ratio' => '2',
            'launch-width'               => '2048',
            'launch-height'              => '2732',
        ),
    );

    foreach ($devices as $device) {
        echo '<link rel="apple-touch-startup-image" media="(device-width: '.$device['device-width'].') and (device-height: '.$device['device-height'].') and (-webkit-device-pixel-ratio: '.$device['-webkit-device-pixel-ratio'].')" href="'.$this->pluginUploadUrl.'img-pwa-apple-launch-'.$device['launch-width'].'x'.$device['launch-height'].'.png'.'">';
        }
    }
?>