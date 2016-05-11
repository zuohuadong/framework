@extends('admin::layouts')
@section('title')分组：{{ $group->title }} - 幻灯片管理@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li><a href="{{ url('admin/flash') }}">幻灯片管理</a></li>
            <li><a href="{{ url('admin/flash/' . $group->id . '') }}">分组：{{ $group->title }}</a></li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading mb20"><i>分组：{{ $group->title }}</i></div>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th class="col-md-6">幻灯片标题</th>
                                <th class="col-md-2">是否开启</th>
                                <th class="col-md-4">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td><strong>{{ $item->title }}</strong></td>
                                    <td>
                                        <form action="{{ url('admin/flash/item/' . $item->id . '/status') }}" method="post">
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
                                        <form action="{{ url('admin/flash/item/' . $item->id) }}" method="POST">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input name="_method" type="hidden" value="DELETE">
                                            <div class="btn-group">
                                                <a class="btn btn-success btn-xs" href="{{ url('admin/flash/item/' . $item->id . '/edit') }}">
                                                    <i class="fa fa-edit"></i>编辑
                                                </a>
                                                <button class="btn btn-danger btn-xs" type="submit">
                                                    <i class="fa fa-trash-o"></i>删除
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <form action="{{ url('admin/flash/item') }}" method="POST" autocomplete="off">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="group_id" value="{{ $group->id }}">
                            <table class="table table-hover">
                                <tr>
                                    <td class="col-md-4"><strong>分组共有{{ $count }}张幻灯片</strong></td>
                                    <td class="col-md-4"><input class="form-control" name="title" placeholder="输入幻灯片标题"></td>
                                    <td class="col-md-4">
                                        <button class="btn btn-primary btn-xs" type="submit">
                                            <i class="fa fa-plus"></i>创建新幻灯片
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </form>
                        <div class="col-md-4 col-md-offset-4">
                            @if (count($errors) > 0)
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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