@extends('admin::layouts')
@section('title')社交组件 - 社交账号登陆@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li class="active"><a href="{{ url('admin/third')}}">社交账号登录</a></li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ url('admin/third') }}" autocomplete="off" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="panel panel-lined clearfix mb20">
                            <div class="panel-heading mb20"><i>QQ登陆设置</i></div>
                            <div class="form-horizontal col-md-12">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">QQ登陆功能开启</label>
                                    <div class="col-md-6">
                                        <div class="btn-group" data-toggle="buttons">
                                            @if($third_qq_enable)
                                                <label class="btn btn-primary btn-sm active"><input name="third_qq_enable" type="radio" value="1" checked>开启</label>
                                                <label class="btn btn-primary btn-sm"><input name="third_qq_enable" type="radio" value="0">关闭</label>
                                            @else
                                                <label class="btn btn-primary btn-sm"><input name="third_qq_enable" type="radio" value="1">开启</label>
                                                <label class="btn btn-primary btn-sm active"><input name="third_qq_enable" type="radio" value="0" checked>关闭</label>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">App ID</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="third_qq_key" value="{{ $third_qq_key }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">APP KEY</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="third_qq_secret" value="{{ $third_qq_secret }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">回调地址</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="third_qq_callback" value="{{ $third_qq_callback }}">
                                    </div>
                                </div>
                            </div>
                            <div class="panel-heading mb20"><i>微博登陆设置</i></div>
                            <div class="form-horizontal col-md-12">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">微博登陆功能开启</label>
                                    <div class="col-md-6">
                                        <div class="btn-group" data-toggle="buttons">
                                            @if($third_weibo_enable)
                                                <label class="btn btn-primary btn-sm active"><input name="third_weibo_enable" type="radio" value="1" checked>开启</label>
                                                <label class="btn btn-primary btn-sm"><input name="third_weibo_enable" type="radio" value="0">关闭</label>
                                            @else
                                                <label class="btn btn-primary btn-sm"><input name="third_weibo_enable" type="radio" value="1">开启</label>
                                                <label class="btn btn-primary btn-sm active"><input name="third_weibo_enable" type="radio" value="0" checked>关闭</label>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">App Key</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="third_weibo_key" value="{{ $third_weibo_key }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">App Secret</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="third_weibo_secret" value="{{ $third_weibo_secret }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">回调地址</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="third_weibo_callback" value="{{ $third_weibo_callback }}">
                                    </div>
                                </div>
                            </div>
                            <div class="panel-heading mb20"><i>微信登陆设置</i></div>
                            <div class="form-horizontal col-md-12">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">微信登陆功能开启</label>
                                    <div class="col-md-6">
                                        <div class="btn-group" data-toggle="buttons">
                                            @if($third_weixin_enable)
                                                <label class="btn btn-primary btn-sm active"><input name="third_weixin_enable" type="radio" value="1" checked>开启</label>
                                                <label class="btn btn-primary btn-sm"><input name="third_weixin_enable" type="radio" value="0">关闭</label>
                                            @else
                                                <label class="btn btn-primary btn-sm"><input name="third_weixin_enable" type="radio" value="1">开启</label>
                                                <label class="btn btn-primary btn-sm active"><input name="third_weixin_enable" type="radio" value="0" checked>关闭</label>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">App Key</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="third_weixin_key" value="{{ $third_weixin_key }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">App Secret</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="third_weixin_secret" value="{{ $third_weixin_secret }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">回调地址</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="third_weixin_callback" value="{{ $third_weixin_callback }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-horizontal col-md-12 mt20">
                                <div class="form-group">
                                    <label class="col-md-4 control-label"></label>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary" style="width: 100%;">提交</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection