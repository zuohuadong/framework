@extends('admin::layouts')
@section('title')网站信息设置@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li class="active"><a href="{{ url('admin/site')}}">网站信息</a></li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading mb20"><i>网站信息</i></div>
                        <div class="col-md-4 col-md-offset-4 mb5">
                            @if (isset($message))
                                <div class="alert alert-success alert-dismissible" role="alert" style="margin-bottom: 15px;">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <p><strong>提示：</strong>{{ $message }}！</p>
                                </div>
                            @endif
                            @if (count($errors) > 0)
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                        <p><strong>{{ $error }}</strong></p>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <form class="form-horizontal col-md-12" action="{{ url('admin/site') }}" autocomplete="off" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label class="col-md-4 control-label">网站名称</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control input-sm" name="title" value="{{ app('request')->old('title', $title) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">网站域名</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control input-sm" name="domain" value="{{ app('request')->old('domain', $domain) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">备案信息</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control input-sm" name="beian" value="{{ app('request')->old('beian', $beian) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">站长邮箱</label>
                                <div class="col-md-4">
                                    <input type="email" class="form-control input-sm" name="email" value="{{ app('request')->old('email', $email) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">统计代码</label>
                                <div class="col-md-4">
                                    <textarea class="form-control input-sm" name="statistics" rows="10">{{ app('request')->old('statistics', $statistics) }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">版权信息</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control input-sm" name="copyright" value="{{ app('request')->old('copyright', $copyright) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">公司名称</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control input-sm" name="company" value="{{ app('request')->old('company', $company) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">首页设置</label>
                                <div class="col-md-4">
                                    <select class="form-control input-sm" name="home">
                                        @if($home == 'default')
                                            <option value="default" selected>默认首页</option>
                                        @else
                                            <option value="default">默认首页</option>
                                        @endif
                                        @foreach($pages as $key=>$value)
                                            @if($home == 'page_' . $value['id'])
                                                <option value="page_{{ $value['id'] }}" selected>{{ $value['title'] }}</option>
                                            @else
                                                <option value="page_{{ $value['id'] }}">{{ $value['title'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label"></label>
                                <div class="col-md-4">
                                    <button class="btn btn-primary right" type="submit">提交</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection