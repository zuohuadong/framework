@extends('admin::layouts')
@section('title')编辑：{{ $category->title }} - 分类管理@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li><a href="{{ url('admin/category') }}">分类管理</a></li>
            @foreach($crumbs as $crumb)
                <li><a href="{{ url('admin/category/' . $crumb->id) }}">{{ $crumb->title }}</a></li>
            @endforeach
            <li><a href="{{ url('admin/category/' . $category->id . '/edit') }}">编辑分类：{{ $category->title }}</a></li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading mb20"><i>编辑：{{ $category->title }}</i></div>
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
                        <form class="form-horizontal col-md-12" action="{{ url('admin/category/' . $category->id) }}" autocomplete="off" enctype="multipart/form-data" method="post">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">名称</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="title" value="{{ $category->title }}">
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">别名</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="alias" value="{{ $category->alias }}">
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">描述</label>
                                <div class="col-md-4">
                                    <textarea class="form-control" name="description" rows="4">{{ $category->description }}</textarea>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">类型</label>
                                <div class="col-md-4">
                                    <select class="form-control" name="type">
                                        @foreach($types as $key=>$value)
                                            @if($category->type == $key)
                                                <option value="{{ $key }}" selected="">{{ $value }}</option>
                                            @else
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">背景颜色</label>
                                <div class="col-md-4">
                                    <div class="input-group mb0" data-toggle="colorpicker">
                                        <input type="text" class="form-control" name="background_color" value="{{ $category->background_color }}">
                                        <span class="input-group-addon"><i></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">SEO标题</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="seo_title" value="{{ $category->seo_title }}">
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">SEO关键字</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="seo_keyword" value="{{ $category->seo_keyword }}">
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">SEO描述</label>
                                <div class="col-md-4">
                                    <textarea class="form-control" name="seo_description" rows="4">{{ $category->seo_description }}</textarea>
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">背景图片</label>
                                <div class="col-md-4">
                                <span class="btn btn-success btn-file">
                                    <i class="fa fa-image"></i>
                                    <span>上传图片</span>
                                    <input type="file" data-toggle="upload-image" data-target="background-image" name="background_image">
                                </span>
                                    @if($category->background_image)
                                        <div id="background-image" class="image-preview">
                                            <img src="{{ asset($category->background_image) }}" alt="" class="img-responsive">
                                        </div>
                                    @else
                                        <div id="background-image" class="image-preview" style="display: none;"></div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">头部图片</label>
                                <div class="col-md-4">
                                <span class="btn btn-success btn-file">
                                    <i class="fa fa-image"></i>
                                    <span>上传图片</span>
                                    <input type="file" data-toggle="upload-image" data-target="top-image" name="top_image">
                                </span>
                                    @if($category->top_image)
                                        <div id="top-image" class="image-preview">
                                            <img src="{{ asset($category->top_image) }}" alt="" class="img-responsive">
                                        </div>
                                    @else
                                        <div id="top-image" class="image-preview" style="display: none;"></div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">状态</label>
                                <div class="col-md-4">
                                    <div class="btn-group" data-toggle="buttons">
                                        @if($category->enabled)
                                            <label class="btn btn-primary active"><input name="enabled" type="radio" value="1" checked>开启</label>
                                            <label class="btn btn-primary"><input name="enabled" type="radio" value="0">关闭</label>
                                        @else
                                            <label class="btn btn-primary"><input name="enabled" type="radio" value="1">开启</label>
                                            <label class="btn btn-primary active"><input name="enabled" type="radio" value="0" checked>关闭</label>
                                        @endif
                                    </div>
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
@section('admin-css')
    @css('admin::less.default.bootstrap-colorpicker')
@endsection
@section('admin-js')
    <script src="{{ asset('statics/admin/js/upload-preview.js') }}"></script>
    <script src="{{ asset('statics/admin/js/bootstrap-colorpicker.min.js') }}"></script>
@endsection