@extends('admin::layouts')
@section('title')Migration工具@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li><a href="{{ url('admin/migration')}}">Migration工具</a></li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-12">
                    @if(isset($message))
                        <div class="alert alert-success alert-dismissible" role="alert" style="margin-bottom: 15px;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <p><strong>提示：</strong>{{ $message }}</p>
                        </div>
                    @endif
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading mb20"><i>创建Migration文件</i></div>
                        <form class="form-horizontal col-md-12" action="{{ url('admin/migration') }}" autocomplete="off" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label class="col-md-4 control-label">name属性</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">--create属性</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="create">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">--table</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="table">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label"></label>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary" style="width: 100%;">提交</button>
                                </div>
                            </div>
                        </form>
                        <div class="panel-heading mb20"><i>执行Migrate命令</i></div>
                        <form class="form-horizontal col-md-12" action="{{ url('admin/migrate') }}" autocomplete="off" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label class="col-md-4 control-label"></label>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-success" style="width: 100%;">执行Migrate命令</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection