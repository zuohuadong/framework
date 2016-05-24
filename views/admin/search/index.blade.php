@extends('admin::layouts')
@section('title')搜索配置@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li><a href="{{ url('admin/search') }}">搜索配置</a></li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading"><i>页面：搜索配置</i></div>
                        <div class="row mt20">
                            <div class="col-md-12">
                                <form class="form-horizontal col-md-12" action="{{ url('admin/search') }}" autocomplete="off" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">是否开启百度站内搜索</label>
                                        <div class="col-md-4">
                                            <div class="btn-group" data-toggle="buttons">
                                                @if($allow_baidu_zhannei)
                                                    <label class="btn btn-primary btn-sm active"><input name="allow_baidu_zhannei" type="radio" value="1" checked>开启</label>
                                                    <label class="btn btn-primary btn-sm"><input name="allow_baidu_zhannei" type="radio" value="0">关闭</label>
                                                @else
                                                    <label class="btn btn-primary btn-sm"><input name="allow_baidu_zhannei" type="radio" value="1">开启</label>
                                                    <label class="btn btn-primary btn-sm active"><input name="allow_baidu_zhannei" type="radio" value="0" checked>关闭</label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">百度站内搜索代码</label>
                                        <div class="col-md-4">
                                            <textarea class="form-control" name="baidu_zhannei_code" rows="10">{{ old('baidu_zhannei_code', $baidu_zhannei_code) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label"></label>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary btn-sm" style="width: 100%;">提交</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection