/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
/*global gdrts_data, ajaxurl*/

;(function($, window, document, undefined) {
    window.wp = window.wp || {};
    window.wp.gdrts = window.wp.gdrts || {};

    window.wp.gdrts.admin = {
        storage: {
            url: "",
            row: 0,
            progres: {
                active: false,
                stop: false,
                total: 0,
                current: 0,
                pages: 0,
                step: 50,
                settings: []
            }
        },
        shared: {
            slug: function(el) {
                $(".gdrts-field-slug", el).limitkeypress({ rexp: /^[a-z0-9]*[a-z0-9\-\_]*[a-z0-9]*$/ });
            }
        },
        init: function() {
            if (gdrts_data.page === "about" && gdrts_data.panel === "addons") {
                wp.gdrts.admin.dialogs.changelog();
                wp.gdrts.admin.addons.changelog.init();
            }

            if (gdrts_data.page === "ratings") {
                wp.gdrts.admin.dialogs.ratings();
                wp.gdrts.admin.ratings.init();
            }

            if (gdrts_data.page === "log") {
                wp.gdrts.admin.dialogs.votes();
                wp.gdrts.admin.votes.init();
            }

            if (gdrts_data.page === "tools") {
                wp.gdrts.admin.dialogs.votes();
                wp.gdrts.admin.tools.init();
            }

            if (gdrts_data.page === "types") {
                wp.gdrts.admin.dialogs.types();
                wp.gdrts.admin.types.init();
            }

            if (gdrts_data.page === "settings") {
                wp.gdrts.admin.settings.init();
            }

            if (gdrts_data.page === "rules") {
                wp.gdrts.admin.dialogs.rules();
                wp.gdrts.admin.rules.init();
                wp.gdrts.admin.settings.init();
            }
        },
        dialogs: {
            classes: function(extra) {
                var cls = "wp-dialog d4p-dialog gdrts-modal-dialog";

                if (extra !== "") {
                    cls+= " " + extra;
                }

                return cls;
            },
            defaults: function() {
                return {
                    width: 480,
                    height: "auto",
                    minHeight: 24,
                    autoOpen: false,
                    resizable: false,
                    modal: true,
                    closeOnEscape: false,
                    zIndex: 300000,
                    open: function() {
                        $(".gdrts-button-focus").focus();
                    }
                };
            },
            icons: function(id) {
                $(id).next().find(".ui-dialog-buttonset button").each(function(){
                    var icon = $(this).data("icon");

                    if (icon !== "") {
                        $(this).find("span.ui-button-text").prepend(gdrts_data["button_icon_" + icon]);
                    }
                });
            },
            votes: function() {
                var dlg_delete = $.extend({}, wp.gdrts.admin.dialogs.defaults(), {
                    dialogClass: wp.gdrts.admin.dialogs.classes("gdrts-dialog-hidex"),
                    buttons: [
                        {
                            id: "gdrts-delete-del-delete",
                            class: "gdrts-dialog-button-delete",
                            text: gdrts_data.dialog_button_delete,
                            data: { icon: "delete" },
                            click: function() {
                                $(".d4p-content-right form").submit();
                            }
                        },
                        {
                            id: "gdrts-delete-del-cancel",
                            class: "gdrts-dialog-button-cancel gdrts-button-focus",
                            text: gdrts_data.dialog_button_cancel,
                            data: { icon: "cancel" },
                            click: function() {
                                $("#gdrts-dialog-log-delete").wpdialog("close");
                            }
                        }
                    ]
                }), dlg_remove = $.extend({}, wp.gdrts.admin.dialogs.defaults(), {
                    dialogClass: wp.gdrts.admin.dialogs.classes("gdrts-dialog-hidex"),
                    buttons: [
                        {
                            id: "gdrts-delete-rem-delete",
                            class: "gdrts-dialog-button-delete",
                            text: gdrts_data.dialog_button_remove,
                            data: { icon: "delete" },
                            click: function() {
                                $(".d4p-content-right form").submit();
                            }
                        },
                        {
                            id: "gdrts-delete-rem-cancel",
                            class: "gdrts-dialog-button-cancel gdrts-button-focus",
                            text: gdrts_data.dialog_button_cancel,
                            data: { icon: "cancel" },
                            click: function() {
                                $("#gdrts-dialog-log-remove").wpdialog("close");
                            }
                        }
                    ]
                }), dlg_delete_single = $.extend({}, wp.gdrts.admin.dialogs.defaults(), {
                    dialogClass: wp.gdrts.admin.dialogs.classes("gdrts-dialog-hidex"),
                    buttons: [
                        {
                            id: "gdrts-delete-delsingle-delete",
                            class: "gdrts-dialog-button-delete",
                            text: gdrts_data.dialog_button_delete,
                            data: { icon: "delete" },
                            click: function() {
                                window.location.href = wp.gdrts.admin.storage.url;
                            }
                        },
                        {
                            id: "gdrts-delete-delsingle-cancel",
                            class: "gdrts-dialog-button-cancel gdrts-button-focus",
                            text: gdrts_data.dialog_button_cancel,
                            data: { icon: "cancel" },
                            click: function() {
                                $("#gdrts-dialog-log-delete-single").wpdialog("close");
                            }
                        }
                    ]
                }), dlg_remove_single = $.extend({}, wp.gdrts.admin.dialogs.defaults(), {
                    dialogClass: wp.gdrts.admin.dialogs.classes("gdrts-dialog-hidex"),
                    buttons: [
                        {
                            id: "gdrts-delete-remsingle-delete",
                            class: "gdrts-dialog-button-delete",
                            text: gdrts_data.dialog_button_remove,
                            data: { icon: "delete" },
                            click: function() {
                                window.location.href = wp.gdrts.admin.storage.url;
                            }
                        },
                        {
                            id: "gdrts-delete-remsingle-cancel",
                            class: "gdrts-dialog-button-cancel gdrts-button-focus",
                            text: gdrts_data.dialog_button_cancel,
                            data: { icon: "cancel" },
                            click: function() {
                                $("#gdrts-dialog-log-remove-single").wpdialog("close");
                            }
                        }
                    ]
                });

                $("#gdrts-dialog-log-delete").wpdialog(dlg_delete);
                $("#gdrts-dialog-log-remove").wpdialog(dlg_remove);
                $("#gdrts-dialog-log-delete-single").wpdialog(dlg_delete_single);
                $("#gdrts-dialog-log-remove-single").wpdialog(dlg_remove_single);

                wp.gdrts.admin.dialogs.icons("#gdrts-dialog-log-delete");
                wp.gdrts.admin.dialogs.icons("#gdrts-dialog-log-remove");
                wp.gdrts.admin.dialogs.icons("#gdrts-dialog-log-delete-single");
                wp.gdrts.admin.dialogs.icons("#gdrts-dialog-log-remove-single");
            },
            ratings: function() {
                var dlg_delete = $.extend({}, wp.gdrts.admin.dialogs.defaults(), {
                    dialogClass: wp.gdrts.admin.dialogs.classes("gdrts-dialog-hidex"),
                    buttons: [
                        {
                            id: "gdrts-delete-del-delete",
                            class: "gdrts-dialog-button-delete",
                            text: gdrts_data.dialog_button_delete,
                            data: { icon: "delete" },
                            click: function() {
                                $(".d4p-content-right form").submit();
                            }
                        },
                        {
                            id: "gdrts-delete-del-cancel",
                            class: "gdrts-dialog-button-cancel gdrts-button-focus",
                            text: gdrts_data.dialog_button_cancel,
                            data: { icon: "cancel" },
                            click: function() {
                                $("#gdrts-dialog-ratings-delete").wpdialog("close");
                            }
                        }
                    ]
                }), dlg_clear = $.extend({}, wp.gdrts.admin.dialogs.defaults(), {
                    dialogClass: wp.gdrts.admin.dialogs.classes("gdrts-dialog-hidex"),
                    buttons: [
                        {
                            id: "gdrts-delete-clr-delete",
                            class: "gdrts-dialog-button-delete",
                            text: gdrts_data.dialog_button_clear,
                            data: { icon: "delete" },
                            click: function() {
                                $(".d4p-content-right form").submit();
                            }
                        },
                        {
                            id: "gdrts-delete-clr-cancel",
                            class: "gdrts-dialog-button-cancel gdrts-button-focus",
                            text: gdrts_data.dialog_button_cancel,
                            data: { icon: "cancel" },
                            click: function() {
                                $("#gdrts-dialog-ratings-clear").wpdialog("close");
                            }
                        }
                    ]
                }), dlg_delete_single = $.extend({}, wp.gdrts.admin.dialogs.defaults(), {
                    dialogClass: wp.gdrts.admin.dialogs.classes("gdrts-dialog-hidex"),
                    buttons: [
                        {
                            id: "gdrts-delete-clrsingle-delete",
                            class: "gdrts-dialog-button-delete",
                            text: gdrts_data.dialog_button_delete,
                            data: { icon: "delete" },
                            click: function() {
                                window.location.href = wp.gdrts.admin.storage.url;
                            }
                        },
                        {
                            id: "gdrts-delete-clrsingle-cancel",
                            class: "gdrts-dialog-button-cancel gdrts-button-focus",
                            text: gdrts_data.dialog_button_cancel,
                            data: { icon: "cancel" },
                            click: function() {
                                $("#gdrts-dialog-ratings-delete-single").wpdialog("close");
                            }
                        }
                    ]
                }), dlg_clear_single = $.extend({}, wp.gdrts.admin.dialogs.defaults(), {
                    dialogClass: wp.gdrts.admin.dialogs.classes("gdrts-dialog-hidex"),
                    buttons: [
                        {
                            id: "gdrts-delete-delsingle-delete",
                            class: "gdrts-dialog-button-delete",
                            text: gdrts_data.dialog_button_clear,
                            data: { icon: "delete" },
                            click: function() {
                                window.location.href = wp.gdrts.admin.storage.url;
                            }
                        },
                        {
                            id: "gdrts-delete-delsingle-cancel",
                            class: "gdrts-dialog-button-cancel gdrts-button-focus",
                            text: gdrts_data.dialog_button_cancel,
                            data: { icon: "cancel" },
                            click: function() {
                                $("#gdrts-dialog-ratings-clear-single").wpdialog("close");
                            }
                        }
                    ]
                }), dlg_recalculate_single = $.extend({}, wp.gdrts.admin.dialogs.defaults(), {
                    dialogClass: wp.gdrts.admin.dialogs.classes("gdrts-dialog-hidex"),
                    buttons: [
                        {
                            id: "gdrts-delete-recsingle-delete",
                            class: "gdrts-dialog-button-delete",
                            text: gdrts_data.dialog_button_recalculate,
                            data: { icon: "recalculate" },
                            click: function() {
                                window.location.href = wp.gdrts.admin.storage.url;
                            }
                        },
                        {
                            id: "gdrts-delete-recsingle-cancel",
                            class: "gdrts-dialog-button-cancel gdrts-button-focus",
                            text: gdrts_data.dialog_button_cancel,
                            data: { icon: "cancel" },
                            click: function() {
                                $("#gdrts-dialog-ratings-recalculate-single").wpdialog("close");
                            }
                        }
                    ]
                });

                $("#gdrts-dialog-ratings-delete").wpdialog(dlg_delete);
                $("#gdrts-dialog-ratings-clear").wpdialog(dlg_clear);
                $("#gdrts-dialog-ratings-delete-single").wpdialog(dlg_delete_single);
                $("#gdrts-dialog-ratings-clear-single").wpdialog(dlg_clear_single);
                $("#gdrts-dialog-ratings-recalculate-single").wpdialog(dlg_recalculate_single);

                wp.gdrts.admin.dialogs.icons("#gdrts-dialog-ratings-delete");
                wp.gdrts.admin.dialogs.icons("#gdrts-dialog-ratings-clear");
                wp.gdrts.admin.dialogs.icons("#gdrts-dialog-ratings-delete-single");
                wp.gdrts.admin.dialogs.icons("#gdrts-dialog-ratings-clear-single");;
                wp.gdrts.admin.dialogs.icons("#gdrts-dialog-ratings-recalculate-single");
            },
            types: function() {
                var dlg_delete = $.extend({}, wp.gdrts.admin.dialogs.defaults(), {
                    dialogClass: wp.gdrts.admin.dialogs.classes("gdrts-dialog-hidex"),
                    buttons: [
                        {
                            id: "gdrts-delete-del-delete",
                            class: "gdrts-dialog-button-delete",
                            text: gdrts_data.dialog_button_delete,
                            data: { icon: "delete" },
                            click: function() {
                                window.location.href = wp.gdrts.admin.storage.url;
                            }
                        },
                        {
                            id: "gdrts-delete-del-cancel",
                            class: "gdrts-dialog-button-cancel gdrts-button-focus",
                            text: gdrts_data.dialog_button_cancel,
                            data: { icon: "cancel" },
                            click: function() {
                                $("#gdrts-dialog-entity-delete").wpdialog("close");
                            }
                        }
                    ]
                });

                $("#gdrts-dialog-entity-delete").wpdialog(dlg_delete);

                wp.gdrts.admin.dialogs.icons("#gdrts-dialog-entity-delete");
            },
            changelog: function() {
                var dlg_delete = $.extend({}, wp.gdrts.admin.dialogs.defaults(), {
                    dialogClass: wp.gdrts.admin.dialogs.classes(),
                    width: 640,
                    height: 400,
                    buttons: [
                        {
                            id: "gdrts-delete-del-close",
                            class: "gdrts-dialog-button-close gdrts-button-focus",
                            text: gdrts_data.dialog_button_close,
                            data: { icon: "cancel" },
                            click: function() {
                                $("#gdrts-dialog-about-changelog").wpdialog("close");
                            }
                        }
                    ]
                });

                $("#gdrts-dialog-about-changelog").wpdialog(dlg_delete);

                wp.gdrts.admin.dialogs.icons("#gdrts-dialog-about-changelog");
            },
            rules: function() {
                var dlg_delete = $.extend({}, wp.gdrts.admin.dialogs.defaults(), {
                    dialogClass: wp.gdrts.admin.dialogs.classes("gdrts-dialog-hidex"),
                    buttons: [
                        {
                            id: "gdrts-delete-del-delete",
                            class: "gdrts-dialog-button-delete",
                            text: gdrts_data.dialog_button_delete,
                            data: { icon: "delete" },
                            click: function() {
                                window.location.href = wp.gdrts.admin.storage.url;
                            }
                        },
                        {
                            id: "gdrts-delete-del-cancel",
                            class: "gdrts-dialog-button-cancel gdrts-button-focus",
                            text: gdrts_data.dialog_button_cancel,
                            data: { icon: "cancel" },
                            click: function() {
                                $("#gdrts-dialog-rule-delete").wpdialog("close");
                            }
                        }
                    ]
                });

                $("#gdrts-dialog-rule-delete").wpdialog(dlg_delete);

                wp.gdrts.admin.dialogs.icons("#gdrts-dialog-rule-delete");
            }
        },
        addons: {
            changelog: {
                init: function() {
                    $(document).on("click", ".gdrts-addon-changelog-open", function(e){
                        e.preventDefault();

                        var changelog = $(this).next().html(),
                            title = $(this).data("addon");

                        $("#gdrts-dialog-about-changelog .gdrts-inner-content").html(changelog);

                        $("#gdrts-dialog-about-changelog").wpdialog({title: gdrts_data.dialog_changelog + ": " + title});
                        $("#gdrts-dialog-about-changelog").wpdialog("open");
                    });
                }
            }
        },
        votes: {
            init: function() {
                $(".gdrts-action-delete-entry").click(function(e){
                    e.preventDefault();

                    wp.gdrts.admin.storage.url = $(this).attr("href");

                    $("#gdrts-dialog-log-delete-single").wpdialog("open");
                });

                $(".gdrts-action-remove-entry").click(function(e){
                    e.preventDefault();

                    wp.gdrts.admin.storage.url = $(this).attr("href");

                    $("#gdrts-dialog-log-remove-single").wpdialog("open");
                });

                $("#doaction").click(function(e) {
                    e.preventDefault();

                    if ($("#bulk-action-selector-top").val() === "delete") {
                        $("#gdrts-dialog-log-delete").wpdialog("open");
                    } else if ($("#bulk-action-selector-top").val() === "remove") {
                        $("#gdrts-dialog-log-remove").wpdialog("open");
                    }
                });

                $("#doaction2").click(function(e) {
                    e.preventDefault();

                    if ($("#bulk-action-selector-bottom").val() === "delete") {
                        $("#gdrts-dialog-log-delete").wpdialog("open");
                    } else if ($("#bulk-action-selector-bottom").val() === "remove") {
                        $("#gdrts-dialog-log-remove").wpdialog("open");
                    }
                });
            }
        },
        ratings: {
            init: function() {
                $("#gdrts-ratings-submit").click(function(e){
                    $("#bulk-action-selector-top, #bulk-action-selector-bottom").val(-1);
                });

                $(".gdrts-action-clear-ratings").click(function(e){
                    e.preventDefault();

                    wp.gdrts.admin.storage.url = $(this).attr("href");

                    $("#gdrts-dialog-ratings-clear-single").wpdialog("open");
                });

                $(".gdrts-action-recalculate-ratings").click(function(e){
                    e.preventDefault();

                    wp.gdrts.admin.storage.url = $(this).attr("href");

                    $("#gdrts-dialog-ratings-recalculate-single").wpdialog("open");
                });

                $(".gdrts-action-delete-ratings").click(function(e){
                    e.preventDefault();

                    wp.gdrts.admin.storage.url = $(this).attr("href");

                    $("#gdrts-dialog-ratings-delete-single").wpdialog("open");
                });

                $("#doaction2").click(function(e) {
                    e.preventDefault();

                    if ($("#bulk-action-selector-bottom").val() !== "-1") {
                        if ($("#bulk-action-selector-bottom").val() === "delete") {
                            $("#gdrts-dialog-ratings-delete").wpdialog("open");
                        } else {
                            $("#gdrts-dialog-ratings-clear").wpdialog("open");
                        }
                    }
                });

                $("#doaction").click(function(e) {
                    e.preventDefault();

                    if ($("#bulk-action-selector-top").val() !== "-1") {
                        if ($("#bulk-action-selector-top").val() === "delete") {
                            $("#gdrts-dialog-ratings-delete").wpdialog("open");
                        } else {
                            $("#gdrts-dialog-ratings-clear").wpdialog("open");
                        }
                    }
                });
            }
        },
        types: {
            init: function() {
                $(".gdrts-types-action-entity-delete").click(function(e){
                    e.preventDefault();

                    wp.gdrts.admin.storage.url = $(this).attr("href");

                    $("#gdrts-dialog-entity-delete").wpdialog("open");
                });
            }
        },
        rules: {
            init: function() {
                $("select#object").change(function(){
                    $(".gdrts-addon-methods-group").hide();
                    $(this).closest("form").find("input[type='submit']").attr("disabled", false);

                    if ($(this).find(":selected").data("scope") === "addon") {
                        var cls = ".gdrts-addon-group-" + $(this).val();

                        if ($(cls).length === 1) {
                            $(cls).show();

                            if ($(cls).find("select").val() === "0") {
                                $(this).closest("form").find("input[type='submit']").attr("disabled", true);
                            }
                        }
                    }
                });

                $(".gdrts-action-delete-rule").click(function(e){
                    e.preventDefault();

                    wp.gdrts.admin.storage.url = $(this).attr("href");

                    $("#gdrts-dialog-rule-delete").wpdialog("open");
                });
            }
        },
        tools: {
            init: function() {
                if (gdrts_data.panel === "export") {
                    wp.gdrts.admin.tools.export();
                }

                if (gdrts_data.panel === "recalc") {
                    wp.gdrts.admin.tools.recalc();
                }

                if (gdrts_data.panel === "dbfour") {
                    wp.gdrts.admin.tools.dbfour();
                }
            },
            export: function() {
                $("#gdrts-tool-export").click(function(e){
                    e.preventDefault();

                    window.location = $("#gdrts-export-url").val();
                });
            },
            dbfour: function() {
                $("#gdrts-tool-dbfour").click(function(e){
                    if (wp.gdrts.admin.storage.progres.active) {
                        wp.gdrts.admin.dbfour.stop();
                    } else {
                        wp.gdrts.admin.dbfour.start();
                    }
                });
            },
            recalc: function() {
                wp.gdrts.admin.storage.progres.step = gdrts_data.step_recalculate;

                $("#gdrts-tool-recalc").click(function(e){
                    if (wp.gdrts.admin.storage.progres.active) {
                        wp.gdrts.admin.recalculation.stop();
                    } else {
                        var s = $(".gdrts-recalc-filter:checked"), settings = [];

                        if (s.length === 0) {
                            alert(gdrts_data.dialog_nothing);
                        } else {
                            $.each(s, function(idx, el){
                                settings.push($(el).val());
                            });

                            wp.gdrts.admin.recalculation.start(settings);
                        }
                    }
                });
            }
        },
        dbfour: {
            start: function() {
                wp.gdrts.admin.storage.progres.active = true;

                $("#gdrts-tool-dbfour").val(gdrts_data.button_stop);
                $("#gdrts-dbfour-intro").slideUp();
                $("#gdrts-dbfour-process").slideDown();

                wp.gdrts.admin.dbfour._call({ operation: "start" }, wp.gdrts.admin.dbfour.callback_start);
            },
            stop: function() {
                wp.gdrts.admin.storage.progres.stop = true;

                $("#gdrts-tool-dbfour").attr("disabled", true);
            },
            callback_start: function(json) {
                wp.gdrts.admin.storage.progres.total = json.objects;
                wp.gdrts.admin.storage.progres.pages = json.objects;

                wp.gdrts.admin.dbfour._write(json.message);

                wp.gdrts.admin.dbfour.run();
            },
            callback_stop: function(json) {
                wp.gdrts.admin.storage.progres.active = false;
                wp.gdrts.admin.dbfour._write(json.message);
            },
            callback_process: function(json) {
                if (wp.gdrts.admin.storage.progres.stop) {
                    wp.gdrts.admin.dbfour._call({ operation: "break" }, wp.gdrts.admin.dbfour.callback_stop);
                } else {
                    wp.gdrts.admin.storage.progres.current+= json.done;

                    wp.gdrts.admin.dbfour._write(json.message);

                    if (wp.gdrts.admin.storage.progres.current < wp.gdrts.admin.storage.progres.pages) {
                        wp.gdrts.admin.dbfour.run();
                    } else {
                        wp.gdrts.admin.dbfour.stop();
                        wp.gdrts.admin.dbfour._call({ operation: "stop" }, wp.gdrts.admin.dbfour.callback_stop);
                    }
                }
            },
            run: function() {
                var data = {
                    operation: "run",
                    total: wp.gdrts.admin.storage.progres.total,
                    current: wp.gdrts.admin.storage.progres.current
                };

                wp.gdrts.admin.dbfour._call(data, wp.gdrts.admin.dbfour.callback_process);
            },
            _write: function(message) {
                $("#gdrts-dbfour-progress pre").append(message + "\r\n");
            },
            _call: function(data, callback) {
                $.ajax({
                    url: ajaxurl + "?action=gdrts_tools_dbfour&_ajax_nonce=" + gdrts_data.nonce,
                    type: "post", dataType: "json", data: data, success: callback
                });
            }
        },
        recalculation: {
            start: function(settings) {
                wp.gdrts.admin.storage.progres.active = true;
                wp.gdrts.admin.storage.progres.settings = settings;

                $("#gdrts-tool-recalc").val(gdrts_data.button_stop);
                $("#gdrts-recalc-intro").slideUp();
                $("#gdrts-recalc-process").slideDown();

                wp.gdrts.admin.recalculation._call({ operation: "start" }, wp.gdrts.admin.recalculation.callback_start);
            },
            stop: function() {
                wp.gdrts.admin.storage.progres.stop = true;

                $("#gdrts-tool-recalc").attr("disabled", true);
            },
            callback_start: function(json) {
                wp.gdrts.admin.storage.progres.total = json.objects;
                wp.gdrts.admin.storage.progres.pages = Math.ceil(json.objects / wp.gdrts.admin.storage.progres.step);

                wp.gdrts.admin.recalculation._write(json.message);

                wp.gdrts.admin.recalculation.run();
            },
            callback_stop: function(json) {
                wp.gdrts.admin.storage.progres.active = false;
                wp.gdrts.admin.recalculation._write(json.message);
            },
            callback_process: function(json) {
                if (wp.gdrts.admin.storage.progres.stop) {
                    wp.gdrts.admin.recalculation._call({ operation: "break" }, wp.gdrts.admin.recalculation.callback_stop);
                } else {
                    wp.gdrts.admin.storage.progres.current++;

                    wp.gdrts.admin.recalculation._write(json.message);

                    if (wp.gdrts.admin.storage.progres.current < wp.gdrts.admin.storage.progres.pages) {
                        wp.gdrts.admin.recalculation.run();
                    } else {
                        wp.gdrts.admin.recalculation.stop();
                        wp.gdrts.admin.recalculation._call({ operation: "stop" }, wp.gdrts.admin.recalculation.callback_stop);
                    }
                }
            },
            run: function() {
                var data = {
                    operation: "run",
                    total: wp.gdrts.admin.storage.progres.total,
                    current: wp.gdrts.admin.storage.progres.current,
                    step: wp.gdrts.admin.storage.progres.step,
                    settings: wp.gdrts.admin.storage.progres.settings
                };

                wp.gdrts.admin.recalculation._call(data, wp.gdrts.admin.recalculation.callback_process);
            },
            _write: function(message) {
                $("#gdrts-recalc-progress pre").prepend(message + "\r\n");
            },
            _call: function(data, callback) {
                $.ajax({
                    url: ajaxurl + "?action=gdrts_tools_recalc&_ajax_nonce=" + gdrts_data.nonce,
                    type: "post", dataType: "json", data: data, success: callback
                });
            }
        },
        settings: {
            init: function() {
                $(".gdrts-style-type-selection select").change(function(e){
                    var type = $(this).val();

                    $(".gdrts-select-type").removeClass("gdrts-select-type-show");
                    $(".gdrts-sel-type-" + type).addClass("gdrts-select-type-show");
                });
            }
        }
    };

    wp.gdrts.admin.init();
})(jQuery, window, document);
