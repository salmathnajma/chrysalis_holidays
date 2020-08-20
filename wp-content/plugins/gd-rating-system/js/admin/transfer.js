/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
/*global gdrts_data, ajaxurl*/
;(function($, window, document, undefined) {
    window.wp = window.wp || {};
    window.wp.gdrts = window.wp.gdrts || {};

    window.wp.gdrts.transfer = {
        status: {
            active: false,
            stop: false,
            total: 0,
            current: 0,
            pages: 0,
            step: 500,
            settings: {}
        },
        init: function() {
            wp.gdrts.transfer.status.step = gdrts_data.step_transfer;

            $(".d4p-content-left .button-primary").click(function(e){
                e.preventDefault();

                if (wp.gdrts.transfer.status.active) {
                    wp.gdrts.transfer.transfer.stop();
                } else {
                    wp.gdrts.transfer.transfer.settings();
                    wp.gdrts.transfer.transfer.start();
                }
            });
        },
        transfer: {
            settings: function() {
                var s = {
                    plugin: $(".gdrts-tr-plugin").val(),
                    data: []
                };

                $(".gdrts-tr-check:checked").each(function(){
                    var key = $(this).val(),
                        box = {
                            rating: key
                        };

                    $(".gdrts-tr-checked-" + key).each(function(){
                        box[$(this).attr("name")] = $(this).val();
                    });

                    s.data.push(box);
                });

                wp.gdrts.transfer.status.settings = s;
            },
            start: function() {
                wp.gdrts.transfer.status.active = true;

                $(".d4p-panel-buttons .button-primary").val(gdrts_data.button_stop);
                $("#gdrts-remotecall-intro").slideUp();
                $("#gdrts-remotecall-process").slideDown();

                wp.gdrts.transfer.transfer._call({
                    operation: "start",
                    settings: wp.gdrts.transfer.status.settings
                }, wp.gdrts.transfer.transfer.callback_start);
            },
            stop: function() {
                wp.gdrts.transfer.status.stop = true;

                $(".d4p-panel-buttons .button-primary").attr("disabled", true);
            },
            callback_start: function(json) {
                wp.gdrts.transfer.status.total = json.objects;
                wp.gdrts.transfer.status.pages = Math.ceil(json.objects / wp.gdrts.transfer.status.step);

                wp.gdrts.transfer.transfer._write(json.message);

                wp.gdrts.transfer.transfer.run();
            },
            callback_stop: function(json) {
                wp.gdrts.transfer.status.active = false;
                wp.gdrts.transfer.transfer._write(json.message);
            },
            callback_process: function(json) {
                if (wp.gdrts.transfer.status.stop) {
                    wp.gdrts.transfer.transfer._call({
                        operation: "break"
                    }, wp.gdrts.transfer.transfer.callback_stop);
                } else {
                    wp.gdrts.transfer.status.current++;

                    wp.gdrts.transfer.transfer._write(json.message);

                    if (wp.gdrts.transfer.status.current < wp.gdrts.transfer.status.pages) {
                        wp.gdrts.transfer.transfer.run();
                    } else {
                        wp.gdrts.transfer.transfer.stop();
                        wp.gdrts.transfer.transfer._call({
                            operation: "stop"
                        }, wp.gdrts.transfer.transfer.callback_stop);
                    }
                }
            },
            run: function() {
                wp.gdrts.transfer.transfer._call({
                    operation: "run",
                    total: wp.gdrts.transfer.status.total,
                    current: wp.gdrts.transfer.status.current,
                    step: wp.gdrts.transfer.status.step,
                    settings: wp.gdrts.transfer.status.settings
                }, wp.gdrts.transfer.transfer.callback_process);
            },
            _write: function(message) {
                $("#gdrts-remotecall-progress pre").prepend(message + "\r\n");
            },
            _call: function(data, callback) {
                $.ajax({
                    url: ajaxurl + "?action=gdrts_transfer_process&_ajax_nonce=" + gdrts_data.nonce,
                    timeout: 0, type: "post", dataType: "json", data: data, success: callback
                });
            }
        }
    };

    wp.gdrts.transfer.init();
})(jQuery, window, document);
