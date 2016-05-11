@extends('admin::layouts')
@section('title')附件管理@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li><a href="{{ url('admin/attachment') }}">附件管理</a></li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading"><i>页面：附件管理 - 功能选择</i></div>
                        <div class="row mt20">
                            <div class="col-md-4 col-md-offset-4">
                                <div class="list-group">
                                    <a href="{{ url('admin/attachment/config') }}" class="list-group-item">附件配置</a>
                                    <a href="{{ url('admin/attachment/list') }}" class="list-group-item">附件列表</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection