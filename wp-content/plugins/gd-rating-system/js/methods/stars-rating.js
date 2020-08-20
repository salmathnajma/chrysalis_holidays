/*jslint regexp: true, nomen: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
/*global gdrts_rating_data*/

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
