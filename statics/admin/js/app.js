+function ($) {
    'use strict';
    $(document).on("click.bs.nav.data-api", '[data-toggle="nav-min"]', function () {
        $("body").toggleClass("nav-min");
    });
    $(document).on("click.bs.data-api", '[data-toggle="off-canvas"]', function () {
        $("body").toggleClass("on-canvas");
    });
    $(document).on("click.bs.sidebar.data-api", '[data-toggle="sidebar"]', function (e) {
        e.preventDefault();
        $(this).parent("li").toggleClass("open");
    });
    $(document).on("click.bs.theme.data-api", '[data-toggle="theme"]', function () {
        var $this = $(this);
        if ($("body").hasClass("theme-zero")) {
            $("body").toggleClass("theme-zero");
        }
        if ($("body").hasClass("theme-one")) {
            $("body").toggleClass("theme-one");
        }
        if ($("body").hasClass("theme-two")) {
            $("body").toggleClass("theme-two");
        }
        if ($("body").hasClass("theme-three")) {
            $("body").toggleClass("theme-three");
        }
        if ($("body").hasClass("theme-four")) {
            $("body").toggleClass("theme-four");
        }
        $("body").toggleClass($this.data("theme"));
        $this.parent("li").parent("ul").find("li").each(function () {
            $(this).removeClass("active");
        });
        $this.parent("li").addClass("active");
        $.post(location.protocol + '/admin/theme/cookie', {theme:$this.data("theme")}, function(data) {});
    });
    $(window).on("load", function () {
        $('[data-toggle="nav-accordion"]').each(function (index, element) {
            var lists = $(element).find("ul").parent("li");
            var a = lists.children("a");
            var listsRest = $(element).children("ul").children("li").not(lists);
            var app = $("body");
            var stopClick = 0;
            a.on("click", function (e) {
                e.preventDefault();
                var self = $(this), parent = self.parent("li");
                a.not(self).next("ul").slideUp();
                self.next("ul").slideToggle();
                lists.not(parent).removeClass("open");
                parent.toggleClass("open");
            });
        });
        $('[data-toggle="scrollbar"]').each(function () {
            $(this).perfectScrollbar({
                suppressScrollX: !0
            });
        });
        $('[data-toggle="ueditor"]').each(function () {
            UE.getEditor($(this).attr("id"), {
                initialFrameHeight: '400'
            });
        });
        $('[data-toggle="colorpicker"]').each(function () {
            $(this).colorpicker();
        });
        $('[data-toggle="datetimepicker"]').each(function () {
            $(this).datetimepicker({
                autoclose: true,
                format: 'yyyy-mm-dd hh:ii',
                language: 'zh-CN',
                pickerPosition: "top-right"
            });
        });
        $('[data-toggle="upload-image"]').each(function () {
            $(this).uploadPreview({
                container: "#" + $(this).data("target"),
                height: "",
                with: "100%",
            });
        });
        $('[data-toggle="upload-image"]').each(function () {
            $(this).uploadPreview({
                container: "#" + $(this).data("target"),
                height: "",
                with: "100%",
            });
        });
        $('[data-toggle="sortable"]').each(function () {
            $(this).sortable({
                stop: function (event, ui) {
                    $(ui.item[0].parentElement).children("li").each(function (i, el) {
                        $(el).find("input").val(i);
                        $(el).find("span.badge").html(i);
                    });
                }
            });
        });
    });
}(jQuery);