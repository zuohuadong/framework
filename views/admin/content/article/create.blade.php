@extends('admin::layouts')
@section('title')文章管理@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li><a href="{{ url('admin/article')}}">文章管理</a></li>
            <li><a href="{{ url('admin/category/' . $category->parent_id) }}">{{ $category->title }}</a></li>
            <li><a href="{{ url('admin/article/create')}}">创建文章</a></li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-8">
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
            </div>
            <form action="{{ url('admin/article') }}" class="form-horizontal" enctype="multipart/form-data" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="category_id" value="{{ $category->id }}">
                <div class="panel panel-lined clearfix mb30">
                    <div class="panel-heading mb20"><i>创建文章</i></div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="title" placeholder="请输入标题" value="{{ app('request')->old('title') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <script id="editor-container" type="text/plain" data-toggle="ueditor" name="content">{!! app('request')->old('content') !!}</script>
                            </div>
                        </div>
                        @if($category->type == 'western.information')
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="btn-group" data-toggle="buttons">
                                        @foreach($recommends as $key=>$recommend)
                                            <label class="btn btn-info btn-xs">
                                                <input type="checkbox" autocomplete="off" name="recommends[]" value="{{ $key }}">{{ $recommend['name'] }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" style="width: 100%;">提交</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-md-4 control-label">上传缩略图</label>
                            <div class="col-md-8">
                        <span class="btn btn-success btn-file">
                            <i class="fa fa-image"></i>
                            <span>上传图片</span>
                            <input type="file" data-toggle="upload-image" data-target="thumb-image" name="thumb_image">
                        </span>
                                <div id="thumb-image" class="image-preview" style="display: none;"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">作者</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="author" value="{{ app('request')->old('author') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">标签</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="keyword" rows="3">{{ app('request')->old('keyword') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">摘要</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="description" rows="11">{{ app('request')->old('description') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">来源</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="from_author" value="{{ app('request')->old('from_author') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">来源链接</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="from_url" rows="2">{{ app('request')->old('from_url') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('admin-js')
    <script src="{{ asset('editor/ueditor/ueditor.config.js') }}"></script>
    <script src="{{ asset('editor/ueditor/ueditor.all.min.js') }}"></script>
    <script src="{{ asset('statics/admin/js/upload-preview.js') }}"></script>
@endsection