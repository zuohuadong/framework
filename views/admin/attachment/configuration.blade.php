@extends('admin::layouts')
@section('title')附件管理@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li><a href="{{ url('admin/attachment') }}">附件管理</a></li>
            <li><a href="{{ url('admin/attachment/configuration') }}">附件上传配置</a></li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading"><i>页面：附件上传配置</i></div>
                        <div class="row mt20">
                            <div class="col-md-12">
                                <form class="form-horizontal col-md-12" action="{{ url('admin/attachment/configuration') }}" autocomplete="off" enctype="multipart/form-data" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">图片处理引擎</label>
                                        <div class="col-md-4">
                                            <div class="btn-group" data-toggle="buttons">
                                                @if($engine == 'gd')
                                                    <label class="btn btn-primary btn-sm active"><input name="engine" type="radio" value="gd" checked>GD库</label>
                                                    <label class="btn btn-primary btn-sm"><input name="engine" type="radio" value="imagemagick">ImageMagick</label>
                                                @else
                                                    <label class="btn btn-primary btn-sm"><input name="engine" type="radio" value="gd">GD库</label>
                                                    <label class="btn btn-primary btn-sm active"><input name="engine" type="radio" value="imagemagick" checked>ImageMagick</label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">附件上传尺寸限制(KB)</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="size_file_limit" value="{{ app('request')->old('size_file_limit', $size_file_limit) }}">
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">图片上传尺寸限制(KB)</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="size_image_limit" value="{{ app('request')->old('size_image_limit', $size_image_limit) }}">
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">视频上传尺寸限制(KB)</label>
                                        <div class="col-md-4">
                                            <input type="text" class="form-control" name="size_video_limit" value="{{ app('request')->old('size_video_limit', $size_video_limit) }}">
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">允许上传的图片格式</label>
                                        <div class="col-md-4">
                                            <textarea class="form-control" name="allow_image_format" rows="3">{{ app('request')->old('allow_image_format', $allow_image_format) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">允许远程下载的文件格式</label>
                                        <div class="col-md-4">
                                            <textarea class="form-control" name="allow_catcher_format" rows="3">{{ app('request')->old('allow_catcher_format', $allow_catcher_format) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">允许上传的视频格式</label>
                                        <div class="col-md-4">
                                            <textarea class="form-control" name="allow_video_format" rows="3">{{ app('request')->old('allow_video_format', $allow_video_format) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">允许上传的文件格式</label>
                                        <div class="col-md-4">
                                            <textarea class="form-control" name="allow_file_format" rows="3">{{ app('request')->old('allow_file_format', $allow_file_format) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">允许管理的图片格式</label>
                                        <div class="col-md-4">
                                            <textarea class="form-control" name="allow_manager_image_format" rows="3">{{ app('request')->old('allow_manager_image_format', $allow_manager_image_format) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">允许管理的文件格式</label>
                                        <div class="col-md-4">
                                            <textarea class="form-control" name="allow_manager_file_format" rows="3">{{ app('request')->old('allow_manager_file_format', $allow_manager_file_format) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">格式配置说明</label>
                                        <label class="col-md-4 control-label">使用半角分号分隔每一个格式</label>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">是否开启水印</label>
                                        <div class="col-md-4">
                                            <div class="btn-group" data-toggle="buttons">
                                                @if($allow_watermark)
                                                    <label class="btn btn-primary btn-sm active"><input name="allow_watermark" type="radio" value="1" checked>开启</label>
                                                    <label class="btn btn-primary btn-sm"><input name="allow_watermark" type="radio" value="0">关闭</label>
                                                @else
                                                    <label class="btn btn-primary btn-sm"><input name="allow_watermark" type="radio" value="1">开启</label>
                                                    <label class="btn btn-primary btn-sm active"><input name="allow_watermark" type="radio" value="0" checked>关闭</label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">上传水印图片</label>
                                        <div class="col-md-4">
                                            <span class="btn btn-success btn-file">
                                                <i class="fa fa-image"></i>
                                                <span>上传图片</span>
                                                <input type="file" data-toggle="upload-image" data-target="watermark-file" name="watermark_file">
                                            </span>
                                            @if($watermark_file)
                                                <div id="thumb-image" class="image-preview">
                                                    <img src="{{ asset($watermark_file) }}" alt="" class="img-responsive">
                                                </div>
                                            @else
                                                <div id="watermark-file" class="image-preview" style="display: none;"></div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label class="col-md-4 control-label">水印配置说明</label>
                                        <label class="col-md-4 control-label">使用同时开启水印和上传水印图片才能正确配置水印功能</label>
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
@section('admin-js')
    <script src="{{ asset('statics/admin/js/upload-preview.js') }}"></script>
@endsection