@extends('admin::layouts')
@section('title')友情链接管理@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li><a href="{{ url('admin/link') }}">友情链接管理</a></li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading mb20"><i>友情链接管理</i></div>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th class="col-md-3">名称</th>
                                <th class="col-md-3">链接</th>
                                <th class="col-md-2">是否开启</th>
                                <th class="col-md-4">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($links as $link)
                                <tr>
                                    <td>
                                        <strong>{{ $link->title }}</strong>
                                    </td>
                                    <td>{{ $link->link }}</td>
                                    <td>
                                        <form action="{{ url('admin/link' . $link->id . '/status') }}" method="post">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <div class="btn-group">
                                                @if($link->is_enabled)
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
                                        <form action="{{ url('admin/link/' . $link->id) }}" method="post">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input name="_method" type="hidden" value="delete">
                                            <div class="btn-group">
                                                <a class="btn btn-success btn-xs" href="{{ url('admin/link/' . $link->id . '/edit') }}">
                                                    <i class="fa fa-edit"></i>编辑友情链接 </a>
                                                <button class="btn btn-danger btn-xs" type="submit">
                                                    <i class="fa fa-trash-o"></i>删除友情链接
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <form action="{{ url('admin/link') }}" autocomplete="off" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <table class="table table-hover">
                                <tr>
                                    <td class="col-md-3"><strong>当前级别共有{{ $count }}个友情链接</strong></td>
                                    <td class="col-md-2">
                                        <input class="form-control input-sm" name="title" placeholder="输入名称"></td>
                                    <td class="col-md-3">
                                        <input class="form-control input-sm" name="link" placeholder="输入链接"></td>
                                    <td class="col-md-4">
                                        <div class="btn-group">
                                            <button type="submit" class="btn btn-primary btn-xs">
                                                <i class="fa fa-plus"></i>创建友情链接
                                            </button>
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