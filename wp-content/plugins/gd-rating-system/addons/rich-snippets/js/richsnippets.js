/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
/*global gdrts_data*/

;(function ($, window, document, undefined) {
    $.fn.gdrtsHasAttr = function (name) {
        return this.attr(name) !== undefined;
    };

    window.wp = window.wp || {};
    window.wp.gdrts = window.wp.gdrts || {};

    window.wp.gdrts.snippets = {
        init: function () {
            wp.gdrts.snippets.fields($(".d4plib-metabox-wrapper"));

            $("#gdrts-rich-snippets-settings-switch").change(function () {
                if ($(this).val() === "custom") {
                    $(".gdrts-meta-rich-snippet-additional-settings").show();
                } else {
                    $(".gdrts-meta-rich-snippet-additional-settings").hide();
                }
            });

            $("#gdrts-rich-snippets-modes-switch").change(function () {
                var model = $(this).val(), container = $(this)
                    .closest(".gdrts-metabox-wrapper")
                    .find(".gdrts-metabox-wrapper-right");

                $(".gdrts-snippet-model", container).hide();
                $(".gdrts-snippet-model-" + model, container).show();
            });

            $(".gdrts-rich-snippets-mode-block-switch").change(function () {
                var id = $(this).attr("id"),
                    val = $(this).val();

                $("." + id).hide();
                $("#" + id + '-' + val).show();
            });

            $(document).on("click", ".gdrts-rich-snippet-block-remove", function () {
                $(this).closest(".gdrts-rich-snippet-block-item").fadeOut(function () {
                    $(this).remove();
                });
            });

            $(".gdrts-rich-snippet-block-add-new").click(function () {
                var nextid = $(this).data("nextid"), repkey = $(this).data("repkey"),
                    attrs = ['for', 'id', 'name'], j, a, v,
                    offer = $(this).closest("div").find(".gdrts-offer-wrapper-hidden").clone();

                $(offer).find("label, input, select").each(function () {
                    for (j = 0; j < attrs.length; j++) {
                        a = attrs[j];

                        if ($(this).gdrtsHasAttr(a)) {
                            v = $(this).attr(a).replace(repkey, nextid);
                            $(this).attr(a, v);
                        }
                    }
                });

                offer.removeClass("gdrts-offer-wrapper-hidden");

                $(this).before(offer);

                wp.gdrts.snippets.fields(offer);

                nextid++;

                $(this).data("nextid", nextid);
            });
        },
        fields: function (el) {
            var args = {
                dateFormat: "Y-m-d"
            };

            if (gdrts_data.flatpickr_locale !== "") {
                args.locale = gdrts_data.flatpickr_locale;
            }

            $(".gdrts-field-is-date", el).flatpickr(args);
        }
    };

    wp.gdrts.snippets.init();
})(jQuery, window, document);
