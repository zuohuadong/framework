<h2>安装</h2>
<p>如果您在安装过程中遇到问题，可以进入<a href="https://www.notadd.com/" target="_blank">www.notadd.com</a>获取帮助。</p>
<p>需要解决Webp格式图片处理问题，可以进入<a href="https://www.notadd.com/alpha2/" target="_blank">https://www.notadd.com/alpha2/</a>获取帮助。</p>
<p>需要开启的PHP函数列表：exec,system,chroot,scandir,chgrp,chown,shell_exec,proc_open。</p>
<?php if($gd_trouble) { ?>
    <p>当前服务器不支持GD引擎处理Webp格式图片。</p>
<?php } else { ?>
    <p>当前服务器可以使用GD引擎处理Webp格式图片。</p>
<?php } ?>
<?php if($imagemagick_trouble) { ?>
    <p>当前服务器不支持Imagemagick引擎处理Webp格式图片。</p>
<?php } else { ?>
    <p>当前服务器可以使用Imagemagick引擎处理Webp格式图片。</p>
<?php } ?>
<form autocomplete="off" method="post">
    <input type="hidden" name="_token" value="<?php echo app('session')->getToken() ?>">
    <div class="form-group form-group-sm">
        <div class="form-field">
            <label>网站标题</label>
            <input name="title">
        </div>
    </div>
    <div class="form-group form-group-sm">
        <div class="form-field">
            <label>数据库服务器</label>
            <input name="host" value="localhost">
        </div>
        <div class="form-field">
            <label>数据库引擎</label>
            <select name="driver">
                <?php if($has_mysql) { ?>
                <option value="mysql">MySQL</option>
                <?php } ?>
                <?php if($has_pgsql) { ?>
                <option value="pgsql">PostgreSQL</option>
                <?php } ?>
                <?php if($has_sqlite) { ?>
                <option value="sqlite">SQLite</option>
                <?php } ?>
            </select>
        </div>
        <div class="form-field">
            <label>数据库名</label>
            <input name="database" value="notadd">
        </div>
        <div class="form-field">
            <label>数据库用户名</label>
            <input name="username" value="root">
        </div>
        <div class="form-field">
            <label>数据库密码</label>
            <input type="password" name="password">
        </div>
        <div class="form-field">
            <label>数据库表前缀(例：not_)</label>
            <input type="text" name="prefix" value="not_">
        </div>
    </div>
    <div class="form-group form-group-sm">
        <div class="form-field">
            <label>管理员用户名</label>
            <input name="admin_username">
        </div>
        <div class="form-field">
            <label>管理员Email</label>
            <input name="admin_email">
        </div>
        <div class="form-field">
            <label>管理员密码</label>
            <input type="password" name="admin_password">
        </div>
        <div class="form-field">
            <label>确认密码</label>
            <input type="password" name="admin_password_confirmation">
        </div>
    </div>
    <div id="error" style="display:none"></div>
    <div>
        <button type="submit">开始安装</button>
    </div>
</form>
<script src="//cdn.bootcss.com/jquery/2.1.4/jquery.min.js"></script>
<script>
    $(function () {
        $('form :input:first').select();
        $('form').on('submit', function (e) {
            e.preventDefault();
            $('#error').hide().text('');
            var $button = $(this).find('button').text('正在安装...').prop('disabled', true);
            $.post("", $(this).serialize()).done(function (data) {
                var infos = data.split("\n");
                $button.prop('disabled', false).text('即将自动跳转……');
                $("#error").append("<p>安装成功！反馈信息：</p>");
                $.each(infos, function(key, value) {
                    $("#error").append("<p>" + value + "</p>");
                });
                $("#error").addClass("info").show();
                $("body").animate({
                    scrollTop: $("body").outerHeight()
                }, 1000);
                setTimeout(function() {
                    window.location.reload();
                }, 6000);
            }).fail(function (data) {
                $button.prop('disabled', false).text('开始安装');
                $("#error").append("<p>安装操作有误：</p>");
                $.each(data.responseJSON, function(key, value) {
                    $("#error").append("<p>" + value + "</p>");
                });
                $("#error").show();
                $("body").animate({
                    scrollTop: $("body").outerHeight()
                }, 1000);
            });
            return false;
        });
    });
</script>
