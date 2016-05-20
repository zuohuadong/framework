@extends('admin::layouts')
@section('title')文章管理@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li><a href="{{ url('admin/article')}}">文章管理</a></li>
            <li><a href="{{ url('admin/category/' . $category->parent_id) }}">{{ $category->title }}</a></li>
            <li><a href="{{ url('admin/article/' . $article->id . '/edit')}}">编辑文章：{{ $article->title }}</a></li>
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
            <form action="{{ url('admin/article/' . $article->id) }}" class="form-horizontal" enctype="multipart/form-data" method="post">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="panel panel-lined clearfix mb30">
                    <div class="panel-heading mb20"><i>编辑文章：{{ $article->title }}</i></div>
                        <div class="col-md-8">
                            <div class="form-group form-group-sm">
                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="title" placeholder="请输入标题" value="{{ app('request')->old('title', $article->title) }}">
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <div class="col-md-12">
                                    <script id="editor-container" type="text/plain" data-toggle="ueditor" name="content">{!! app('request')->old('content', $article->content) !!}</script>
                                </div>
                            </div>
                            @if($category->type == 'western.information')
                                <div class="form-group form-group-sm">
                                    <div class="col-md-12">
                                        <div class="btn-group" data-toggle="buttons">
                                            @foreach($recommends as $key=>$recommend)
                                                @if($recommend['has'])
                                                    <label class="btn btn-info btn-xs active">
                                                        <input type="checkbox" autocomplete="off" name="recommends[]" value="{{ $key }}" checked>{{ $recommend['name'] }}
                                                    </label>
                                                @else
                                                    <label class="btn btn-info btn-xs">
                                                        <input type="checkbox" autocomplete="off" name="recommends[]" value="{{ $key }}">{{ $recommend['name'] }}
                                                    </label>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group form-group-sm">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-sm" style="width: 100%;">提交</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">上传缩略图</label>
                                        <div class="col-md-8">
                                            <span class="btn btn-success btn-sm btn-file">
                                                <i class="fa fa-image"></i>
                                                <span>上传图片</span>
                                                <input type="file" data-toggle="upload-image" data-target="thumb-image" name="thumb_image">
                                            </span>
                                            @if($article->thumb_image)
                                                <div id="thumb-image" class="image-preview mt15">
                                                    <img src="{{ asset($article->thumb_image) }}" alt="" class="img-responsive">
                                                </div>
                                            @else
                                                <div id="thumb-image" class="image-preview" style="display: none;"></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">作者</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="author" value="{{ app('request')->old('author', $article->author) }}">
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">标签</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control" name="keyword" rows="3">{{ app('request')->old('keyword', $article->keyword) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">摘要</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control" name="description" rows="11">{{ app('request')->old('description', $article->description) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">来源</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="from_author" value="{{ app('request')->old('from_author', $article->from_author) }}">
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">来源链接</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control" name="from_url" rows="2">{{ app('request')->old('from_url', $article->from_url) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">点击次数</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">是否置顶</label>
                                        <div class="col-md-8">
                                            <div class="btn-group" data-toggle="buttons">
                                                @if($article->is_sticky)
                                                    <label class="btn btn-info btn-sm active"><input type="radio" name="is_sticky" value="1" checked>置顶</label>
                                                    <label class="btn btn-info btn-sm"><input type="radio" name="is_sticky" value="0">不置顶</label>
                                                @else
                                                    <label class="btn btn-info btn-sm"><input type="radio" name="is_sticky" value="1">置顶</label>
                                                    <label class="btn btn-info btn-sm active"><input type="radio" name="is_sticky" value="0" checked>不置顶</label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">创建日期</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" data-toggle="datetimepicker" name="created_at" readonly value="{{ app('request')->old('created_at', $article->created_at) }}">
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">更新时间</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="updated_at" value="{{ $article->updated_at }}" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('admin-css')
    <link rel="stylesheet" href="{{ asset('statics/admin/css/bootstrap-datetimepicker.min.css') }}">
@endsection
@section('admin-js')
    <script src="{{ asset('statics/ueditor/ueditor.config.js') }}"></script>
    <script src="{{ asset('statics/ueditor/ueditor.all.min.js') }}"></script>
    <script src="{{ asset('statics/admin/js/upload-preview.js') }}"></script>
    <script src="{{ asset('statics/admin/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('statics/admin/js/bootstrap-datetimepicker.zh-CN.js') }}"></script>
@endsection