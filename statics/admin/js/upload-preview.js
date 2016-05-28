(function ($) {
    $.fn.uploadPreview = function (opts) {
        if (this.length < 1) {
            return this;
        }
        var $this = $(this);
        var msie = $.fn.uploadPreview.msie;
        opts = $.extend({
            container: '#preView',
            css: {},
            text: '',
            width: '100%',
            height: '100%',
            imgType: ["gif", "jpeg", "jpg", "bmp", "png", "webp"]
        }, opts || {});
        if (msie) {
            if (msie == 6) {
                $.fn.uploadPreview.loopInput($this, opts, function (input) {
                    $(input).change({opts: opts}, function () {
                        if (!$.fn.uploadPreview.checkFileType(input.value, opts)) {
                            input.value = '';
                            return false;
                        }
                        $.fn.uploadPreview.getImage(opts).attr('src', 'file:///' + input.value);
                    });

                });
                return $this;
            }
            $.fn.uploadPreview.loopInput($this, opts, function (input) {
                $(input).change({opts: opts}, function () {
                    if (!$.fn.uploadPreview.checkFileType(input.value, opts)) {
                        input.value = '';
                        return false;
                    }
                    var innerDiv = $('<div/>');
                    var container = $(opts.container);
                    container.empty().prepend(innerDiv);
                    input.select();
                    input.blur();
                    var imgSrc = document.selection.createRange().text;
                    try {
                        innerDiv.css('filter', 'progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)');
                        innerDiv[0].filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = imgSrc;
                        innerDiv.css($.fn.uploadPreview.getImageWH($(opts.container), opts.width, opts.height, imgSrc));
                    } catch (e) {
                        alert("您上传的图片格式不正确，请重新选择!");
                        return false;
                    }
                    $('.upload-pre-view-image', container).hide();
                    document.selection.empty();
                });
            });
            return $this;
        }
        $.fn.uploadPreview.loopInput($this, opts, function (input) {
            $(input).change({opts: opts}, function () {
                if (!$.fn.uploadPreview.checkFileType(input.value, opts)) {
                    input.value = '';
                    return false;
                }
                $.fn.uploadPreview.getImage(opts).attr('src', URL.createObjectURL(input.files[0]));
            });
        });
        return $this;
    };
    $.fn.uploadPreview.msie = (function () {
        var undef,
                v = 3,
                div = document.createElement('div'),
                all = div.getElementsByTagName('i');
        while (div.innerHTML = '<!--[if gt IE ' + (++v) + ']><i></i><![endif]-->', all[0])
            ;
        return v > 4 ? v : undef;
    })();
    $.fn.uploadPreview.checkFileType = function (value, opts) {
        if (value) {
            if (!RegExp('\.(' + opts.imgType.join('|') + ')$', 'i').test(value)) {
                alert('图片类型必须是' + opts.imgType.join(', ') + '中的一种');
                return false;
            }
            return true;
        }
        return false;
    };
    $.fn.uploadPreview.getImage = function (opts) {
        var img = $('.upload-pre-view-image', opts.container); //获取图片
        if (img.length > 0) {
            return img;
        }
        var wh = {};
        var container = $(opts.container);
        if (container.css("display") === "none") {
            container.show();
        }
        if (opts.width) {
            if (isNaN(opts.width)) {
                opts.width = $.trim(opts.width);
                if (opts.width.lastIndexOf('%') != -1) {
                    wh.width = ((container.width() * (opts.width.split('%')[0] - 0)) / 100) + 'px';
                } else {
                    wh.width = opts.width;
                }
            } else {
                wh.width = opts.width + 'px';
            }
        }
        if (opts.height) {
            if (isNaN(opts.height - 0)) {
                opts.height = $.trim(opts.height);
                if (opts.height.lastIndexOf('%') != -1) {
                    wh.height = ((container.height() * (opts.height.split('%')[0] - 0)) / 100) + 'px';
                } else {
                    wh.height = opts.height;
                }
            } else {
                wh.height = opts.height + 'px';
            }
        }
        img = $('<img />');
        img.addClass('upload-pre-view-image');
        img.css(wh);
        $(opts.container).empty().prepend(img);
        img = $('.upload-pre-view-image', opts.container); //获取图片
        return img;
    };
    $.fn.uploadPreview.getImageWH = function (container, width, height, imgSrc) {
        var wh = {};
        if (width) {
            if (isNaN(width - 0)) {
                width = $.trim(width);
                if (width.lastIndexOf('%') != -1) {
                    wh.width = ((container.width() * (width.split('%')[0] - 0)) / 100) + 'px';
                } else {
                    wh.width = width;
                }
            } else {
                wh.width = width + 'px';
            }
            if (height) {
                if (isNaN(height - 0)) {
                    height = $.trim(height);
                    if (height.lastIndexOf('%') != -1) {
                        wh.height = ((container.height() * (height.split('%')[0] - 0)) / 100) + 'px';
                    } else {
                        wh.height = height;
                    }
                } else {
                    wh.height = height + 'px';
                }
                return wh;
            }
            var img = new Image();
            img.src = imgSrc;
            var w = wh.width.toLowerCase().split('px')[0] - 0;
            if (w != img.width) {
                wh.height = ((w / img.width) * img.height) + 'px';
            }
            return wh;
        }
        if (height) {
            if (isNaN(height - 0)) {
                wh.height = height;
            } else {
                wh.height = height + 'px';
            }
            var img = new Image();
            img.src = imgSrc;
            var h = wh.height.toLowerCase().split('px')[0] - 0;
            if (img.height != h) {
                wh.width = ((h / img.height) * img.width) + 'px';
            }
            return wh;
        }
        var img = new Image();
        img.src = imgSrc;
        if (img.width)
            wh.width = img.width;
        if (img.height)
            wh.height = img.height;
        return wh;
    };
    $.fn.uploadPreview.loopInput = function (obj, opts, callback) {
        var file;
        for (var i = 0; i < obj.length; i++) {
            file = obj[i];
            if (file.tagName.toLowerCase() == 'input' && $(file).attr('type').toLowerCase() == 'file') {
                callback(file);
            }

        }
    };
})(jQuery);