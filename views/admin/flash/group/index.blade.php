@extends('admin::layouts')
@section('title')幻灯片组件管理@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li class="active"><a href="{{ url('admin/flash')}}">幻灯片管理</a></li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading mb20"><i>幻灯片分组管理</i></div>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th class="col-md-5">分组名称</td>
                                <th class="col-md-3">分组别名</td>
                                <th class="col-md-4">操作</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($groups as $group)
                                <tr>
                                    <td>
                                        <strong>{{ $group->title }}</strong>
                                    </td>
                                    <td>{{ $group->alias }}</td>
                                    <td>
                                        <form action="{{ url('admin/flash/' . $group->id) }}" method="post">
                                            <input name="_method" type="hidden" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <div class="btn-group">
                                                <a class="btn btn-primary btn-xs" href="{{ url('admin/flash/' . $group->id) }}">
                                                    <i class="fa fa-search-plus"></i>幻灯片管理 </a>
                                                <a class="btn btn-success btn-xs" href="{{ url('admin/flash/' . $group->id . '/edit') }}">
                                                    <i class="fa fa-edit"></i>编辑分组 </a>
                                                <button class="btn btn-danger btn-xs">
                                                    <i class="fa fa-trash-o"></i>删除分组
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <form action="{{ url('admin/flash') }}" method="post" autocomplete="off">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <table class="table table-hover">
                                <tr>
                                    <td class="col-md-2"></td>
                                    <td class="col-md-3">
                                        <input class="form-control input-sm" name="title" placeholder="输入分组名称">
                                    </td>
                                    <td class="col-md-3">
                                        <input class="form-control input-sm" name="alias" placeholder="输入分组别名">
                                    </td>
                                    <td class="col-md-4">
                                        <button class="btn btn-primary btn-xs" type="submit">
                                            <i class="fa fa-plus"></i>添加新分组
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </form>
                        <div class="col-md-4 col-md-offset-4">
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
                </div>
            </div>
        </div>
    </div>
@endsection