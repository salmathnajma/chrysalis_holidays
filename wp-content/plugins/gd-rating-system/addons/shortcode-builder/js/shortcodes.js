/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
/*global gdrts_data*/

;(function($, window, document, undefined) {
    window.wp = window.wp || {};
    window.wp.gdrts = window.wp.gdrts || {};

    window.wp.gdrts.shortcodes = {
        init: function() {
            this.render();

            $(document).on("change keyup", ".gdrts-attribute, .gdrts-attr-value input, .gdrts-attr-value select", wp.gdrts.shortcodes.render);

            var clipboard = new ClipboardJS('.gdrts-shortcode-copy button', {
                text: function(trigger) {
                    return $("#gdrts-shortcode-block").html();
                }
            });

            $(".gdrts-color-picker").wpColorPicker({
                change: wp.gdrts.shortcodes.render
            });

            var args = {
                allowInput: true,
                dateFormat: "Y-m-d H:i:S",
                enableTime: true,
                enableSeconds: true,
                time_24hr: true
            };

            if (gdrts_data.flatpickr_locale !== "") {
                args.locale = gdrts_data.flatpickr_locale;
            }

            $(".gdrts-datetime-picker").flatpickr(args);
        },
        render: function() {
            var render = "", group = $(".d4p-group-arguments"),
                shortcode = group.data("shortcode"),
                inner = group.data("inner") === "yes",
                attributes = wp.gdrts.shortcodes.attributes();

            render += "[" + shortcode;

            $.each(attributes, function(idx, val){
                render += " " + idx + "='" + val + "'";
            });

            render += "]";

            if (inner) {
                render += "&lt;!-- --- -->[/" + shortcode + "]";
            }

            $("#gdrts-shortcode-block").html(render);
        },
        attributes: function() {
            var attr = {}, defaults = {};

            $(".d4p-group-arguments .gdrts-unit").each(function(){
                var f = $(this), data = f.data(), def = data.default;

                if (data.type == "checkbox") {
                    def = def ? "true" : "false";
                }

                defaults[data.name] = def;
            });

            $(".d4p-group-arguments .gdrts-unit").each(function(){
                var f = $(this), data = f.data(), def = data.default, inc,
                    selected, temp, vx, attribute = $(".gdrts-attribute", f);

                if (attribute.is(":checked")) {
                    switch (data.type) {
                        case 'number':
                            selected = $(".gdrts-attr-value input[type=number]", f).val();
                            selected = parseInt(selected);
                            break;
                        case 'multi':
                            temp = [];
                            $(".gdrts-attr-value input[type=checkbox]:checked", f).each(function(){
                                temp.push($(this).val());
                            });
                            selected = temp.join(", ");
                            break;
                        case 'select':
                            selected = $(".gdrts-attr-value select", f).val().trim();
                            break;
                        case 'checkbox':
                            selected = $(".gdrts-attr-value select", f).val().trim();
                            def = def ? "true" : "false";
                            break;
                        case 'color':
                        case 'datetime':
                        case 'text':
                            selected = $(".gdrts-attr-value input[type=text]", f).val().trim();
                            break;
                    }

                    if (selected !== def) {
                        inc = true;

                        $.each(data.rule, function(idx, val){
                            vx = val + '';

                            if (attr[idx] !== undefined) {
                                if (vx.endsWith('%%')) {
                                    inc = attr[idx].startsWith(vx.substr(0, vx.length - 2));
                                } else {
                                    inc = attr[idx] === val;
                                }
                            } else {
                                if (vx.endsWith('%%')) {
                                    inc = defaults[idx].startsWith(vx.substr(0, vx.length - 2));
                                } else {
                                    inc = defaults[idx] === val;
                                }
                            }
                        });

                        if (inc) {
                            attr[data.name] = selected;
                        }
                    }
                }
            });

            return attr;
        }
    };

    wp.gdrts.shortcodes.init();
})(jQuery, window, document);
