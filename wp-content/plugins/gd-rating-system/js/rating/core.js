/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
/*global gdrts_rating_data*/

;(function($, window, document, undefined) {
    $.fn.get_hidden_dims = function (includeMargin) {
        var oldProps = [], $item = this, $hiddenParents = $item.parents().addBack().not(':visible'),
            props = { position: 'absolute', visibility: 'hidden', display: 'block' },
            dim = { width: 0, height: 0, innerWidth: 0, innerHeight: 0, outerWidth: 0, outerHeight: 0 };

        includeMargin = (includeMargin === null) ? false : includeMargin;

        $hiddenParents.each(function() {
            var old = {}, $this = this;

            $.each(props, function(name, value){
                old[name] = $this.style[name];
                $this.style[name] = value;
            });

            oldProps.push(old);
        });

        dim.width = $item.width();
        dim.outerWidth = $item.outerWidth(includeMargin);
        dim.innerWidth = $item.innerWidth();
        dim.height = $item.height();
        dim.innerHeight = $item.innerHeight();
        dim.outerHeight = $item.outerHeight(includeMargin);

        $hiddenParents.each(function (i) {
            var old = oldProps[i], $this = this;

            $.each(props, function(name, value){
                $this.style[name] = old[name];
            });
        });

        return dim;
    };

    window.wp = window.wp || {};
    window.wp.gdrts = window.wp.gdrts || {};

    window.wp.gdrts.help = {
        remote: {
            url: function() {
                return gdrts_rating_data.url + "?action=" + gdrts_rating_data.handler;
            },
            call: function(args, callback, callerror) {
                $.ajax({
                    url: this.url(),
                    type: "post",
                    dataType: "json",
                    data:  {
                        req: JSON.stringify(args)
                    },
                    success: callback,
                    error: callerror
                });
            },
            error: function(jqXhr, textStatus, errorThrown) {
                var json = {
                    uid: 0,
                    message: ""
                };

                if (typeof jqXhr.responseJSON === "object") {
                    if (jqXhr.responseJSON.hasOwnProperty("uid")) {
                        json.uid = parseInt(jqXhr.responseJSON.uid);

                        if (jqXhr.responseJSON.hasOwnProperty("message")) {
                            json.message = jqXhr.responseJSON.message;
                        }
                    }
                }

                if (json.message === "") {
                    json.message = "Uncaught Error: " + errorThrown;

                    if (jqXhr.status === 0) {
                        json.message = "No internet connection.";
                    } else if (jqXhr.status === 404) {
                        json.message = "Requested page not found.";
                    } else if (jqXhr.status === 500) {
                        json.message = "Internal Server Error.";
                    } else if (textStatus === "timeout") {
                        json.message = "Request timed out.";
                    } else if (textStatus === "abort") {
                        json.message = "Request aborted.";
                    }
                }

                if (!isNaN(json.uid) && json.uid > 0) {
                    wp.gdrts.core.common.error(json);
                }

                var message = 'AJAX ERROR: GD Rating System - ' + json.message;

                if (gdrts_rating_data.ajax_error === "alert") {
                    alert(message);
                } else if (gdrts_rating_data.ajax_error === "console") {
                    if (window.console) {
                        console.log(message);
                    }
                }
            }
        },
        char: function(chr) {
            if (chr.substring(0, 3) === "&#x") {
                chr = chr.replace(";", "");
                chr = parseInt(chr.substring(3), 16);
                chr = String.fromCharCode(chr);
            } else if (chr.substring(0, 2) === "&#") {
                chr = chr.replace(";", "");
                chr = parseInt(chr.substring(2), 10);
                chr = String.fromCharCode(chr);
            }

            return chr;
        }
    };

    window.wp.gdrts.dynamic = {
        init: function() {
            var args = {
                todo: "dynamic",
                items: []
            };

            $(".gdrts-dynamic-block").each(function(){
                args.items.push(window.wp.gdrts.dynamic.process(this));
            });

            window.wp.gdrts.help.remote.call(args, window.wp.gdrts.dynamic.load, window.wp.gdrts.help.remote.error);
        },
        process: function(el) {
            var data = JSON.parse($(".gdrts-rating-data", $(el)).html());

            data.did = window.wp.gdrts.core.storage.did;

            $(el).attr("id", "gdrts-dynamic-id-" + window.wp.gdrts.core.storage.did)
                 .addClass("gdrts-dynamic-loading");

            window.wp.gdrts.core.storage.did++;

            return data;
        },
        load: function(json) {
            if (json.status && json.items && json.status === "ok") {
                $.each(json.items, function(idx, item){
                    window.wp.gdrts.dynamic.item(item);
                });
            }
        },
        item: function(item) {
            var obj = $(item.render).hide();

            $("#gdrts-dynamic-id-" + item.did).fadeOut(150, function(){
                $(this).replaceWith(obj);

                obj.fadeIn(300, function(){
                    window.wp.gdrts.core.common.process(this);

                    wp.gdrts.hooks.doAction("gdrts-dynamic-process-onload", this);

                    wp.gdrts.hooks.doAction("gdrts-rating-block-loaded", this, "dynamic-load");
                });
            });
        }
    };

    window.wp.gdrts.custom = {
        init: function(el) {
            window.wp.gdrts.custom.likes.init(el);
            window.wp.gdrts.custom.stars.init(el);
        },
        likes: {
            init: function(el) {
                $(".gdrts-custom-like-block", el).not(".gdrts-custom-done").each(function(){
                    window.wp.gdrts.custom.likes.process(this);
                });
            },
            process: function(el) {
                $(el).addClass("gdrts-custom-done");

                if ($(el).hasClass("gdrts-with-fonticon")) {
                    var data = $(el).data();

                    var key = data.type + "-" + data.name,
                        obj = {
                            font: data.name,
                            name: data.type,
                            like: window.wp.gdrts.help.char(data.char),
                            liked: window.wp.gdrts.help.char(data.char),
                            clear: window.wp.gdrts.help.char(data.char)
                        };

                    window.wp.gdrts.core.style.like(key, obj);
                }
            }
        },
        stars: {
            init: function(el) {
                $(".gdrts-custom-stars-block", el).not(".gdrts-custom-done").each(function(){
                    window.wp.gdrts.custom.stars.process(this);
                });
            },
            process: function(el) {
                $(el).addClass("gdrts-custom-done");

                if ($(el).hasClass("gdrts-with-fonticon")) {
                    var data = $(el).data();

                    var key = data.type + "-" + data.name + "-" + data.max,
                        obj = {
                            font: data.name,
                            name: data.type,
                            length: data.max,
                            content: Array(data.max + 1).join(window.wp.gdrts.help.char(data.char))
                        };

                    window.wp.gdrts.core.style.star(key, obj);

                    if (data.responsive == 1) {
                        $(window).on("load resize orientationchange", {el: el, data: data}, window.wp.gdrts.core.responsive.stars);

                        window.wp.gdrts.core.responsive._s({el: el, data: data});
                    }
                }
            }
        }
    };

    window.wp.gdrts.core = {
        storage: {
            uid: 1,
            did: 1,
            stars: [],
            likes: []
        },

        _b: function(el) {
            var uid = $(el).data("uid");
            return $("#gdrts-unique-id-" + uid);
        },
        _d: function(el) {
            return this._b(el).data("rating");
        },

        run: function() {
            this.init();
            this.live();

            wp.gdrts.hooks.addAction("gdrts-rating-block-loaded", function(el, method){
                window.wp.gdrts.custom.init(el);
            });

            window.wp.gdrts.custom.init($("body"));
        },

        ajax: function() {
            this.live();

            if ($(".gdrts-dynamic-block").length > 0) {
                window.wp.gdrts.dynamic.init();
            }

            $(".gdrts-rating-block, .gdrts-rating-list").each(function(){
                window.wp.gdrts.core.common.process(this);
            });

            var body = $("body");
            window.wp.gdrts.core.common.methods(body);
            window.wp.gdrts.custom.init(body);

            wp.gdrts.hooks.doAction("gdrts-ajax-call-finish");
        },

        init: function() {
            wp.gdrts.hooks.doAction("gdrts-core-init-start");

            if ($(".gdrts-dynamic-block").length > 0) {
                window.wp.gdrts.dynamic.init();
            }

            $(".gdrts-rating-block, .gdrts-rating-list").each(function(){
                window.wp.gdrts.core.common.process(this);
            });

            window.wp.gdrts.core.common.methods($("body"));

            wp.gdrts.hooks.doAction("gdrts-core-init-finish");
        },
        live: function() {
            $(document).on("click", ".gdrts-toggle-distribution", function(e){
                e.preventDefault();

                var open = $(this).hasClass("gdrts-toggle-open");

                if (open) {
                    $(this).removeClass("gdrts-toggle-open");
                    $(this).html($(this).data("show"));

                    $(".gdrts-rating-distribution", $(this).closest(".gdrts-rating-block")).slideUp();
                } else {
                    $(this).addClass("gdrts-toggle-open");
                    $(this).html($(this).data("hide"));

                    $(".gdrts-rating-distribution", $(this).closest(".gdrts-rating-block")).slideDown();
                }
            });
        },
        style: {
            star: function(key, obj) {
                if ($.inArray(key, window.wp.gdrts.core.storage.stars) === -1) {
                    var base = ".gdrts-with-fonticon.gdrts-fonticon-" + obj.name + ".gdrts-" + obj.name + "-" + obj.font + ".gdrts-stars-length-" + obj.length,
                        rule = base + " .gdrts-stars-empty::before, " + 
                               base + " .gdrts-stars-active::before, " + 
                               base + " .gdrts-stars-current::before { " +
                               "content: \"" + obj.content + "\"; }",
                        desc = "/* stars: " + obj.name + " - " + obj.font + " - " + obj.length + " */",
                        id = "gdrts-style-stars-" + obj.name + "-" + obj.font+ "-" + obj.length;

                    $("<style type=\"text/css\" id=\"" + id + "\">\r\n" + desc + "\r\n" + rule + "\r\n\r\n</style>").appendTo("head");

                    window.wp.gdrts.core.storage.stars.push(key);
                }
            },
            like: function(key, obj) {
                if ($.inArray(key, window.wp.gdrts.core.storage.likes) === -1) {
                    var base = ".gdrts-with-fonticon.gdrts-fonticon-" + obj.name + ".gdrts-" + obj.name + "-" + obj.font + " .gdrts-like-this-symbol",
                        rule = base + ".gdrts-like-symbol::before { content: \"" + obj.like + "\"; }" + 
                               base + ".gdrts-liked-symbol::before { content: \"" + obj.liked + "\"; }" + 
                               base + ".gdrts-clear-symbol::before { content: \"" + obj.clear + "\"; }",
                        desc = "/* likes: " + obj.name + " - " + obj.font + " */",
                        id = "gdrts-style-likes-" + obj.name + "-" + obj.font;

                    $("<style type=\"text/css\" id=\"" + id + "\">\r\n" + desc + "\r\n" + rule + "\r\n\r\n</style>").appendTo("head");

                    window.wp.gdrts.core.storage.likes.push(key);
                }
            }
        },
        common: {
            process: function(el) {
                if (!$(el).data().hasOwnProperty("rating")) {
                    var data = JSON.parse($(".gdrts-rating-data", $(el)).html());

                    data.uid = window.wp.gdrts.core.storage.uid;

                    $(el).attr("id", "gdrts-unique-id-" + window.wp.gdrts.core.storage.uid)
                         .data("uid", window.wp.gdrts.core.storage.uid)
                         .data("rating", data);

                    $(".gdrts-rating-element", el).data("uid", window.wp.gdrts.core.storage.uid);

                    if ($(el).hasClass("gdrts-rating-list")) {
                        wp.gdrts.hooks.doAction("gdrts-rating-list-process", el);
                    } else {
                        wp.gdrts.hooks.doAction("gdrts-rating-block-process", el);
                    }

                    window.wp.gdrts.core.storage.uid++;
                }
            },
            methods: function(el) {
                wp.gdrts.hooks.doAction("gdrts-core-common-methods-run", this, el);
            },
            error: function(json) {
                var block = $("#gdrts-unique-id-" + json.uid);

                block.removeClass("gdrts-vote-saving")
                     .append('<div class="gdrts-error-message">' +
                            json.message + 
                            '</div>');

                $(".gdrts-rating-please-wait", block).hide();
            }
        },

        responsive: {
            _s: function(input) {
                var el = input.el,
                    parent_dim = $(el).parent(),
                    data = input.data,
                    available = parent_dim.width(),
                    new_size = Math.floor(available / data.max);

                new_size = new_size > data.size || new_size === 0 ? data.size : new_size;

                if (data.type === "image") {
                    $(".gdrts-stars-empty, .gdrts-stars-active, .gdrts-stars-current", el).css("background-size", new_size + "px");
                    $(el).css("height", new_size + "px").css("width", data.max * new_size + "px");
                } else {
                    $(".gdrts-stars-empty", el).css("font-size", new_size + "px").css("line-height", new_size + "px");
                    $(el).css("line-height", new_size + "px").css("height", new_size + "px");
                }
            },
            stars: function(e) {
                window.wp.gdrts.core.responsive._s(e.data);
            }
        }
    };
})(jQuery, window, document);
