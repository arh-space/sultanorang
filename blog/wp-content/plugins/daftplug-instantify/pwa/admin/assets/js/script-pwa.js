jQuery(function() {
    'use strict';
    var daftplugAdmin = jQuery('.daftplugAdmin[data-daftplug-plugin="daftplug_instantify"]');
    var optionName = daftplugAdmin.attr('data-daftplug-plugin');
    var objectName = window[optionName + '_admin_js_vars'];

    // Generate launch screens
    daftplugAdmin.find('.daftplugAdminSettings_form').on('submit', function(e) {
        e.preventDefault();
        var action = optionName + '_generate_launch_screens';
        var canvas = document.createElement('canvas');
        canvas.width = 2048;
        canvas.height = 2732;
        var image = new Image();
        image.src = jQuery('#pwaIcon').attr('data-attach-url');
        image.onload = function() {
            var ctx = canvas.getContext('2d');
            ctx.fillStyle = jQuery('#pwaBackgroundColor').val();
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(image,
              canvas.width / 2 - image.width / 2,
              canvas.height / 2 - image.height / 2
            );

            var launchScreen = canvas.toDataURL('image/png');

            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: action,
                    launchScreen: launchScreen,
                },
                beforeSend: function() {
                    
                },
                success: function(response, textStatus, jqXhr) {
                    
                },
                complete: function() {

                },
                error: function(jqXhr, textStatus, errorThrown) {
                    
                }
            });
        };
    });

    // Handle populating segment select
    daftplugAdmin.on('click', '.daftplugAdminTable_action.-send, .daftplugAdminButton.-sendNotification', function(e) {
        var self = jQuery(this);
        var openPopup = self.attr('data-open-popup');
        var popup = daftplugAdmin.find('[data-popup="'+openPopup+'"]');
        var subscription = self.attr('data-subscription');
        var form = popup.find('.daftplugAdminSendPush_form');
        var pushSegmentSelect = form.find('#pushSegment');
        var pushSegmentDropdown = form.find('.daftplugAdminInputSelect_dropdown[data-name="pushSegment"]');
        var pushSegmentList = form.find('.daftplugAdminInputSelect_list[data-name="pushSegment"]');

        pushSegmentSelect.val(subscription);
        pushSegmentList.find('.daftplugAdminInputSelect_option.-selected').removeClass('-selected');
        pushSegmentList.find('.daftplugAdminInputSelect_option[data-value="'+subscription+'"]').addClass('-selected');
        pushSegmentDropdown.attr('data-value', subscription).text(pushSegmentList.find('.daftplugAdminInputSelect_option.-selected').find('.daftplugAdminInputSelect_text').text());
    });

    // Handle table data remove
    daftplugAdmin.on('click', '.daftplugAdminTable_action.-remove', function(e) {
        var self = jQuery(this);
        var row = self.closest('.daftplugAdminTable_row');
        var action = optionName + '_handle_subscription';
        var method = 'remove';
        var endpoint = self.attr('data-subscription');

        jQuery.ajax({
            url: ajaxurl,
            dataType: 'text',
            type: 'POST',
            data: {
                action: action,
                method: method,
                endpoint: endpoint,
            },
            beforeSend: function() {
                row.addClass('-disabled');
            },
            success: function(response, textStatus, jqXhr) {
                row.remove();
                jQuery('.daftplugAdminButton.-sendAll').remove();
            },
            complete: function() {

            },
            error: function(jqXhr, textStatus, errorThrown) {
                row.remove();
                jQuery('.daftplugAdminButton.-sendAll').remove();
            }
        });
    });

    // Handle table data filter
    daftplugAdmin.on('input paste', '#subscribersFilter', function(e) {
        var self = jQuery(this);
        var searchPhrase = self.val();           
        searchPhrase = jQuery.trim(searchPhrase).replace(/ +/g, ' ').toLowerCase();
        daftplugAdmin.find('tbody').find('.daftplugAdminTable_row').show().filter(function(e) {
            var a = jQuery(this).text().replace(/\s+/g, ' ').toLowerCase();
            return -1 === a.indexOf(searchPhrase)
        }).hide();
    });

    // Send push notification
    daftplugAdmin.find('.daftplugAdminSendPush_form').on('submit', function(e) {
        e.preventDefault();
        var self = jQuery(this);
        var button = self.find('.daftplugAdminButton.-submit');
        var responseText = self.find('.daftplugAdminField_response');
        var action = optionName + '_send_notification';
        var nonce = self.attr('data-nonce');
        var pushSegment = self.find('#pushSegment');
        var pushTitle = self.find('#pushTitle');
        var pushBody = self.find('#pushBody');
        var pushImage = self.find('#pushImage');
        var pushUrl = self.find('#pushUrl');
        var pushIcon = self.find('#pushIcon');
        var pushActionButton1Text = self.find('#pushActionButton1Text');
        var pushActionButton1Url = self.find('#pushActionButton1Url');
        var pushActionButton2Text = self.find('#pushActionButton2Text');
        var pushActionButton2Url = self.find('#pushActionButton2Url');
        
        jQuery.ajax({
            url: ajaxurl,
            dataType: 'text',
            type: 'POST',
            data: {
                action: action,
                nonce: nonce,
                pushSegment: pushSegment.val(),
                pushTitle: pushTitle.val(),
                pushBody: pushBody.val(),
                pushImage: pushImage.val(),
                pushUrl: pushUrl.val(),
                pushIcon: pushIcon.val(),
                pushActionButton1Text: pushActionButton1Text.val(),
                pushActionButton1Url: pushActionButton1Url.val(),
                pushActionButton2Text: pushActionButton2Text.val(),
                pushActionButton2Url: pushActionButton2Url.val(),
            },
            beforeSend: function() {
                button.addClass('-loading');
            },
            success: function(response, textStatus, jqXhr) {
                if (response == 1) {
                    button.addClass('-success');
                    setTimeout(function() {
                        button.removeClass('-loading -success');
                    }, 1500);
                    responseText.css('color', '#4073FF').html('Notification sent successfully.').fadeIn('fast').delay(3000).fadeOut('fast', function() {
                        responseText.empty().show();
                    });
                    self.trigger('reset');
                    pushImage.val('').removeClass('-hasFile').removeAttr('data-attach-url');
                    pushIcon.val('').removeClass('-hasFile').removeAttr('data-attach-url');
                    self.find('.daftplugAdminMinifielset_close').trigger('click');
                } else {
                    button.addClass('-fail');
                    setTimeout(function() {
                        button.removeClass('-loading -fail');
                    }, 1500);
                    responseText.css('color', '#FF3A3A').html('Sending push notification failed.').fadeIn('fast');
                }

                console.log(response);
            },
            complete: function() {

            },
            error: function(jqXhr, textStatus, errorThrown) {
                button.addClass('-fail');
                setTimeout(function() {
                    button.removeClass('-loading -fail');
                }, 1500);
                responseText.css('color', '#FF3A3A').html('Sending push notification failed.').fadeIn('fast');

                console.log(jqXhr);
            }
        });
    });
});