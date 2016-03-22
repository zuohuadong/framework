@extends('admin::auth.layouts')
@section('title'){{ setting('site.company', 'iBenchu CMS')  }}后台管理系统@endsection
@section('content')
<div class="page page-auth clearfix">
    <div class="auth-container">
        <div class="auth-container-wrap">
            <h1 class="site-logo h2 mb15"><a href="{{ url('/') }}"><span>{{ setting('site.company', 'Notadd CMS')  }}</span>&nbsp;内容管理系统</a></h1>
            <h3 class="text-normal h4 text-center">欢迎登陆后台管理系统</h3>
            <div class="form-container">
                <form action="{{ url('admin/login') }}" class="form-horizontal" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group form-group-lg">
                        <input type="email" class="form-control" name="email" required>
                        <label alt="请输入邮件账户" placeholder="邮件账户"></label>
                    </div>
                    <div class="form-group form-group-lg">
                        <input type="password" class="form-control" name="password" required>
                        <label alt="请输入密码" placeholder="密码"></label>
                    </div>
                    <div class="clearfix"><a href="{{ url('admin/password/email') }}" class="right small mb20">忘记密码了吗？</a></div>
                    <div class="clearfix text-center">
                        <button type="submit" class="btn btn-lg btn-w120 btn-primary text-uppercase">登录</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection