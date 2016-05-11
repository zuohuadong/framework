@extends('admin::layouts')
@section('title')分组：{{ $group->title }} - 菜单管理@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li><a href="{{ url('admin/menu') }}">菜单管理</a></li>
            <li><a href="{{ url('admin/menu/' . $group->id . '') }}">分组：{{ $group->title }}</a></li>
            @foreach($crumbs as $crumb)
                <li><a href="{{ url('admin/menu/item/' . $crumb->id) }}">{{ $crumb->title }}</a></li>
            @endforeach
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading mb20"><i>分组：{{ $group->title }}</i></div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="col-md-3">菜单名称</th>
                                    <th class="col-md-3">菜单链接</th>
                                    <th class="col-md-2">是否开启</th>
                                    <th class="col-md-4">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->title }}</strong>
                                    </td>
                                    <td>{{ $item->link }}</td>
                                    <td>
                                        <form action="{{ url('admin/menu/item/' . $item->id . '/status') }}" method="post">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <div class="btn-group">
                                                @if($item->enabled)
                                                    <span class="btn btn-primary btn-xs active">开启</span>
                                                    <button type="submit" class="btn btn-primary btn-xs">关闭</button>
                                                @else
                                                    <button type="submit" class="btn btn-primary btn-xs">开启</button>
                                                    <span class="btn btn-primary btn-xs active">关闭</span>
                                                @endif
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ url('admin/menu/item/' . $item->id) }}" method="POST">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input name="_method" type="hidden" value="DELETE">
                                            <div class="btn-group">
                                                <a class="btn btn-primary btn-xs" href="{{ url('admin/menu/item/' . $item->id . '') }}">
                                                    <i class="fa fa-search-plus"></i>子级分类
                                                    <span class="badge" title="子级菜单数量">{{ $item->countSubMenu() }}</span>
                                                </a>
                                                <a class="btn btn-success btn-xs" href="{{ url('admin/menu/item/' . $item->id . '/edit') }}">
                                                    <i class="fa fa-edit"></i>编辑菜单 </a>
                                                <button class="btn btn-danger btn-xs" type="submit">
                                                    <i class="fa fa-trash-o"></i>删除菜单
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <form action="{{ url('admin/menu/item') }}" method="POST" autocomplete="off">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="parent_id" value="{{ $parent_id }}">
                            <input type="hidden" name="group_id" value="{{ $group->id }}">
                            <table class="table table-hover">
                                <tr>
                                    <td class="col-md-3"><strong>当前级别共有{{ $count }}个菜单</strong></td>
                                    <td class="col-md-2">
                                        <input class="form-control" name="title" placeholder="输入菜单名称">
                                    </td>
                                    <td class="col-md-3">
                                        <input class="form-control" name="link" placeholder="输入菜单链接">
                                    </td>
                                    <td class="col-md-4">
                                        <div class="btn-group">
                                            <button type="submit" class="btn btn-primary btn-xs">
                                                <i class="fa fa-plus"></i>当前级别创建菜单
                                            </button>
                                            <a href="{{ url('admin/menu/item/' . $parent_id . '/sort') }}" class="btn btn-info btn-xs">
                                                <i class="fa fa-sort-alpha-asc"></i>菜单排序 </a>
                                        </div>
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