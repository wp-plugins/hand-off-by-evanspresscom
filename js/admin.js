(function($) {
    var admin_bar = function() {
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

        var curY = 0;

        $(document).on(window.interact.touchStart, function(e) {
            var touches = e.originalEvent;

            if(e.originalEvent.touches) {
                touches = e.originalEvent.touches[0];
            }

            curY = touches.pageY;
        }).on(window.interact.touchMove, function(e) {
            var touches = e.originalEvent;

            if(e.originalEvent.touches) {
                touches = e.originalEvent.touches[0];
            }
            curY = touches.pageY;
        }).on(window.interact.touchEnd, function(e) {
            if(custom_adminbar.length) {
                var menupos = menu.offset();

                if (curY > menupos.top + menu.outerHeight() + arrow.outerHeight() && !menu.hasClass('hide')) {
                    arrow.trigger(window.interact.touchEnd);
                }
            }
        });
    };

    var settings = function() {
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
                that.on(window.interact.touchStart, function(e) {
                    e.stopPropagation();
                }).on(window.interact.touchEnd, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
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
    };

    var logo = function() {
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
    };

    var menu = function() {
        var container = $(document);
        var wrap = $("#wpHandoffmenu");
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
            list = $(".wpHandoff-menu-list", wrap);   //update selectors
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
                    var dragarrow = $(".wpHandoff-menu-drag", that);
                    var input = $("input[name^='wpHandoffmenu_rename']", name);
                    var html = input.next();
                    var default_text = input.val();
                    var hidden = $("input[name^='wpHandoffmenu_hidden']", that);
                    var subarrow = $(".wpHandoff-submenu-arrow", that);
                    var submenu = $(".wpHandoff-submenu-list", that);
                    var subitem = $(".wpHandoff-submenu-item", that);

                    //check if the menu belongs to the hidden column
                    if (that.parents("div.wpHandoff-menu-hidden").length) {
                        hidden.prop("checked", true);
                    } else {
                        hidden.prop("checked", false);
                    }

                    //remove all events
                    input.off();
                    html.off();
                    subarrow.off();

                    input.on("change", function () {
                        var that = $(this);
                        var text = that.val();

                        //sync input value to html text
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
                        dragarrow.removeClass("hide");
                    }).on(window.interact.touchEnd, function(e) {
                        e.preventDefault();
                    }).on("focus", function() {
                        $(this).select();
                    }).on('keyup', function(e) {
                        e.stopPropagation();
                        var that = $(this);
                        if(e.which == 27) { //escape
                            that.val(default_text);
                            that.trigger('change');
                            that.trigger('blur');
                        }
                    });

                    //rename menu
                    html.on(window.interact.touchEnd, function() {
                        if(moving === false) {
                            input.removeClass("hide");
                            html.addClass("hide");
                            dragarrow.addClass("hide");
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
                            if(moving === false) {
                                subinput.removeClass("hide");
                                subhtml.addClass("hide");
                                dragarrow.removeClass("hide");

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

                    // hide / show submenu
                    subarrow.on(window.interact.touchEnd, function() {
                        if(moving === false) {
                            var that = $(this);
                            var arrows = that.children();
                            var more = arrows.eq(0);
                            var less = arrows.eq(1);

                            if (submenu.hasClass('hide')) { //show submenu
                                more.addClass('hide');
                                less.removeClass('hide');
                                submenu.removeClass('hide');
                                $(".wpHandoff-submenu-arrow", wrap).not(subarrow).each(function() {
                                    var that = $(this);
                                    if(that.children().eq(0).hasClass('hide')) {
                                        that.trigger(window.interact.touchEnd);
                                    }
                                });
                            } else {    //hide submenu
                                more.removeClass('hide');
                                less.addClass('hide');
                                submenu.addClass('hide');
                            }
                        }
                    });

                    $(window).trigger('resize');
                });
            }
        };
        items_bind();

        container.on(window.interact.touchStart, function(e) {
            var touches = e.originalEvent;

            if(e.originalEvent.touches) {
                touches = e.originalEvent.touches[0];
            }

            startX = curX = touches.pageX;
            startY = curY = touches.pageY;

            if(items !== false) {
                items.each(function () {
                    var that = $(this);
                    var drag = $(".wpHandoff-menu-drag", that);
                    var pos = drag.offset();

                    if (
                        (curY >= pos.top && curY <= pos.top + drag.outerHeight(true))
                        && (curX >= pos.left && curX <= pos.left + drag.outerWidth(true))
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
            } else {
                if(original !== false && ! moved) {
                    original.addClass('active');
                    if(items !== false) {
                        items.not(original).removeClass('active');
                    }
                }
            }
            //return default values
            copy = false;
            original = false;
            moved = false;
            moving = false;

            if(items !== false) {
                items.removeClass("selected");
            }
        }).on(window.interact.touchMove, function(e) {
            if(copy !== false) {
                original.addClass('selected');
                if(!$(".wpHandoff-submenu-list", original).hasClass('hide')) {
                    $(".wpHandoff-submenu-arrow", original).trigger(window.interact.touchEnd);
                }

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
        }).on('keyup', function(e) {
            if(items !== false && ! moving) {
                var index = 0;
                var active = items.filter(function(i) {
                    if($(this).hasClass('active')) {
                        index = i;
                        return true;
                    }
                    return false;
                });
                if(active.length) {
                    items.removeClass('active');
                    copy = active.clone(true);
                    copy.addClass('active');
                    switch (e.which) {
                        case 37:    //left
                        case 39:    //right
                            var i = active.index();
                            if (active.parents(".wpHandoff-menu-hidden").length) {  //left
                                var show = $(".wpHandoff-menu-show .wpHandoff-menu-list", wrap);
                                if(show.children().length > i) {
                                    show.children().eq(i).before(copy);
                                } else {
                                    show.append(copy);
                                }
                            } else if (active.parents(".wpHandoff-menu-show").length) { //right
                                var hidden = $(".wpHandoff-menu-hidden .wpHandoff-menu-list", wrap);
                                if(hidden.children().length > i) {
                                    hidden.children().eq(i).before(copy);
                                } else {
                                    hidden.append(copy);
                                }
                            }
                            active.remove();
                            items_bind();
                            break;
                        case 38:    //up
                            if(! index) {
                                items.eq(items.length - 1).after(copy);
                            } else {
                                var prev = items.eq(index - 1);
                                if(prev.index() == prev.parent().children().length - 1) {
                                    items.eq(index - 1).after(copy);
                                } else {
                                    items.eq(index - 1).before(copy);
                                }
                            }
                            active.remove();
                            items_bind();
                            break;
                        case 40:    //down
                            if(index >= items.length - 1) {
                                items.eq(0).before(copy);
                            } else {
                                var next = items.eq(index + 1);
                                if(! next.index()) {
                                    items.eq(index + 1).before(copy);
                                } else {
                                    items.eq(index + 1).after(copy);
                                }
                            }
                            active.remove();
                            items_bind();
                            break;
                    }
                    copy = false;
                }
            }
        });
    };

    var pages = function() {
        var container = $(document);
        var wrap = $("#wpHandoffpages");
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
            list = $(".wpHandoff-menu-list", wrap);   //update selectors
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
                    var show = $("input[name^='wpHandoffpages_show']", that);

                    //check if the menu belongs to the hidden column
                    if (that.parents("div.wpHandoff-menu-hidden").length) {
                        show.prop("checked", false);
                    } else {
                        show.prop("checked", true);
                    }

                    $(window).trigger('resize');
                });
            }
        };
        items_bind();

        container.on(window.interact.touchStart, function(e) {
            var touches = e.originalEvent;

            if(e.originalEvent.touches) {
                touches = e.originalEvent.touches[0];
            }

            startX = curX = touches.pageX;
            startY = curY = touches.pageY;

            if(items !== false) {
                items.each(function () {
                    var that = $(this);
                    var drag = $(".wpHandoff-menu-drag", that);
                    var pos = drag.offset();

                    if (
                        (curY >= pos.top && curY <= pos.top + drag.outerHeight(true))
                        && (curX >= pos.left && curX <= pos.left + drag.outerWidth(true))
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
            } else {
                if(original !== false && ! moved) {
                    original.addClass('active');
                    if(items !== false) {
                        items.not(original).removeClass('active');
                    }
                }
            }
            //return default values
            copy = false;
            original = false;
            moved = false;
            moving = false;

            if(items !== false) {
                items.removeClass("selected");
            }
        }).on(window.interact.touchMove, function(e) {
            if(copy !== false) {
                original.addClass('selected');
                if(!$(".wpHandoff-submenu-list", original).hasClass('hide')) {
                    $(".wpHandoff-submenu-arrow", original).trigger(window.interact.touchEnd);
                }

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
        }).on('keyup', function(e) {
            if(items !== false && ! moving) {
                var index = 0;
                var active = items.filter(function(i) {
                    if($(this).hasClass('active')) {
                        index = i;
                        return true;
                    }
                    return false;
                });
                if(active.length) {
                    items.removeClass('active');
                    copy = active.clone(true);
                    copy.addClass('active');
                    switch (e.which) {
                        case 37:    //left
                        case 39:    //right
                            var i = active.index();
                            if (active.parents(".wpHandoff-menu-hidden").length) {  //left
                                var show = $(".wpHandoff-menu-show .wpHandoff-menu-list", wrap);
                                if(show.children().length > i) {
                                    show.children().eq(i).before(copy);
                                } else {
                                    show.append(copy);
                                }
                            } else if (active.parents(".wpHandoff-menu-show").length) { //right
                                var hidden = $(".wpHandoff-menu-hidden .wpHandoff-menu-list", wrap);
                                if(hidden.children().length > i) {
                                    hidden.children().eq(i).before(copy);
                                } else {
                                    hidden.append(copy);
                                }
                            }
                            active.remove();
                            items_bind();
                            break;
                        case 38:    //up
                            if(! index) {
                                items.eq(items.length - 1).after(copy);
                            } else {
                                var prev = items.eq(index - 1);
                                if(prev.index() == prev.parent().children().length - 1) {
                                    items.eq(index - 1).after(copy);
                                } else {
                                    items.eq(index - 1).before(copy);
                                }
                            }
                            active.remove();
                            items_bind();
                            break;
                        case 40:    //down
                            if(index >= items.length - 1) {
                                items.eq(0).before(copy);
                            } else {
                                var next = items.eq(index + 1);
                                if(! next.index()) {
                                    items.eq(index + 1).before(copy);
                                } else {
                                    items.eq(index + 1).after(copy);
                                }
                            }
                            active.remove();
                            items_bind();
                            break;
                    }
                    copy = false;
                }
            }
        });
    };

    $(document).ready(function() {
        admin_bar();
        settings();
        logo();
        menu();
        pages();

        $("form", ".wpHandoff-settings").on("submit", function(e) {
            $(this).parent().addClass('hide');
            list = $(".wpHandoff-menu-list", "#wpHandoffmenu");   //update selectors
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

        $("#wpHandoff-custom-rss").next().on('change', function() {
            $(this).prev().val($(this).val());
        });

        $(window).trigger('resize');
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
    }).on('resize', function() {
        var logo_preview = $(".wpHandoff-login-image-preview img");
        var adminbar = $("#wpadminbar");
        var custom_adminbar = $("#wpHandoff-admin-bar");
        var menu = $(".wpHandoff-admin-bar-menu", custom_adminbar);
        var arrow = $(".wpHandoff-admin-bar-show", custom_adminbar);
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
        $(".wpHandoff-menu-list").each(function() {
            var that = $(this);
            var max = $(window).height() * 0.8;
            var h = 0;

            that.children().each(function() {
                h += $(this).outerHeight(true);
            });

            that.css({
               maxHeight: max,
            });

            if(h > max) {
                that.children().css({
                    display: 'inline-block',
                });
            } else {
                that.children().css({
                    display: 'block',
                });
            }
        });
    });
})(jQuery);