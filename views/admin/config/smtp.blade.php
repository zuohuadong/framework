@extends('admin::layouts')
@section('title')SMTP配置@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li class="active"><a href="{{ url('admin/seo')}}">SMTP配置</a></li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                @include('admin::common.messages')
                <div class="col-md-12">
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading mb20"><i>SMTP配置</i></div>
                        <form class="form-horizontal col-md-12" action="{{ url('admin/smtp') }}" autocomplete="off" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">SEO标题</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="title" value="{{ app('request')->old('title', $title) }}">
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">SEO关键字</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="keyword" value="{{ app('request')->old('keyword', $keyword) }}">
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">SEO描述</label>
                                <div class="col-md-4">
                                    <textarea class="form-control" name="description" rows="10">{{ app('request')->old('description', $description) }}</textarea>
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
@endsection