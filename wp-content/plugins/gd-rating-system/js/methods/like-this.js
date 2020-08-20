/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
/*global gdrts_rating_data*/

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
