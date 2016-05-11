@extends('admin::layouts')
@section('title')编辑：{{ $link->title }} - 友情链接管理@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li><a href="{{ url('admin/link') }}">友情链接管理</a></li>
            <li class="active"><a href="{{ url('admin/link/' . $link->id . '/edit') }}">编辑：{{ $link->title }}</a>
            </li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading mb20"><i>编辑友情链接：{{ $link->title }}</i></div>
                        <div class="col-md-4 col-md-offset-4 mb5">
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
                        <form class="form-horizontal col-md-12" action="{{ url('admin/link/' . $link->id) }}" autocomplete="off" enctype="multipart/form-data" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="put">
                            <div class="form-group form-group-sm">
                                <label class="col-md-3 control-label">名称</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="title" value="{{ $link->title }}">
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-3 control-label">链接</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" name="link" rows="1">{{ $link->link }}</textarea>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-3 control-label">图标</label>
                                <div class="col-md-6">
                                <span class="btn btn-success btn-file">
                                    <i class="fa fa-image"></i>
                                    <span>上传图片</span>
                                    <input type="file" data-toggle="upload-image" data-target="icon" name="icon">
                                </span>
                                    @if($link->icon)
                                        <div id="icon" class="image-preview">
                                            <img src="{{ asset($link->icon) }}" alt="" class="img-responsive">
                                        </div>
                                    @else
                                        <div id="icon" class="image-preview" style="display: none;"></div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-3 control-label">简介</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" name="description" rows="3">{{ $link->description }}</textarea>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-3 control-label">状态</label>
                                <div class="col-md-6">
                                    <div class="btn-group" data-toggle="buttons">
                                        @if($link->is_enabled)
                                            <label class="btn btn-primary active"><input name="is_enabled" type="radio" value="1" checked>开启</label>
                                            <label class="btn btn-primary"><input name="is_enabled" type="radio" value="0">关闭</label>
                                        @else
                                            <label class="btn btn-primary"><input name="is_enabled" type="radio" value="1">开启</label>
                                            <label class="btn btn-primary active"><input name="is_enabled" type="radio" value="0" checked>关闭</label>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-3 control-label"></label>
                                <div class="col-md-6">
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
@section('admin-css')
    <link rel="stylesheet" href="{{ asset('statics/admin/css/bootstrap-colorpicker.min.css') }}">
@endsection
@section('admin-js')
    <script src="{{ asset('statics/admin/js/jquery.uploadPreview.js') }}"></script>
    <script src="{{ asset('statics/admin/js/bootstrap-colorpicker.min.js') }}"></script>
@endsection