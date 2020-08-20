/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
/*global gdrts_rating_data*/

;(function($) {
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
}(jQuery));

;(function($, window, document, undefined) {
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

;(function($, window, document, undefined) {
    window.wp.gdrts.core.like_this = {
        run: function() {
            wp.gdrts.hooks.addAction("gdrts-core-common-methods-run", function(from, el){
                window.wp.gdrts.core.like_this.single.init(el);
                window.wp.gdrts.core.like_this.list.init(el);
            });

            wp.gdrts.hooks.addAction("gdrts-dynamic-process-onload", function(from){
                if ($(from).hasClass("gdrts-method-like-this")) {
                    window.wp.gdrts.core.like_this.single.process($(".gdrts-like-this", from));
                }
            });
        },
        single: {
            init: function(el) {
                $(".gdrts-rating-block .gdrts-like-this", el).each(function(){
                    window.wp.gdrts.core.like_this.single.process(this);
                });

                $(document).on("mouseover", ".gdrts-likes-theme-expanding .gdrts-like:not(.gdrts-like-expanded)", function(){
                    $(this).addClass("gdrts-like-expanded");

                    $(".gdrts-like-this-suffix", this).animate({"margin-left": "5px"}, 100, function(){
                        $(this).animate({"max-width": "256px"}, 150, function(){
                            $(this).parent().next().animate({"padding": "5px 10px 5px 20px"}, 100, function(){
                                $(this).animate({"max-width": "512px"}, 150);
                            });
                        });
                    });
                });
            },
            call: function(el, rating) {
                var obj = window.wp.gdrts.core._b(el),
                    data = window.wp.gdrts.core._d(el),
                    args = {
                        todo: "vote",
                        method: "like-this",
                        item: data.item.item_id,
                        nonce: data.item.nonce,
                        render: data.render,
                        uid: data.uid,
                        meta: {
                            value: rating
                        }
                    };

                wp.gdrts.hooks.doAction("gdrts-rating-method-pre-ajax", "like-this", obj, args);

                window.wp.gdrts.help.remote.call(args, window.wp.gdrts.core.like_this.single.voted, window.wp.gdrts.help.remote.error);
            },
            voted: function(json) {
                if (json.status === "error") {
                    window.wp.gdrts.core.common.error(json);
                } else {
                    var obj = $(json.render).hide();

                    $("#gdrts-unique-id-" + json.uid).fadeOut(150, function(){
                        $(this).replaceWith(obj);

                        obj.fadeIn(300, function(){
                            window.wp.gdrts.core.common.process(this);
                            window.wp.gdrts.core.like_this.single.process($(".gdrts-like-this", this));

                            $(this).attr("role", "alert");

                            wp.gdrts.hooks.doAction("gdrts-like-this-voted", $(this));
                            wp.gdrts.hooks.doAction("gdrts-rating-block-loaded", $(this), "like-this");
                        });
                    });
                }
            },
            adjust: function(el) {
                var inline = window.wp.gdrts.core._b(el).find(".gdrts-rating-text.gdrts-text-inline"),
                    please = window.wp.gdrts.core._b(el).find(".gdrts-rating-please-wait.gdrts-text-inline"),
                    rating = $(".gdrts-like-link", el), height = rating.outerHeight();

                if (inline.length) {
                    inline.css("height", height + "px").css("line-height", height + "px");
                }

                if (please.length) {
                    please.css("height", height + "px").css("line-height", height + "px");
                }
            },
            process: function(el) {
                var data = window.wp.gdrts.core._d(el).likes;

                window.wp.gdrts.core.like_this.single.adjust(el);

                if ($(el).hasClass("gdrts-with-fonticon")) {
                    var key = data.type + "-" + data.name,
                        obj = {
                            font: data.name,
                            name: data.type,
                            like: window.wp.gdrts.help.char(data.chars.like),
                            liked: window.wp.gdrts.help.char(data.chars.liked),
                            clear: window.wp.gdrts.help.char(data.chars.clear)
                        };

                    window.wp.gdrts.core.style.like(key, obj);
                }

                if ($(el).hasClass("gdrts-state-active")) {
                    window.wp.gdrts.core.like_this.single.activity(el, data);
                }

                wp.gdrts.hooks.doAction("gdrts-like-this-single-init", el);
            },
            activity: function(el, data) {
                $(".gdrts-sr-button", el).click(function(e) {
                    e.preventDefault();

                    window.wp.gdrts.core.like_this.single.do_rating($(this).data("rating"), $(this).parent(), el);
                });

                $(".gdrts-like-link", el).click(function(e){
                    var rating = $(this).find(".gdrts-clear-symbol, .gdrts-like-symbol").data("rating");

                    window.wp.gdrts.core.like_this.single.do_rating(rating, this, el);
                });
            },
            do_rating: function(rating, t, el) {
                if (window.wp.gdrts.core._b(t).hasClass("gdrts-vote-saving")) {
                    return;
                }

                var obj = window.wp.gdrts.core._b(el),
                    _do_vote = wp.gdrts.hooks.applyFilters("gdrts-like-this-do-vote", true, rating, obj);

                if (_do_vote) {
                    window.wp.gdrts.core._b($(t).parent()).addClass("gdrts-vote-saving");

                    window.wp.gdrts.core.like_this.single.call(el, rating);
                } else {
                    wp.gdrts.hooks.doAction("gdrts-like-this-vote-prevented", rating, obj);
                }
            }
        },
        list: {
            init: function(el) {
                $(".gdrts-rating-list .gdrts-like-this", el).each(function(){
                    window.wp.gdrts.core.like_this.list.process(this);
                });
            },
            process: function(el) {
                if ($(el).data().hasOwnProperty("gdrts")) {
                    return;
                }

                $(el).data("gdrts", "done");

                var data = window.wp.gdrts.core._d(el).likes;

                if ($(el).hasClass("gdrts-with-fonticon")) {
                    var key = data.type + "-" + data.name,
                        obj = {
                            font: data.name,
                            name: data.type,
                            like: window.wp.gdrts.help.char(data.chars.like),
                            liked: window.wp.gdrts.help.char(data.chars.liked),
                            clear: window.wp.gdrts.help.char(data.chars.clear)
                        };

                    window.wp.gdrts.core.style.like(key, obj);
                }

                wp.gdrts.hooks.doAction("gdrts-like-this-list-init", el);
            }
        }
    };

    window.wp.gdrts.core.like_this.run();
})(jQuery, window, document);

;(function($, window, document, undefined) {
    window.wp.gdrts.core.stars_rating = {
        run: function() {
            wp.gdrts.hooks.addAction("gdrts-core-common-methods-run", function(from, el){
                window.wp.gdrts.core.stars_rating.single.init(el);
                window.wp.gdrts.core.stars_rating.list.init(el);
            });

            wp.gdrts.hooks.addAction("gdrts-dynamic-process-onload", function(from){
                if ($(from).hasClass("gdrts-method-stars-rating")) {
                    window.wp.gdrts.core.stars_rating.single.process($(".gdrts-stars-rating", from));
                }
            });
        },
        single: {
            init: function(el) {
                $(".gdrts-rating-block .gdrts-stars-rating", el).each(function(){
                    window.wp.gdrts.core.stars_rating.single.process(this);
                });
            },
            call: function(el, rating) {
                var obj = window.wp.gdrts.core._b(el),
                    data = window.wp.gdrts.core._d(el),
                    args = {
                        todo: "vote",
                        method: "stars-rating",
                        item: data.item.item_id,
                        nonce: data.item.nonce,
                        render: data.render,
                        uid: data.uid,
                        meta: {
                            value: rating,
                            max: data.stars.max
                        }
                    };

                wp.gdrts.hooks.doAction("gdrts-rating-method-pre-ajax", "stars-rating", obj, args);

                window.wp.gdrts.help.remote.call(args, window.wp.gdrts.core.stars_rating.single.voted, window.wp.gdrts.help.remote.error);
            },
            voted: function(json) {
                if (json.status === "error") {
                    window.wp.gdrts.core.common.error(json);
                } else {
                    var obj = $(json.render).hide();

                    $("#gdrts-unique-id-" + json.uid).fadeOut(150, function(){
                        $(this).replaceWith(obj);

                        obj.fadeIn(300, function(){
                            window.wp.gdrts.core.common.process(this);
                            window.wp.gdrts.core.stars_rating.single.process($(".gdrts-stars-rating", this));

                            $(this).attr("role", "alert");

                            wp.gdrts.hooks.doAction("gdrts-stars-rating-voted", $(this));
                            wp.gdrts.hooks.doAction("gdrts-rating-block-loaded", $(this), "stars-rating");
                        });
                    });
                }
            },
            process: function(el) {
                if ($(el).data().hasOwnProperty("gdrts")) {
                    return;
                }

                $(el).data("gdrts", "done");

                var data = window.wp.gdrts.core._d(el).stars,
                    labels = window.wp.gdrts.core._d(el).labels;

                if ($(el).hasClass("gdrts-with-fonticon")) {
                    var key = data.type + "-" + data.name + "-" + data.max,
                        obj = {font: data.name,
                            name: data.type,
                            length: data.max,
                            content: Array(data.max + 1).join(window.wp.gdrts.help.char(data.char))};

                    window.wp.gdrts.core.style.star(key, obj);
                }

                if ($(el).hasClass("gdrts-state-active")) {
                    window.wp.gdrts.core.stars_rating.single.activity(el, data, labels);
                }

                if (data.responsive) {
                    $(window).on("load resize orientationchange", {el: el, data: data}, window.wp.gdrts.core.responsive.stars);

                    window.wp.gdrts.core.responsive._s({el: el, data: data});
                }

                wp.gdrts.hooks.doAction("gdrts-stars-rating-single-init", el);
            },
            activity: function(el, data, labels) {
                $(".gdrts-stars-empty", el).mouseleave(function(e){
                    if ($(this).hasClass("gdrts-vote-saving")) {
                        return;
                    }

                    var reset = $(this).parent().find("input").val(),
                        width = $(this).width(),
                        star = width / data.max,
                        current = star * reset;

                    $(el).data("selected", reset).attr("title", "");
                    $(".gdrts-stars-active", this).width(current);
                });

                $(".gdrts-stars-empty", el).mousemove(function(e){
                    if ($(this).hasClass("gdrts-vote-saving")) {
                        return;
                    }

                    var offset = $(this).offset(),
                        width = $(this).width(),
                        star = width / data.max,
                        res = data.resolution,
                        step = res * (star / 100),
                        steps = (data.max * star) / step,
                        x = e.pageX - offset.left,
                        parts = gdrts_rating_data.rtl ?
                            steps - Math.ceil(x / step) + 1 :
                            Math.ceil(x / step),
                        current = parseFloat((parts * (res / 100)).toFixed(2)),
                        lid = Math.ceil(current * 1),
                        label = labels[lid - 1],
                        active = parts * step;

                    $(el).data("selected", current).attr("title", current + ": " + label);
                    $(".gdrts-stars-active", this).width(active);
                });

                $(".gdrts-sr-button", el).click(function(e) {
                    e.preventDefault();

                    var select = $(this).parent().find(".gdrts-sr-rating");

                    window.wp.gdrts.core.stars_rating.single.do_rating($(select).val(), select.parent(), el);
                });

                $(".gdrts-stars-empty", el).click(function(e){
                    e.preventDefault();

                    window.wp.gdrts.core.stars_rating.single.do_rating($(el).data("selected"), this, el);
                });
            },
            do_rating: function(rating, t, el) {
                if ($(t).hasClass("gdrts-vote-saving")) {
                    return;
                }

                $(t).parent().find("input").val(rating);

                if ($(t).parent().hasClass("gdrts-passive-rating")) {
                    return;
                }

                var obj = window.wp.gdrts.core._b(el),
                    _do_vote = wp.gdrts.hooks.applyFilters("gdrts-stars-rating-do-vote", true, rating, obj);

                if (_do_vote) {
                    $(t).addClass("gdrts-vote-saving");

                    window.wp.gdrts.core._b($(t).parent()).addClass("gdrts-vote-saving");

                    window.wp.gdrts.core.stars_rating.single.call(el, rating);
                } else {
                    wp.gdrts.hooks.doAction("gdrts-stars-rating-vote-prevented", rating, obj);
                }
            }
        },
        list: {
            init: function(el) {
                $(".gdrts-rating-list .gdrts-stars-rating", el).each(function(){
                    window.wp.gdrts.core.stars_rating.list.process(this);
                });
            },
            process: function(el) {
                if ($(el).data().hasOwnProperty("gdrts")) {
                    return;
                }

                $(el).data("gdrts", "done");

                var data = window.wp.gdrts.core._d(el).stars;

                if ($(el).hasClass("gdrts-with-fonticon")) {
                    var key = data.type + "-" + data.name + "-" + data.max,
                        obj = {font: data.name,
                            name: data.type,
                            length: data.max,
                            content: Array(data.max + 1).join(window.wp.gdrts.help.char(data.char))};

                    window.wp.gdrts.core.style.star(key, obj);
                }

                if (data.responsive) {
                    $(window).on("load resize orientationchange", {el: el, data: data}, window.wp.gdrts.core.responsive.stars);

                    window.wp.gdrts.core.responsive._s({el: el, data: data});
                }

                wp.gdrts.hooks.doAction("gdrts-stars-rating-list-init", el);
            }
        }
    };

    window.wp.gdrts.core.stars_rating.run();
})(jQuery, window, document);
