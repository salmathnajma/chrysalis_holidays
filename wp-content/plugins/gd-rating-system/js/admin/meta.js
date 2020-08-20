/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
;(function($, window, document, undefined) {
    window.wp = window.wp || {};
    window.wp.gdrts = window.wp.gdrts || {};

    window.wp.gdrts.meta = {
        init: function() {
            $(window).on('resize orientationchange', wp.gdrts.meta.tabs);

            wp.gdrts.meta.tabs();
        },
        tabs: function() {
            var meta = $('#gdrts-metabox .d4plib-metabox-wrapper'),
                tabs = $('.wp-tab-bar li', meta), total = 0;

            tabs.each(function(){
                total+= $(this).outerWidth();
            });

            if (meta.width() < total) {
                meta.addClass('d4plib-metabox-iconize');
            } else {
                meta.removeClass('d4plib-metabox-iconize');
            }
        }
    };

    wp.gdrts.meta.init();
})(jQuery, window, document);
