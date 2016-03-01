@extends('admin::layouts')
@section('title')编辑分组：{{ $group->title }} - 幻灯片管理@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li><a href="{{ url('admin/flash') }}">幻灯片管理</a></li>
            <li><a href="{{ url('admin/flash/' . $group->id . '/edit') }}">编辑分组：{{ $group->title }}</a></li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading mb20"><i>编辑分组：{{ $group->title }}</i></div>
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
                        <form class="form-horizontal col-md-12" action="{{ url('admin/flash/' . $group->id) }}" autocomplete="off" method="post">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label class="col-md-4 control-label">分组名称</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="title" value="{{ $group->title }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label">分组别名</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="alias" value="{{ $group->alias }}">
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