jQuery(function() {
    'use strict';
    var daftplugAdmin = jQuery('.daftplugAdmin[data-daftplug-plugin="daftplug_instantify"]');
    var optionName = daftplugAdmin.attr('data-daftplug-plugin');
    var objectName = window[optionName + '_admin_js_vars'];

    // Handle AMP custom CSS editor
    var ampCustomCssTextarea = daftplugAdmin.find('#ampCustomCss');
    var cmEditorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings) : {};
    cmEditorSettings.codemirror = _.extend(
        {},
        cmEditorSettings.codemirror,
        {
            lineNumbers: true,
            mode: 'text/css',
            indentUnit: 4,
            tabSize: 4,
            autoRefresh:true,
        }
    );
    var cmEditor = wp.codeEditor.initialize(ampCustomCssTextarea, cmEditorSettings);
    daftplugAdmin.on('keyup paste', '.CodeMirror-code', function(e) {
        ampCustomCssTextarea.html(cmEditor.codemirror.getValue()).trigger('change');
    });
});