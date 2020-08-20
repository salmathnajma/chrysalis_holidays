jQuery( document ).ready(function($) {
	"use strict";

    $('.customize-control-tinymce-editor').each(function(){
        // Get the toolbar strings that were passed from the PHP Class
        var tinyMCEToolbar1String = _wpCustomizeSettings.controls[$(this).attr('id')].skyrockettinymcetoolbar1;
        var tinyMCEToolbar2String = _wpCustomizeSettings.controls[$(this).attr('id')].skyrockettinymcetoolbar2;

        wp.editor.initialize( $(this).attr('id'), {
            tinymce: {
                wpautop: true,
                toolbar1: tinyMCEToolbar1String,
                toolbar2: tinyMCEToolbar2String
            },
            quicktags: true
        });
    });
    $(document).on( 'tinymce-editor-init', function( event, editor ) {
        editor.on('change', function(e) {
            tinyMCE.triggerSave();
            $('#'+editor.id).trigger('change');
        });
    });
});