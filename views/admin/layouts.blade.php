<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>@yield('title') - 后台 - {{ setting('site.company', 'iBenchu CMS')  }}内容管理系统</title>
    <meta name="author" content="iBenchu.net">
    <meta name="keywords" content="iBenchu">
    <meta name="description" content="iBenchu CMS内容管理系统">
    @css('admin::less.layout.bootstrap.bootstrap')
    @css('admin::less.layout.font-awesome.font-awesome')
    @yield('admin-css')
    @css('admin::css.default.admin')
    @output('css')
</head>
<body class="app {{ $admin_theme }}">
<header class="site-head clearfix" id="site-head">
    <div class="nav-head">
        <a href="{{ url('admin') }}" class="site-logo"><span>{{ setting('site.company', 'iBenchu CMS')  }}</span>&nbsp;内容管理系统</a>
        <span class="nav-trigger fa fa-outdent hidden-xs" data-toggle="nav-min"></span>
        <span class="nav-trigger fa fa-navicon visible-xs" data-toggle="off-canvas"></span>
    </div>
    <div class="head-wrap clearfix">
        <ul class="list-unstyled navbar-right">
            <li>
                <a href="{{ url() }}" target="_blank"> <i class="fa fa-external-link"></i> </a>
            </li>
            <li class="dropdown">
                <a href class="user-profile" data-toggle="dropdown">
                    <img src="{{ asset('statics/admin/images/avatar.jpg') }}" alt="N">
                </a>
                <div class="panel panel-default dropdown-menu">
                    <div class="panel-footer clearfix">
                        <a href="{{ url('admin/password') }}" class="btn btn-warning btn-sm left">重置密码</a>
                        <a href="{{ url('admin/logout') }}" class="btn btn-danger btn-sm right">退出登陆</a>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</header>
<div class="main-container clearfix">
    <aside class="nav-wrap" id="site-nav" data-toggle="scrollbar">
        <div class="form-search">
            <form action="{{ url('admin/search') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="search" class="form-control" name="keyword" placeholder="输入搜索关键词">
                <button type="submit" class="fa fa-search"></button>
            </form>
        </div>
        <nav class="site-nav clearfix" role="navigation" data-toggle="nav-accordion">
            @foreach(config('admin') as $top)
                <div class="nav-title panel-heading"><i>{{ $top['title'] }}</i></div>
                @if($top['sub'])
                    <ul class="list-unstyled nav-list">
                        @foreach($top['sub'] as $one)
                            @if(isset($one['sub']))
                                <li class="{{ $one['active'] }}">
                                    <a href="javascript:;"><i class="fa {{ $one['icon'] }} icon"></i><span class="text">{{ $one['title'] }}</span><i class="arrow fa fa-angle-right right"></i></a>
                                    <ul class="inner-drop list-unstyled">
                                        @foreach($one['sub'] as $two)
                                            <li class="{{ (app('request')->is($two['active']) ? 'active' : '') }}">
                                                <a href="{{ url($two['url']) }}">{{ $two['title'] }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li class="{{ $one['active'] }}">
                                    <a href="{{ url($one['url']) }}"><i class="fa {{ $one['icon'] }} icon"></i><span class="text">{{ $one['title'] }}</span></a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            @endforeach
        </nav>
        <div class="theme-settings clearfix">
            <div class="panel-heading"><i>主题切换</i></div>
            <ul class="list-unstyled clearfix">
                @if($admin_theme == 'theme-zero')
                    <li class="active">
                        <a href="javascript:;" data-toggle="theme" data-theme="theme-zero"><span class="side-top"></span><span class="header"></span><span class="side-rest"></span></a>
                    </li>
                @else
                    <li>
                        <a href="javascript:;" data-toggle="theme" data-theme="theme-zero"><span class="side-top"></span><span class="header"></span><span class="side-rest"></span></a>
                    </li>
                @endif
                @if($admin_theme == 'theme-one')
                    <li class="active">
                        <a href="javascript:;" data-toggle="theme" data-theme="theme-one"><span class="side-top"></span><span class="header"></span><span class="side-rest"></span></a>
                    </li>
                @else
                    <li><a href="javascript:;" data-toggle="theme" data-theme="theme-one"><span class="side-top"></span><span class="header"></span><span class="side-rest"></span></a>
                    </li>
                @endif
                @if($admin_theme == 'theme-two')
                    <li class="active">
                        <a href="javascript:;" data-toggle="theme" data-theme="theme-two"><span class="side-top"></span><span class="header"></span><span class="side-rest"></span></a>
                    </li>
                @else
                    <li><a href="javascript:;" data-toggle="theme" data-theme="theme-two"><span class="side-top"></span><span class="header"></span><span class="side-rest"></span></a>
                    </li>
                @endif
                @if($admin_theme == 'theme-three')
                    <li class="active">
                        <a href="javascript:;" data-toggle="theme" data-theme="theme-three"><span class="side-top"></span><span class="header"></span><span class="side-rest"></span></a>
                    </li>
                @else
                    <li>
                        <a href="javascript:;" data-toggle="theme" data-theme="theme-three"><span class="side-top"></span><span class="header"></span><span class="side-rest"></span></a>
                    </li>
                @endif
                @if($admin_theme == 'theme-four')
                    <li class="active">
                        <a href="javascript:;" data-toggle="theme" data-theme="theme-four"><span class="side-top"></span><span class="header"></span><span class="side-rest"></span></a>
                    </li>
                @else
                    <li>
                        <a href="javascript:;" data-toggle="theme" data-theme="theme-four"><span class="side-top"></span><span class="header"></span><span class="side-rest"></span></a>
                    </li>
                @endif
            </ul>
        </div>
    </aside>
    <div class="content-container" id="content">@yield('content')</div>
    <footer id="site-foot" class="site-foot clearfix">
        <p class="left">&copy; Copyright 2015 <strong>iBenchu.org</strong>, All rights reserved.</p>
        <p class="right">{{ config('app.version') }}</p>
    </footer>
</div>
<script src="{{ asset('statics/admin/js/jquery-2.1.3.min.js') }}"></script>
<script src="{{ asset('statics/admin/js/perfect-scrollbar.jquery.min.js') }}"></script>
<script src="{{ asset('statics/admin/js/bootstrap.min.js') }}"></script>
@yield('admin-js')
<script src="{{ asset('statics/admin/js/app.js') }}"></script>
</body>
</html>