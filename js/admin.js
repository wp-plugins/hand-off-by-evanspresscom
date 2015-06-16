(function($) {
    $(document).ready(function() {
        //ADMIN BAR
        var adminbar = $("#wpadminbar");
        var custom_adminbar = $("#wpHandoff-admin-bar");
        var menu = $(".wpHandoff-admin-bar-menu", custom_adminbar);
        var menu_list = $(".wpHandoff-admin-bar-menus", menu).eq(0);
        var submenu = $(".wpHandoff-admin-bar-submenu", custom_adminbar);
        var arrow = $(".wpHandoff-admin-bar-show", custom_adminbar);

        custom_adminbar.css({
           background: adminbar.css('background-color'),
        });
        $("a", submenu).css({
           background: adminbar.css('background-color'),
        });

        arrow.on(window.interact.touchEnd, function(e) {
            e.preventDefault();
            e.stopPropagation();    //don't trigger document event
            var that = $(this);
            var height = 0;

            $("span", that).toggleClass('hide');

            if(menu.hasClass("hide")) {
                $(window).trigger('resize');
                menu.css({
                   height: 0,
                });
                menu.removeClass('hide');
                menu.children().each(function() {
                    var that = $(this);
                    if(that.outerHeight(true) > height) {
                        height = that.height();
                    }
                });
                menu.animate({
                    height: height
                }, {
                    queue: false,
                    duration: "fast",
                    complete: function() {
                        $(this).css({
                           height: 'auto',
                           minHeight: $(this).children().eq(1).outerHeight(true),
                        });
                        $(window).trigger('resize');
                    }
                });
                custom_adminbar.animate({
                    top: adminbar.outerHeight(true),
                }, {
                    queue: false,
                    duration: "fast",
                    complete: function() {
                        $(this).css({
                            'z-index': 99998
                        });
                    }
                });
            } else {
                var minh = parseFloat(menu.css("min-height"));
                var h = parseFloat(menu.css("height"));

                if(minh > h) {
                    h = minh;
                }

                menu.removeAttr('style');
                menu.css({
                    height: h,
                });
                menu.animate({
                    height: 0,
                }, {
                    queue: false,
                    duration: "fast",
                    complete: function() {
                        $(this).addClass('hide');
                        $(this).css({
                            height: 'auto',
                        });
                    }
                });
                custom_adminbar.css({
                   'z-index': 999999,
                });
                custom_adminbar.animate({
                    top: 0,
                }, {
                    queue: false,
                    duration: "fast"
                });
            }
        });

        if(! menu_list.children().length && custom_adminbar.length) { //frontend
            $.ajax({
                url: custom_adminbar.attr("data-admin-url"),
                success: function(data) {
                    var menu_items = $("#adminmenu > li", data);
                    var html = "";

                    menu_items.each(function() {
                        var that = $(this);
                        var name = $(".wp-menu-name", that);
                        var link = custom_adminbar.attr("data-admin-url") + name.parent().attr('href');
                        var submenu_items = $(".wp-submenu > li > a", that);

                        if(name.length) {
                            html += '<li class="wpHandoff-admin-bar-menu-item"><a href="' + link + '">' + name.html() + '</a><ul class="wpHandoff-admin-bar-submenu">';
                            submenu_items.each(function () {
                                var that = $(this);
                                var name = that.html();
                                var link = that.attr('href');

                                html += '<li class="wpHandoff-admin-bar-submenu-item"><a href="wp-admin/' + link + '" style="background: ' + adminbar.css('background-color') + '">' + name + '</a></li>';

                            });
                            html += '</ul></li>';
                        }
                    });

                    menu_list.html(html);
                }
            });
        }

        //ADMIN BAR END

        //SETTINGS
        $(".wpHandoff-settings").each(function(index, value) {
            var wrap = $(this);
            var tabs = $(".wpHandoff-tabs", wrap)
            var tabs_wrap = $(".wpHandoff-tabs-wrapper", tabs);
            var a = $(".title", tabs_wrap);
            var content = $(".wpHandoff-content", wrap);
            var th = 0;
            var active_tab = $("input[name='wpHandoffactive_tab']");

            a.each(function() {
                var that = $(this);
                that.on(window.interact.touchEnd, function(e) {
                    e.preventDefault();
                });
                th += that.outerHeight();
            });
            a.each(function() {
                var that = $(this);
                var scroll = 0;
                that.on(window.interact.touchStart, function(e) {
                    var that = $(this);
                    scroll = $(window).scrollTop();
                    that.addClass("focus");
                }).on(window.interact.touchEnd, function(e) {
                    var that = $(this);
                    if(that.hasClass("focus") && scroll === $(window).scrollTop()) {

                        a.removeClass('selected');
                        that.addClass('selected');

                        var target = that.attr('data-target');

                        content.addClass('hide');
                        $(target).removeClass('hide');

                        active_tab.val(that.attr("data-target"));

                        $(window).trigger('resize');  //invoke in case of hidden tab element(s)
                    }
                    that.removeClass("focus");
                }).on(window.interact.touchCancel, function(e) {
                    var that = $(this);
                    that.removeClass("focus");
                });

                if($.trim(active_tab.val()).length && that.attr("data-target") == active_tab.val()) {
                    that.trigger(window.interact.touchStart).trigger(window.interact.touchEnd);
                }
            });

            if(! $.trim(active_tab.val()).length) {
                a.eq(0).trigger(window.interact.touchStart).trigger(window.interact.touchEnd);
            }

            content.each(function() {
                var that = $(this);
                var hint = $(".wpHandoff-hint", that);

                $("a", hint).on(window.interact.touchEnd, function(e) {
                    e.preventDefault();

                    hint.animate({
                        height: 0,
                        padding: 0,
                    }, {
                        queue: false,
                        duration: 'fast',
                    });
                });
            });

            tabs.css({
                height: th
            });

            content.css({
                'min-height': th - 2
            });
        });
        //SETTINGS END
        var list = false;
        var items = false;
        var original = false;
        var copy = false;
        var moving = false;
        var moved = false;
        var startX = startY = 0;
        var curX = curY = 0;

        //event handlers prep for reset
        //reset after moving
        var items_bind = function() {
            list = $(".wpHandoff-menu-list");   //update selectors
            items = false;
            list.each(function () {
                var that = $(this);
                var children = that.children();

                if (items === false) {
                    items = children;
                } else {
                    $.merge(items, children);
                }
            });

            if(items !== false) {
                items.each(function () {//renaming
                    var that = $(this);
                    var name = $(".wpHandoff-menu-name", that);
                    var input = $("input[name^='wpHandoffmenu_rename'], input[name^='wpHandoffpages_rename']", name);
                    var html = input.next();
                    var default_text = input.val();
                    var menu_hidden = $("input[name^='wpHandoffmenu_hidden']", that);
                    var pages_show = $("input[name^='wpHandoffpages_show']", that);
                    var arrow = $(".wpHandoff-submenu-arrow", that);
                    var submenu = $(".wpHandoff-submenu-list", that);
                    var subitem = $(".wpHandoff-submenu-item", that);

                    //check if the menu belongs to the hidden column
                    //check if the page belongs to the show column
                    if (that.parents("div.wpHandoff-menu-hidden").length) {
                        if(menu_hidden.length) {
                            menu_hidden.prop("checked", true);
                        }
                        if(pages_show.length) {
                            pages_show.prop("checked", false);
                        }
                    } else {
                        if(menu_hidden.length) {
                            menu_hidden.prop("checked", false);
                        }
                        if(pages_show.length) {
                            pages_show.prop("checked", true);
                        }
                    }

                    //remove all events
                    input.off();
                    name.off();
                    arrow.off();

                    input.on("change", function () {
                        var that = $(this);
                        var text = that.val();

                        if($.trim(text).length) {
                            html.html(text);
                            default_text = text;
                        } else {
                            that.val(default_text); //revert value to default
                            html.html(default_text);
                        }
                    }).on("blur", function() {
                        $(this).addClass("hide");
                        html.removeClass("hide");
                    }).on(window.interact.touchEnd, function(e) {
                        e.preventDefault();
                    }).on("focus", function() {
                        $(this).select();
                    });

                    name.on(window.interact.touchEnd, function() {
                        if(moving === false) {    //just a click
                            input.removeClass("hide");
                            html.addClass("hide");

                            input.focus();
                        }
                    });

                    subitem.each(function() {
                        var that = $(this);
                        var subinput = $("input[name^='wpHandoffsubmenu_rename']", that);
                        var subhtml = subinput.next();
                        var subdefault_text = subinput.val();
                        var hide = $("input[name^='wpHandoffsubmenu_hidden']", that);

                        subinput.on("change", function () {
                            var that = $(this);
                            var text = that.val();

                            if($.trim(text).length) {
                                subhtml.html(text);
                                subdefault_text = text;
                            } else {
                                that.val(subdefault_text); //revert value to default
                                html.html(subdefault_text);
                            }
                        }).on("blur", function() {
                            $(this).addClass("hide");
                            subhtml.removeClass("hide");
                        }).on(window.interact.touchEnd, function(e) {
                            e.preventDefault();
                        }).on("focus", function() {
                            $(this).select();
                        });

                        that.on(window.interact.touchEnd, function() {
                            if(moving === false) {    //just a click

                                subinput.removeClass("hide");
                                subhtml.addClass("hide");

                                subinput.focus();
                            }
                        });

                        //automatically uncheck parent page in submenu
                        hide.on("change", function(e) {
                            var that = $(this);
                            var check = 0;
                            var head = false;

                            if(! that.parent().hasClass('wpHandoff-submenu-item-parent')) {
                                $("input[name^='wpHandoffsubmenu_hidden']", subitem).not(that).each(function() {
                                    var current = $(this).parent();
                                    if(current.hasClass('wpHandoff-submenu-item-parent')) {
                                        head = current;
                                    } else if($(this).prop("checked")) {
                                        check++;
                                    }
                                });

                                if(head !== false) {
                                    if (! check) {
                                        $("input[name^='wpHandoffsubmenu_hidden']", head).prop("checked", false);
                                    } else {
                                        $("input[name^='wpHandoffsubmenu_hidden']", head).prop("checked", true);
                                    }
                                }
                            } else {
                                $("input[name^='wpHandoffsubmenu_hidden']", subitem).not(that).each(function() {
                                    if($(this).prop("checked")) {
                                        check++;
                                    }
                                });

                                if (! check) {
                                    that.parent().siblings().find("input[name^='wpHandoffsubmenu_hidden']").prop("checked", true);
                                }
                            }
                        });
                    });

                    arrow.on(window.interact.touchEnd, function() {
                        if(moving === false) {
                            var that = $(this);
                            var arrows = that.children();
                            var more = arrows.eq(0);
                            var less = arrows.eq(1);

                            if (submenu.hasClass('hide')) {
                                more.addClass('hide');
                                less.removeClass('hide');
                                submenu.removeClass('hide');
                            } else {
                                more.removeClass('hide');
                                less.addClass('hide');
                                submenu.addClass('hide');
                            }
                        }
                    });
                });
            }
        };
        items_bind();

        $(this).on(window.interact.touchStart, function(e) {
            var touches = e.originalEvent;

            if(e.originalEvent.touches) {
                touches = e.originalEvent.touches[0];
            }

            startX = curX = touches.pageX;
            startY = curY = touches.pageY;

            if(items !== false) {
                items.each(function () {
                    var that = $(this);
                    var pos = that.offset();

                    if (
                        (curY >= pos.top && curY <= pos.top + that.outerHeight(true))
                        && (curX >= pos.left && curX <= pos.left + that.outerWidth(true))
                    ) {
                        original = that;
                        original.addClass('selected');
                        copy = original.clone(true);
                        original.removeClass('selected');
                    }
                });
            }
        }).on(window.interact.touchEnd, function(e) {
            if(moving === true) {
                var pos = original.offset();

                if (original !== false
                    && moved
                    && ! ((curY >= pos.top && curY <= pos.top + original.outerHeight(true))
                    && (curX >= pos.left && curX <= pos.left + original.outerWidth(true)))
                ) {
                    original.remove();  //remove original element
                }

                //rebind events
                items_bind();
            }

            //return default values
            copy = false;
            original = false;
            moved = false;
            moving = false;

            if(items !== false) {
                items.removeClass("selected");
            }

            //adminbar
            if(custom_adminbar.length) {
                var menupos = menu.offset();

                if (curY > menupos.top + menu.outerHeight() + arrow.outerHeight() && !menu.hasClass('hide')) {
                    arrow.trigger(window.interact.touchEnd);
                }
            }
        }).on(window.interact.touchMove, function(e) {
            if(copy !== false) {

                original.addClass('selected');

                var touches = e.originalEvent;

                moving = true;

                if(e.originalEvent.touches) {
                    touches = e.originalEvent.touches[0];
                }
                curX = touches.pageX;
                curY = touches.pageY;

                //in case list does not have items in it
                list.each(function() {
                    var that = $(this);
                    var pos = that.offset();
                    var children = that.children();

                    if(
                        (curY >= pos.top && curY <= pos.top + that.outerHeight(true))
                        && (curX >= pos.left && curX <= pos.left + that.outerWidth(true))
                        && ! children.length
                    ) {
                        that.append(copy);
                        moved = true;
                    }
                });

                if(items !== false) {
                    items.each(function () {
                        var that = $(this);
                        var pos = that.offset();

                        if (
                            (curY >= pos.top && curY <= pos.top + that.outerHeight(true))
                            && (curX >= pos.left && curX <= pos.left + that.outerWidth(true))
                        ) {
                            copy.remove();

                            if (! that.hasClass("selected") && ! that.next().hasClass("selected")) {
                                that.after(copy);
                                moved = true;
                            }
                        }
                    });
                }
            }
        });

        $("form", ".wpHandoff-settings").on("submit", function(e) {
            $(this).parent().addClass('hide');
            if(items !== false) {
                var index = 0;
                items.each(function () {
                    var that = $(this);
                    var order = $("input[name^='wpHandoffmenu_order']", that);

                    order.attr("name", "wpHandoffmenu_order[" + (index + 1) + "]");

                    index++;
                });
            }

            $("input[type='checkbox']", '.wpHandoff-settings').each(function() {
                var that = $(this);

                if(that.attr('name').indexOf('wpHandoffmenu_hidden') == -1 && that.attr('name').indexOf('wpHandoffpages_show') == -1) {   //exclude menu & pages
                    if (that.prop("checked")) {
                        that.prop("checked", false);
                    } else {
                        that.prop("checked", true);
                    }
                }
            });
        });


        var image = $(".wpHandoff-login-image-file");
        var logo_preview = $(".wpHandoff-login-image-preview img");
        var error = $(".wpHandoff-login-image-error");
        var progress = $(".wpHandoff-login-image-progress").children();
        var choose = progress.eq(0);
        var bar = progress.eq(1);
        var custom = $("#wpHandoff-custom-logo");
        var custom_logo = $(".wpHandoff-custom-logo");
        var siteurl = $("#siteurl").val();

        var update_preview = function(url) {
            logo_preview.attr('src', url);
            url = url.replace(siteurl, '');
            custom.val(url);
            custom_logo.val(url);
        };

        image.fileupload({
            type: 'POST',
            dataType: 'json',
            dropZone: null,
            pasteZone: null,
            singleFileUploads: true,
            formData: {
                action: 'login_logo_upload',
            },
            start: function () {
                bar.css({
                   width: 0,
                });
                choose.addClass("hide");
                bar.removeClass("hide");
                error.addClass("hide");
            },
            done: function (e, data) {
                $.each(data.result.files, function (index, file) {
                    var splits = file.name.split(".");
                    if(splits.pop() == "bmp") {
                        file.name = splits.join(".") + ".jpeg";
                    }
                    update_preview(file.url);
                });

                choose.removeClass("hide");
                bar.addClass("hide");

                custom.prop("checked", true);
                $(window).trigger('resize');  //invoke resize to position image
            },
            fail: function(e, data) {
                var msg = "";
                for(var key in data.messages) {
                    msg += key + " : " + data.messages[key];
                }

                error.html(msg);
                error.removeClass("hide");
                choose.removeClass("hide");
                bar.addClass("hide");

                if(console.log) {
                    console.log(msg);
                }
            },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);

                bar.css({
                   width: progress + "%",
                });
            }
        }).prop('disabled', ! $.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');

        $("#wpHandoff-custom-rss").next().on('change', function() {
            $(this).prev().val($(this).val());
        });

        $(window).on('resize', function() {
            var top = (logo_preview.parent().height() / 2) - (logo_preview.height() / 2);

            logo_preview.css({
                marginTop: top
            });

            //keep the admin on screen
            custom_adminbar.css({
               maxHeight: $(this).height() - arrow.height(),
            });

            if(! menu.hasClass('hide')) {
                custom_adminbar.css({
                    top: adminbar.outerHeight(true),
                });
                menu.children().eq(0).css({
                   maxWidth: menu.outerWidth(true) - menu.children().eq(1).outerWidth(true) - 10
                });
            }

        }).trigger('resize');
    });

    $(window).on('load mobileinit', function() {
        $(".wpHandoff-settings").each(function(index, value) {
            var wrap = $(this);
            var tabs = $(".wpHandoff-tabs", wrap)
            var tabs_wrap = $(".wpHandoff-tabs-wrapper", tabs);
            var a = $(".title", tabs_wrap);
            var content = $(".wpHandoff-content", wrap);
            var th = 0;

            a.each(function() {
                var that = $(this);
                th += that.outerHeight();
            });

            tabs.css({
                height: th
            });

            content.css({
                'min-height': th - 2
            });
        });

        $(this).trigger('resize');  //for logo resize event
    });
})(jQuery);