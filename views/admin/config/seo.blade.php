@extends('admin::layouts')
@section('title')全局SEO配置@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li class="active"><a href="{{ url('admin/seo')}}">SEO设置</a></li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading mb20"><i>全局SEO设置</i></div>
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
                        <form class="form-horizontal col-md-12" action="{{ url('admin/seo') }}" autocomplete="off" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label class="col-md-4 control-label">SEO标题</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control input-sm" name="title" value="{{ app('request')->old('title', $title) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">SEO关键字</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control input-sm" name="keyword" value="{{ app('request')->old('keyword', $keyword) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">SEO描述</label>
                                <div class="col-md-4">
                                    <textarea class="form-control input-sm" name="description" rows="10">{{ app('request')->old('description', $description) }}</textarea>
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