<?php

if (!defined('ABSPATH')) exit;

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

if (!class_exists('daftplugInstantifyPwaPublicPushnotifications')) {
    class daftplugInstantifyPwaPublicPushnotifications {
        public $name;
        public $description;
        public $slug;
        public $version;
        public $textDomain;
        public $optionName;
        public $pluginFile;
        public $pluginBasename;
        public $settings;
        public $vapidKeys;
        public $subscribedDevices;
        public $daftplugInstantifyPwaPublic;

        private static $noteData;

        public function __construct($config, $daftplugInstantifyPwaPublic) {
            $this->name = $config['name'];
            $this->description = $config['description'];
            $this->slug = $config['slug'];
            $this->version = $config['version'];
            $this->textDomain = $config['text_domain'];
            $this->optionName = $config['option_name'];
            $this->pluginFile = $config['plugin_file'];
            $this->pluginBasename = $config['plugin_basename'];
            $this->settings = $config['settings'];
            $this->vapidKeys = get_option("{$this->optionName}_vapid_keys", true);            
            $this->subscribedDevices = get_option("{$this->optionName}_subscribed_devices", true);
            $this->daftplugInstantifyPwaPublic = $daftplugInstantifyPwaPublic;

            add_filter("{$this->optionName}_pwa_serviceworker", array($this, 'addPushToServiceWorker'));
            add_filter("{$this->optionName}_public_js_vars", array($this, 'addPushJsVars'));
            add_action("wp_ajax_{$this->optionName}_handle_subscription", array($this, 'handleSubscription'));
            add_action("wp_ajax_nopriv_{$this->optionName}_handle_subscription", array($this, 'handleSubscription'));

            if (daftplugInstantify::isWoocommerceActive()) {
                if (daftplugInstantify::getSetting('pwaPushWooNewOrder') == 'on') {
                    add_action('woocommerce_new_order', array($this, 'doWooNewOrderPush'));  
                }
                if (daftplugInstantify::getSetting('pwaPushWooLowStock') == 'on') {
                    add_action('woocommerce_thankyou', array($this, 'doWooLowStockPush'));  
                }
            }

            if (daftplugInstantify::isBuddyPressActive()) {
                if (daftplugInstantify::getSetting('pwaPushBpMemberMention') == 'on') {
                    add_action('bp_activity_sent_mention_email', array($this, 'doBpMemberMentionPush'), 10, 5);
                }
                if (daftplugInstantify::getSetting('pwaPushBpMemberReply') == 'on') {
                    add_action('bp_activity_sent_reply_to_update_notification', array($this, 'doBpMemberCommentPush'), 10, 4);
                    add_action('bp_activity_sent_reply_to_reply_notification', array($this, 'doBpMemberReplyPush'), 10, 4);
                }
                if (daftplugInstantify::getSetting('pwaPushBpNewMessage') == 'on') {
                    add_action('messages_message_sent', array($this, 'doBpNewMessagePush'), 10, 1);
                }
                if (daftplugInstantify::getSetting('pwaPushBpFriendRequest') == 'on') {
                    add_action('friends_friendship_requested', array($this, 'doBpFriendRequestPush'), 1, 4);
                }
                if (daftplugInstantify::getSetting('pwaPushBpFriendAccepted') == 'on') {
                    add_action('friends_friendship_accepted', array($this, 'doBpFriendAcceptedPush'), 1, 4);
                }
            }

            if (daftplugInstantify::isPeepsoActive()) {
                if (daftplugInstantify::getSetting('pwaPushPeepsoNotifications') == 'on') {
                    add_filter('peepso_notifications_data_before_add', array($this, 'doPeepsoNotification'), 99);
                }
            }

            if (daftplugInstantify::getSetting('pwaPushButton') == 'on') {
                add_filter("{$this->optionName}_public_html", array($this, 'renderPushButton'));
            }

            if (daftplugInstantify::getSetting('pwaPushPrompt') == 'on') {
                add_filter("{$this->optionName}_public_html", array($this, 'renderPushPrompt'));
            }           
        }

        public function handleSubscription() {
            $subscribedDevices = $this->subscribedDevices;
            $endpoint = $_REQUEST['endpoint'];
            $userKey = $_REQUEST['userKey'];
            $userAuth = $_REQUEST['userAuth'];
            $deviceInfo = $_REQUEST['deviceInfo'];
            $date = date('j M Y');
            $country = json_decode(file_get_contents('http://www.geoplugin.net/json.gp?ip='.$_SERVER['REMOTE_ADDR']), true);
            $method = $_REQUEST['method'];
            $user = (is_user_logged_in() ? get_current_user_id() : esc_html__('Unregistered', $this->textDomain));
            $roles = (is_user_logged_in() ? (array)wp_get_current_user()->roles : array());
            
            switch ($method) {
                case 'add':
                    $subscribedDevices[$endpoint] = array(
                        'endpoint' => $endpoint,
                        'userKey' => $userKey,
                        'userAuth' => $userAuth,
                        'deviceInfo' => $deviceInfo,
                        'date' => $date,
                        'country' => @$country['geoplugin_countryName'],
                        'user' => $user,
                        'roles' => $roles,
                    );
                    break;
                case 'update':
                    if (array_key_exists($endpoint, $subscribedDevices)) {
                        foreach ($subscribedDevices as $key => $value) {
                            $subscribedDevices[$endpoint]['userKey'] = $userKey;
                            $subscribedDevices[$endpoint]['userAuth'] = $userAuth;
                            $subscribedDevices[$endpoint]['deviceInfo'] = $deviceInfo;
                            $subscribedDevices[$endpoint]['user'] = $user;
                            $subscribedDevices[$endpoint]['roles'] = $roles;
                        }
                    } else {
                        $subscribedDevices[$endpoint] = array(
                            'endpoint' => $endpoint,
                            'userKey' => $userKey,
                            'userAuth' => $userAuth,
                            'deviceInfo' => $deviceInfo,
                            'date' => $date,
                            'country' => @$country['geoplugin_countryName'],
                            'user' => $user,
                            'roles' => $roles,
                        );
                    }
                    break;
                case 'remove':
                    unset($subscribedDevices[$endpoint]);
                    break;
                default:
                    echo 'Error: method not handled';
                    return;
            }

            $handled = update_option("{$this->optionName}_subscribed_devices", $subscribedDevices);

            if ($handled) {
                wp_die('1');
            } else {
                wp_die('0');
            }
        }

        public function doWooNewOrderPush($orderId) {
            if (!$orderId) {
                return;
            }

            $order = wc_get_order($orderId);
            $pushData = array(
                'title' => esc_html__('WooCommerce New Order', $this->textDomain),
                'body' => sprintf(__('You have new order for total %s%s. Click on notification to see it.', $this->textDomain), html_entity_decode (get_woocommerce_currency_symbol($order->get_currency())), $order->get_total()),
                'data' => array(
                    'url' => $order->get_view_order_url(),
                ),
            );

            $this->sendNotification($pushData, 'wooNewOrder');
        }

        public function doWooLowStockPush($orderId) {
            if (!$orderId) {
                return;
            }
        
            $order = wc_get_order($orderId);
            $items = $order->get_items();
            
            foreach ($items as $item) {
                if ($item['variation_id'] > 0) {
                    $productId = $item['variation_id'];
                    $stock = get_post_meta($item['variation_id'], '_stock', true);
                    $sku = get_post_meta($item['variation_id'], '_sku', true);
                    $lowStockThreshold = get_post_meta($item['variation_id'], '_low_stock_amount', true);
                } else {
                    $productId = $item['product_id'];
                    $stock = get_post_meta( $item['product_id'], '_stock', true);
                    $sku = get_post_meta( $item['product_id'], '_sku', true);
                    $lowStockThreshold = get_post_meta($item['product_id'], '_low_stock_amount', true);
                }

                $lowStockThreshold = (!empty($lowStockThreshold) ? $lowStockThreshold : daftplugInstantify::getSetting('pwaPushWooLowStockThreshold'));

                if ($stock <= $lowStockThreshold && !get_post_meta($orderId, 'pwaPushWooLowStock', true)) {
                    update_post_meta($orderId, 'pwaPushWooLowStock', 1);
                    $pushData = array(
                        'title' => esc_html__('WooCommerce Low Stock', $this->textDomain),
                        'body' => sprintf(__('The product "%s" is running out of stock. Currently left %s in stock. Click on notification to see it.', $this->textDomain), $item['name'], $stock),
                        'data' => array(
                            'url' => get_permalink($productId),
                        ),
                    );

                    $this->sendNotification($pushData, 'wooLowStock');
                }
            }
        }

        public function doBpMemberMentionPush($activity, $subject, $message, $content, $receiverUserId) {
            $currentUser = get_userdata($activity->user_id);
            $friendDetail = get_userdata($receiverUserId);

            if ($activity->type == 'activity_comment') {
                $body = sprintf(__('%s has just mentioned you in a comment.', $this->textDomain), $currentUser->display_name);
            } else {
                $body = sprintf(__('%s has just mentioned you in an update.', $this->textDomain), $currentUser->display_name);
            }
    
            $pushData = array(
                'title' => sprintf(__('New mention from %s', $this->textDomain), $currentUser->display_name),
                'body' => $body,
                'data' => array(
                    'url' => get_permalink(get_option('bp-pages')['members']).$friendDetail->user_nicename.'/activity/mentions/',
                ),
            );

            $this->sendNotification($pushData, 'bpActivity', $receiverUserId);
        }

        public function doBpMemberCommentPush($activity, $commentId, $commenterId, $params) {
            $currentUser = get_userdata($commenterId);
            $receiverDetail = get_userdata($activity->user_id);
            $pushData = array(
                'title' => sprintf(__('New comment from %s', $this->textDomain), $currentUser->display_name),
                'body' => sprintf(__('%s has just commented on your post.', $this->textDomain), $currentUser->display_name),
                'data' => array(
                    'url' => get_permalink(get_option('bp-pages')['members']).$receiverDetail->user_nicename.'/activity/'.$activity->id.'/#acomment-'.$commentId,
                ),
            );

            $this->sendNotification($pushData, 'bpActivity', $activity->user_id);
        }

        public function doBpMemberReplyPush($activity, $commentId, $commenterId, $params) {
            $currentUser = get_userdata($commenterId);
            $pushData = array(
                'title' => sprintf(__('New reply from %s', $this->textDomain), $currentUser->display_name),
                'body' => sprintf(__('%s has just replied you.', $this->textDomain), $currentUser->display_name),
                'data' => array(
                    'url' => get_permalink(get_option('bp-pages')['activity']).'p/'.$activity->item_id.'/#acomment-'.$commenterId,
                ),
            );

            $this->sendNotification($pushData, 'bpActivity', $activity->user_id);
        }

        public function doBpNewMessagePush($params) {
            $senderDetail = get_userdata($params->sender_id);
            foreach ($params->recipients as $r) {
                $recipientDetail = get_userdata($r->user_id);
                $pushData = array(
                    'title' => sprintf(__('New message from %s', $this->textDomain), $senderDetail->display_name),
                    'body' => substr(strip_tags($params->message), 0, 77).'...',
                    'data' => array(
                        'url' => get_permalink(get_option('bp-pages')['members']).$recipientDetail->user_nicename.'/messages/view/'.$params->thread_id,
                    ),
                );
                
                $this->sendNotification($pushData, 'bpActivity', $r->user_id);
            }
        }

        public function doBpFriendRequestPush($id, $userId, $friendId, $friendship) {
            $friendDetail = get_userdata($friendId);
            $currentUser = get_userdata($userId);
            $pushData = array(
                'title' => sprintf(__('New friend request from %s', $this->textDomain), $currentUser->display_name),
                'body' => sprintf(__('%s has just sent you a friend request.', $this->textDomain), $currentUser->display_name),
                'data' => array(
                    'url' => get_permalink(get_option('bp-pages')['members']).$friendDetail->user_nicename.'/friends/requests/?new',
                ),
            );
            
            $this->sendNotification($pushData, 'bpActivity', $friendId);
        }

        public function doBpFriendAcceptedPush($id, $userId, $friendId, $friendship) {
            $friendDetail = get_userdata($userId);
            $currentUser = get_userdata($friendId);
            $pushData = array(
                'title' => sprintf(__('%s accepted your friend request', $this->textDomain), $currentUser->display_name),
                'body' => sprintf(__('%s has just accepted your friend request.', $this->textDomain), $currentUser->display_name),
                'data' => array(
                    'url' => get_permalink(get_option('bp-pages')['members']).$friendDetail->user_nicename.'/friends',
                ),
            );
            
            $this->sendNotification($pushData, 'bpActivity', $userId);
        }

        public function doPeepsoNotification($notification) {
            self::$noteData = $notification;
            if (self::$noteData['not_external_id'] > 0) {
                self::$noteData['post_title'] = get_the_title(self::$noteData['not_external_id']);
            }
            $PeepSoUser = PeepSoUser::get_instance(self::$noteData['not_from_user_id']);
            $notificationArgs = self::peepsoNotificationLink(false);
            $pushData = array(
                'title' => sprintf(__('New Notification From %s', $this->textDomain), $PeepSoUser->get_firstname()),
                'body' => strip_tags($PeepSoUser->get_firstname().' '.self::$noteData['not_message'].$notificationArgs['message']),
                'data' => array(
                    'url' => $notificationArgs['link'],
                ),
            );

            $this->sendNotification($pushData, 'peepsoNotification', self::$noteData['not_user_id']);

            return $notification;
        }

        private static function peepsoNotificationLink($echo = true) {
            $link = PeepSo::get_page('activity_status') . self::$noteData['post_title'] . '/';
            $link = apply_filters('peepso_profile_notification_link', $link, self::$noteData);
            $isComment = 0;
            if ('user_comment' === self::$noteData['not_type']) {
                $isComment = 1;
            }
            if ('like_post' == self::$noteData['not_type']) {
                global $wpdb;
                $sql = 'SELECT COUNT(id) as `is_comment_like` FROM `' . $wpdb->prefix . 'posts` WHERE `post_type`=\'peepso-comment\' AND ID=' . self::$noteData['not_external_id'];
                $res = $wpdb->get_row($sql);
                $isComment = $res->is_comment_like;
            }
            $printLink = '';
            $activityType = array(
                'type' => 'post',
                'text' => __('post', 'peepso-core')
            );
            if ('stream_reply_comment' === self::$noteData['not_type']) {
                $activities = PeepSoActivity::get_instance();
                $notActivity = $activities->get_activity_data(self::$noteData['not_external_id'], self::$noteData['not_module_id']);
                $commentActivity = $activities->get_activity_data($notActivity->act_comment_object_id, $notActivity->act_comment_module_id);
                $postActivity = $activities->get_activity_data($commentActivity->act_comment_object_id, $commentActivity->act_comment_module_id);
    
                if (is_object($commentActivity) && is_object($postActivity)) {
                    $parentComment = $activities->get_activity_post($commentActivity->act_id);
                    $parentPost = $activities->get_activity_post($postActivity->act_id);
                    $parentId = $parentComment->act_external_id;
                    $postLink = PeepSo::get_page('activity_status') . $parentPost->post_title . '/';
                    $commentLink = $postLink . '?t=' . time() . '#comment.' . $postActivity->act_id . '.' . $parentComment->ID . '.' . $commentActivity->act_id . '.' . $notActivity->act_external_id;
                    if (0 === intval($echo)) {
                        $hyperlink = $commentLink;
                    }
                    ob_start();
                    echo ' ';
                    $postContent = __('a comment', 'peepso-core');
                    if (intval($parentComment->post_author) === get_current_user_id()) {
                        $postContent = (self::$noteData['not_message'] != __('replied to', 'peepso-core')) ? __('on ', 'peepso-core') : '';
                        $postContent .= __('your comment', 'peepso-core');
                    }
                    echo $postContent;
                    $printLink = ob_get_clean();
                }
            } elseif('profile_like' === self::$noteData['not_type']) {
                $author = PeepSoUser::get_instance(self::$noteData['not_from_user_id']);
                $link = $author->get_profileurl();
                if (0 === intval($echo)) {
                    $hyperlink = $link;
                }
            } elseif (1 == $isComment) {
                $activities = PeepSoActivity::get_instance();
                $notActivity = $activities->get_activity_data(self::$noteData['not_external_id'], self::$noteData['not_module_id']);
                $parentActivity = $activities->get_activity_data($notActivity->act_comment_object_id, $notActivity->act_comment_module_id);
                if (is_object($parentActivity)) {
                    $notPost = $activities->get_activity_post($notActivity->act_id);
                    $parentPost = $activities->get_activity_post($parentActivity->act_id);
                    $parentId = $parentPost->act_external_id;
                    $activityType = apply_filters('peepso_notifications_activity_type', $activityType, $parentId, NULL);
                    if ($parentPost->post_type == 'peepso-comment') {
                        $commentActivity = $activities->get_activity_data($notActivity->act_comment_object_id, $notActivity->act_comment_module_id);
                        $postActivity = $activities->get_activity_data($commentActivity->act_comment_object_id, $commentActivity->act_comment_module_id);
                        $parentPost = $activities->get_activity_post($postActivity->act_id);
                        $parentComment = $activities->get_activity_post($commentActivity->act_id);
                        $parentLink = PeepSo::get_page('activity_status') . $parentPost->post_title . '/?t=' . time() . '#comment.' . $postActivity->act_id . '.' . $parentComment->ID . '.' . $commentActivity->act_id . '.' . $notActivity->act_external_id;
                    } else {
                        $parentLink = PeepSo::get_page('activity_status') . $parentPost->post_title . '/#comment.' . $parentActivity->act_id . '.' . $notPost->ID . '.' . $notActivity->act_external_id;
                    }
                    if (0 === intval($echo)) {
                        $hyperlink = $parentLink;
                    }
                    ob_start();
                    $postContent = '';
                    $on = '';
                    if ($activityType['type'] == 'post') {
                        $on = ' ' . __('on', 'peepso-core');
                        $postContent = sprintf(__('a %s', 'peepso-core') , $activityType['text']);
                    }
                    if (intval($parentPost->post_author) === get_current_user_id() || (intval($parentPost->post_author) === get_current_user_id() && in_array($activityType['type'], array('cover', 'avatar')))) {
                        $on = ' ' . __('on', 'peepso-core');
                        $postContent = sprintf(__('your %s', 'peepso-core') , $activityType['text']);
                    }
                    if (in_array($activityType['type'], array('cover', 'avatar')) && (intval($parentPost->post_author) !== get_current_user_id())) {
                        $on = ' ' . __('on', 'peepso-core');
                        if (preg_match('/^[aeiou]/i', strtolower($activityType['text']))) {
                            $postContent = sprintf(__('an %s', 'peepso-core') , $activityType['text']);
                        } else {
                            $postContent = sprintf(__('a %s', 'peepso-core') , $activityType['text']);
                        }
                    }
                    echo $on, ' ';
                    echo $postContent;
                    $printLink = ob_get_clean();
                }
            } else {
                if (0 === intval($echo)) {
                    $hyperlink = $link;
                }
                if ('share' === self::$noteData['not_type']) {
                    $activities = PeepSoActivity::get_instance();
                    $repost = $activities->get_activity_data(self::$noteData['not_external_id'], self::$noteData['not_module_id']);
                    $originalPost = $activities->get_activity_post($repost->act_repost_id);
                    $activityType = apply_filters('peepso_notifications_activity_type', $activityType, $originalPost->ID, NULL);
                    ob_start();
                    echo ' ', sprintf(__('your %s', 'peepso-core') , $activityType['text']);
                    $printLink = ob_get_clean();
                }
            }
    
            $printLink = apply_filters('peepso_modify_link_item_notification', array(
                $printLink,
                $link
            ) , self::$noteData);
    
            if (is_array($printLink)) {
                return ['message' => $printLink[0], 'link' => $hyperlink];
            } else {
                return ['message' => $printLink, 'link' => $hyperlink];
            }
        }

        public function addPushToServiceWorker($serviceWorker) {
            $serviceWorker .= file_get_contents(plugins_url('pwa/public/assets/js/script-push.js', $this->pluginFile));

            return $serviceWorker;
        }

        public function addPushJsVars($vars) {
            $vars['pwaPublicKey'] = $this->vapidKeys['pwaPublicKey'];
            $vars['pwaSubscribeOnMsg'] = esc_html__('Notifications are turned on', $this->textDomain);
            $vars['pwaSubscribeOffMsg'] = esc_html__('Notifications are turned off', $this->textDomain);

            return $vars;
        }

        public function renderPushPrompt() {
            if (daftplugInstantify::isAmpPage() || !daftplugInstantifyPwa::isPwaAvailable()) {
                return;
            }
            
            include_once($this->daftplugInstantifyPwaPublic->partials['pushPrompt']);
        }

        public function renderPushButton() {
            if (daftplugInstantify::isAmpPage() || !daftplugInstantifyPwa::isPwaAvailable()) {
                return;
            }
            
            include_once($this->daftplugInstantifyPwaPublic->partials['pushButton']);
        }

        public function sendNotification($pushData, $type, $tagetUserId = null) {
            require_once(plugin_dir_path($this->pluginFile) . implode(DIRECTORY_SEPARATOR, array('pwa', 'includes', 'libs', 'web-push-php', 'autoload.php')));

            $auth = array(
                'VAPID' => array(
                    'subject' => get_bloginfo('wpurl'),
                    'publicKey' => $this->vapidKeys['pwaPublicKey'],
                    'privateKey' => $this->vapidKeys['pwaPrivateKey'],
                ),
            );

            $webPush = new WebPush($auth);

            $pushData = wp_parse_args($pushData, array(
                'title' => '',
                'badge' => '',
                'body' => '',
                'icon' => '',
                'image' => '',
                'data' => '',
                'tag' => 'renotify',
                'renotify' => true,
            ));

            switch ($type) {
                case 'wooNewOrder':
                    $subscriptions = array();
                    foreach ($this->subscribedDevices as $subscribedDevice) {
                        if (in_array(daftplugInstantify::getSetting('pwaPushWooNewOrderRole'), $subscribedDevice['roles'])) {
                            $subscriptions[] =  array(
                                                    'subscription' => Subscription::create(
                                                        array(
                                                            'endpoint' => $subscribedDevice['endpoint'],
                                                            'publicKey' => $subscribedDevice['userKey'],
                                                            'authToken' => $subscribedDevice['userAuth'],
                                                        )
                                                    ),
                                                    'payload' => null
                                                );
                        }
                    }
        
                    foreach ($subscriptions as $subscription) {
                        $webPush->sendNotification(
                            $subscription['subscription'],
                            json_encode($pushData)
                        );
                    }
                    break;
                case 'wooLowStock':
                    $subscriptions = array();
                    foreach ($this->subscribedDevices as $subscribedDevice) {
                        if (in_array(daftplugInstantify::getSetting('pwaPushWooLowStockRole'), $subscribedDevice['roles'])) {
                            $subscriptions[] =  array(
                                                    'subscription' => Subscription::create(
                                                        array(
                                                            'endpoint' => $subscribedDevice['endpoint'],
                                                            'publicKey' => $subscribedDevice['userKey'],
                                                            'authToken' => $subscribedDevice['userAuth'],
                                                        )
                                                    ),
                                                    'payload' => null
                                                );
                        }
                    }
    
                    foreach ($subscriptions as $subscription) {
                        $webPush->sendNotification(
                            $subscription['subscription'],
                            json_encode($pushData)
                        );
                    }
                    break;
                case 'bpActivity':
                case 'peepsoNotification':
                    $subscriptions = array();
                    foreach ($this->subscribedDevices as $subscribedDevice) {
                        if ($tagetUserId == $subscribedDevice['user']) {
                            $subscriptions[] =  array(
                                                    'subscription' => Subscription::create(
                                                        array(
                                                            'endpoint' => $subscribedDevice['endpoint'],
                                                            'publicKey' => $subscribedDevice['userKey'],
                                                            'authToken' => $subscribedDevice['userAuth'],
                                                        )
                                                    ),
                                                    'payload' => null
                                                );
                        }
                    }
    
                    foreach ($subscriptions as $subscription) {
                        $webPush->sendNotification(
                            $subscription['subscription'],
                            json_encode($pushData)
                        );
                    }
                    break;
                default:
                    echo 'Undefined Push Type.';
            }

            foreach ($webPush->flush() as $report) {
                $endpoint = $report->getRequest()->getUri()->__toString();
                if (!$report->isSuccess()) {
                    unset($this->subscribedDevices[$endpoint]);
                    update_option("{$this->optionName}_subscribed_devices", $this->subscribedDevices);
                    //wp_die("[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}");
                } else {
                    return true;
                }
            }
        }
    }
}