@extends('admin::layouts')
@section('title')编辑页面：{{ $page->title }} - 页面管理@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li><a href="{{ url('admin/page') }}">页面管理</a></li>
            @foreach($crumbs as $crumb)
                @if($page->id == $crumb->id)
                    <li><a href="{{ url('admin/page/' . $page->id . '/edit') }}">编辑页面：{{ $page->title }}</a></li>
                @else
                    <li><a href="{{ url('admin/page/' . $crumb->id) }}">{{ $crumb->title }}</a></li>
                @endif
            @endforeach
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
            <form action="{{ url('admin/page/' . $page->id) }}" autocomplete="off" class="form-horizontal" enctype="multipart/form-data" method="post">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="panel panel-lined clearfix mb30">
                    <div class="panel-heading mb20"><i>编辑页面：{{ $page->title }}</i></div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="title" value="{{ app('request')->old('title', $page->title) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-12">
                                <script id="editor-container" type="text/plain" data-toggle="ueditor" name="content">{!! app('request')->old('content', $page->content) !!}</script>
                            </div>
                        </div>
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
                                @if($page->thumb_image)
                                    <div id="thumb-image" class="image-preview">
                                        <img src="{{ asset($page->thumb_image) }}" alt="" class="img-responsive">
                                    </div>
                                @else
                                    <div id="thumb-image" class="image-preview" style="display: none;"></div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">静态化名称</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="alias" value="{{ app('request')->old('alias', $page->alias) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">二级域名</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">是否开启</label>
                            <div class="col-md-8">
                                @if ($page->enabled || app('request')->old('enabled'))
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-primary btn-sm active"><input name="enabled" type="radio" value="1" checked>开启</label>
                                        <label class="btn btn-primary btn-sm"><input name="enabled" type="radio" value="0">关闭</label>
                                    </div>
                                @else
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-primary btn-sm"><input name="enabled" type="radio" value="1" checked>开启</label>
                                        <label class="btn btn-primary btn-sm active"><input name="enabled" type="radio" value="0">关闭</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">标签</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="keyword" rows="3">{{ app('request')->old('keyword', $page->keyword) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">摘要</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="description" rows="5">{{ app('request')->old('description', $page->description) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">模板</label>
                            <div class="col-md-8">
                                <select class="form-control" name="template">
                                    @foreach($templates as $key=>$value)
                                        @if($page->template == $key)
                                            <option value="{{ $key }}" selected>{{ $value }}</option>
                                        @else
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">浏览次数</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="view_count" value="{{ app('request')->old('view_count', $page->view_count) }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">创建日期</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" value="{{ $page->created_at }}" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">修改日期</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" value="{{ $page->updated_at }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('admin-js')
    <script src="{{ asset('/statics/ueditor/ueditor.config.js') }}"></script>
    <script src="{{ asset('/statics/ueditor/ueditor.all.min.js') }}"></script>
    <script src="{{ asset('statics/admin/js/upload-preview.js') }}"></script>
@endsection