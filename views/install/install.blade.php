@extends('install::layout')
@section('content')
    <div class="main-viewport test-success">
        <header>
            <div class="container">
                <img class="success-logo" src="{{ asset('assets/install/images/test-success.svg') }}">
                <h1 class="error-title">安装信息</h1>
            </div>
        </header>
        <div class="container">
            <div class="error-panel">
                <form action="{{ url('/') }}" class="form-horizontal" id="install-form" method="post">
                    <fieldset>
                        <div class="content">
                            <div class="form-group">
                                <label for="toggle1" class="col-xs-6 control-label webp-label">Webp图片模式</label>
                                <div class="togglebutton" id="toggle1">
                                    <label><input type="checkbox" checked></label>
                                </div>
                            </div>
                            <div class="form-group label-floating">
                                <label class="control-label">你的网站名称</label>
                                <input class="form-control" type="text" name="website">
                            </div>
                            <ul class="sql-group">
                                <input type="hidden" name="type" value="mysql">
                                <li id="mysql" class="active"><a href="javascript:void(0)" class="btn-sql">MySQL</a></li>
                                <li id="pgsql"><a href="javascript:void(0)" class="btn-sql">PostgreSQL</a></li>
                                <li id="sqlite"><a href="javascript:void(0)" class="btn-sql">SQLite3</a></li>
                            </ul>
                            <div class="data" id="data">
                                <div class="form-group label-floating">
                                    <label class="control-label" for="dataAddress">数据库地址</label>
                                    <input class="form-control" id="dataAddress" type="text" name="dataAddress">
                                </div>
                                <div class="form-group label-floating">
                                    <label class="control-label" for="dataUserName">数据库用户名</label>
                                    <input class="form-control" id="dataUserName" type="text" name="dataUserName">
                                </div>
                                <div class="form-group label-floating">
                                    <label class="control-label" for="dataPassword">数据库密码</label>
                                    <input class="form-control" id="dataPassword" type="password" name="dataPassword">
                                </div>
                                <div class="form-group label-floating">
                                    <label class="control-label" for="dataName">数据库名</label>
                                    <input class="form-control" id="dataName" type="text" name="dataName">
                                </div>
                            </div>
                            <div class="user">
                                <div class="form-group label-floating">
                                    <label class="control-label" for="userAccount">管理员账号</label>
                                    <input class="form-control" id="userAccount" type="text" name="userAccount">
                                </div>
                                <div class="form-group label-floating">
                                    <label class="control-label" for="userEmail">管理员邮箱</label>
                                    <input class="form-control" id="userEmail" type="text" name="userEmail">
                                </div>
                                <div class="form-group label-floating">
                                    <label class="control-label" for="userPassword">管理员密码</label>
                                    <input class="form-control" id="userPassword" type="password" name="userPassword">
                                </div>
                                <div class="form-group label-floating">
                                    <label class="control-label" for="userConfPwd">再次输入密码</label>
                                    <input class="form-control" id="userConfPwd" type="password" name="userConfPwd">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input class="submit" type="submit" value="安装">
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('sheets')
    <link href="{{ asset('assets/install/sheets/bootstrap-material-design.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/install/sheets/ripples.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/install/sheets/install.css') }}" rel="stylesheet">
@endsection
@section('script')
    <script src="{{ asset('assets/install/scripts/material.min.js') }}"></script>
    <script src="{{ asset('assets/install/scripts/ripples.min.js') }}"></script>
    <script src="{{ asset('assets/install/scripts/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/install/scripts/messages_zh.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("#sqlite").click(function () {
                $(this).parent("ul").find("input[name=type]").val("sqlite");
                $(this).parent("ul").find("li").removeClass("active");
                $(this).addClass("active");
                $("#data").fadeOut();
            });
            $("#mysql").click(function () {
                $(this).parent("ul").find("input[name=type]").val("mysql");
                $(this).parent("ul").find("li").removeClass("active");
                $(this).addClass("active");
                $("#data").fadeIn("slow");
            });
            $("#pgsql").click(function () {
                $(this).parent("ul").find("input[name=type]").val("pgsql");
                $(this).parent("ul").find("li").removeClass("active");
                $(this).addClass("active");
                $("#data").fadeIn("slow");
            });
        });
        $.material.init();
        $.validator.setDefaults({});
        $().ready(function () {
            jQuery.validator.addMethod("noSpace", function (value, element) {
                return value.indexOf(" ") < 0 && value != "";
            }, $.validator.format("请勿输入空白字符"));
        });
        $().ready(function () {
            $('#install-form').validate(
                {
                    rules: {
                        website: {
                            required: true,
                            noSpace: true
                        },
                        dataAddress: {
                            required: true,
                            noSpace: true
                        },
                        dataUserName: {
                            required: true,
                            noSpace: true
                        },
                        dataPassword: {
                            required: true,
                            noSpace: true,
                            minlength: 6
                        },
                        dataName: {
                            required: true,
                            noSpace: true
                        },
                        userAccount: {
                            required: true,
                            noSpace: true
                        },
                        userEmail: {
                            required: true,
                            noSpace: true,
                            email: true
                        },
                        userPassword: {
                            required: true,
                            noSpace: true,
                            minlength: 6
                        },
                        userConfPwd: {
                            required: true,
                            noSpace: true,
                            minlength: 6,
                            equalTo: "#userPassword"
                        },
                        success: "valid",
                        errorClass: "error",
                        errorPlacement: function (error, element) {
                            error.appendTo(element.parent());
                        }
                    },
                    messages: {
                        website: {
                            required: "请输入网站名称",
                            noSpace: "请勿输入空白字符"
                        },
                        dataAddress: {
                            required: "请输入数据库地址",
                            noSpace: "请勿输入空白字符"
                        },
                        dataUserName: {
                            required: "请输入数据库地址",
                            noSpace: "请勿输入空白字符"
                        },
                        dataPassword: {
                            required: "请输入数据库密码",
                            minlength: "数据库密码至少为6位",
                            noSpace: "请勿输入空白字符"
                        },
                        dataName: {
                            required: "请输入数据名",
                            noSpace: "请勿输入空白字符"
                        },
                        userAccount: {
                            required: "请输入管理员账号",
                            noSpace: "请勿输入空白字符"
                        },
                        userEmail: {
                            required: "请输入管理员邮箱",
                            emaill: "请输入正确的管理员邮箱",
                            noSpace: "请勿输入空白字符"
                        },
                        userPassword: {
                            noSpace: "请勿输入空白字符",
                            required: "请输入管理员密码",
                            minlength: "管理员密码至少为6位"
                        },
                        userConfPwd: {
                            noSpace: "请勿输入空白字符",
                            required: "请再次输入管理员密码",
                            minlength: "管理员密码至少为6位",
                            equalTo: "与上面输入的密码不一致"
                        }
                    }
                });
        });
    </script>
@endsection