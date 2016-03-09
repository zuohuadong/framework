@extends('admin::layouts')
@section('title')编辑：{{ $item->title }} - {{ $item->group->title }} - 幻灯片管理@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li><a href="{{ url('admin/flash') }}">幻灯片管理</a></li>
            <li><a href="{{ url('admin/flash/' . $item->group->id) }}">分组：{{ $item->group->title }}</a></li>
            <li><a href="{{ url('admin/flash/item/' . $item->id . '/edit') }}">编辑：{{ $item->title }}</a></li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading mb20"><i>编辑：{{ $item->title }}</i></div>
                        <div class="col-md-4 col-md-offset-4 mb5">
                            @if (count($errors) > 0)
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <p><strong>{{ $error }}</strong></p>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <form class="form-horizontal col-md-12" action="{{ url('admin/flash/item/' . $item->id) }}" autocomplete="off" enctype="multipart/form-data" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="group_id" value="{{ $item->group_id }}">
                            <div class="form-group">
                                <label class="col-md-3 control-label">名称</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="title" value="{{ $item->title }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">链接</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" name="link" rows="3">{{ $item->link }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3">以<strong>http://</strong>或<strong>https://</strong>开通的链接解析为外站链接</div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">打开方式</label>
                                <div class="col-md-6">
                                    <div class="btn-group" data-toggle="buttons">
                                        @if($item->link_target == '_blank')
                                            <label class="btn btn-primary active"><input name="link_target" type="radio" value="_blank" checked>_blank</label>
                                            <label class="btn btn-primary"><input name="link_target" type="radio" value="_self">_self</label>
                                            <label class="btn btn-primary"><input name="link_target" type="radio" value="_parent">_parent</label>
                                            <label class="btn btn-primary"><input name="link_target" type="radio" value="_top">_top</label>
                                        @elseif($item->link_target == '_self')
                                            <label class="btn btn-primary"><input name="link_target" type="radio" value="_blank" checked>_blank</label>
                                            <label class="btn btn-primary active"><input name="link_target" type="radio" value="_self">_self</label>
                                            <label class="btn btn-primary"><input name="link_target" type="radio" value="_parent">_parent</label>
                                            <label class="btn btn-primary"><input name="link_target" type="radio" value="_top">_top</label>
                                        @elseif($item->link_target == '_parent')
                                            <label class="btn btn-primary"><input name="link_target" type="radio" value="_blank" checked>_blank</label>
                                            <label class="btn btn-primary"><input name="link_target" type="radio" value="_self">_self</label>
                                            <label class="btn btn-primary active"><input name="link_target" type="radio" value="_parent">_parent</label>
                                            <label class="btn btn-primary"><input name="link_target" type="radio" value="_top">_top</label>
                                        @elseif($item->link_target == '_top')
                                            <label class="btn btn-primary"><input name="link_target" type="radio" value="_blank" checked>_blank</label>
                                            <label class="btn btn-primary"><input name="link_target" type="radio" value="_self">_self</label>
                                            <label class="btn btn-primary"><input name="link_target" type="radio" value="_parent">_parent</label>
                                            <label class="btn btn-primary active"><input name="link_target" type="radio" value="_top">_top</label>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-3"><strong>_blank：</strong>新窗口，<strong>_self：</strong>本窗口，<strong>_parent：</strong>父窗口，<strong>_top：</strong>顶层窗口。</div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">缩略图</label>
                                <div class="col-md-6">
                                <span class="btn btn-success btn-file">
                                    <i class="fa fa-image"></i>
                                    <span>上传图片</span>
                                    <input type="file" data-toggle="upload-image" data-target="thumb-image" name="thumb_image">
                                </span>
                                    @if($item->thumb_image)
                                        <div id="thumb-image" class="mt15">
                                            <img src="{{ asset($item->thumb_image) }}" alt="" class="img-responsive">
                                        </div>
                                    @else
                                        <div id="thumb-image" class="image-preview" style="display: none;"></div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">完整大图</label>
                                <div class="col-md-6">
                                    <span class="btn btn-success btn-file">
                                        <i class="fa fa-image"></i>
                                        <span>上传图片</span>
                                        <input type="file" data-toggle="upload-image" data-target="full-image" name="full_image">
                                    </span>
                                    @if($item->full_image)
                                        <div id="full-image" class="mt15">
                                            <img src="{{ asset($item->full_image) }}" alt="" class="img-responsive">
                                        </div>
                                    @else
                                        <div id="full-image" class="image-preview" style="display: none;"></div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">幻灯片说明</label>
                                <div class="col-md-6">
                                    <textarea class="form-control" name="alt_info" rows="6">{{ $item->alt_info }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">状态</label>
                                <div class="col-md-6">
                                    <div class="btn-group" data-toggle="buttons">
                                        @if($item->enabled)
                                            <label class="btn btn-primary active"><input name="enabled" type="radio" value="1" checked>开启</label>
                                            <label class="btn btn-primary"><input name="enabled" type="radio" value="0">关闭</label>
                                        @else
                                            <label class="btn btn-primary"><input name="enabled" type="radio" value="1">开启</label>
                                            <label class="btn btn-primary active"><input name="enabled" type="radio" value="0" checked>关闭</label>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"></label>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary right">提交</button>
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