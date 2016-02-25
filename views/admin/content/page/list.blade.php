@extends('admin::layouts')
@section('title')页面管理@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li><a href="{{ url('admin/page') }}">页面管理</a></li>
            @foreach($crumbs as $crumb)
                <li><a href="{{ url('admin/page/' . $crumb->id) }}">{{ $crumb->title }}</a></li>
            @endforeach
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading mb20"><i>页面管理</i></div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="col-md-3">页面标题</td>
                                    <th class="col-md-2">路由</th>
                                    <th class="col-md-3">创建时间</td>
                                    <th class="col-md-4">操作</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pages as $page)
                                    <tr>
                                        <td>
                                            <a href="{{ url('page/' . $page->id) }}" target="_blank"><strong>{{ $page->title }}</strong></a>
                                        </td>
                                        <td>{{ $page->alias }}</td>
                                        <td>{{ $page->created_at }}</td>
                                        <td>
                                            <form action="{{ URL('admin/page/'.$page->id) }}" method="POST" style="display: inline;">
                                                <input name="_method" type="hidden" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <div class="btn-group">
                                                    <a class="btn btn-primary btn-xs" href="{{ url('admin/page/' . $page->id) }}">
                                                        <i class="fa fa-search-plus"></i>子页管理
                                                        <span class="badge" title="该分类下子级页面数量">{{ $page->countSubPages() }}</span>
                                                    </a>
                                                    <a class="btn btn-success btn-xs" href="{{ url('admin/page/' . $page->id . '/edit') }}"><i class="fa fa-edit"></i>编辑 </a>
                                                    <a class="btn btn-info btn-xs" href="{{ url('admin/page/' . $page->id . '/move') }}"><i class="fa fa-arrows"></i>移动</a>
                                                    <button class="btn btn-danger btn-xs"><i class="fa fa-trash-o"></i>删除</button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <form action="{{ url('admin/page') }}" method="post" autocomplete="off">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="parent_id" value="{{ $id }}">
                            <table class="table table-hover">
                                <tr>
                                    <td class="col-md-5"><strong>当前级别有{{ $count }}个分类</strong></td>
                                    <td class="col-md-3">
                                        <input class="form-control input-sm" name="title" placeholder="输入页面名称"></td>
                                    <td class="col-md-4">
                                        @if($id && $count)
                                            <div class="btn-group">
                                                <button class="btn btn-primary btn-xs" type="submit">
                                                    <i class="fa fa-plus"></i>添加新页面
                                                </button>
                                                <a href="{{ url('admin/page/' . $id . '/sort') }}" class="btn btn-info btn-xs">
                                                    <i class="fa fa-sort-alpha-asc"></i>子页排序 </a>
                                            </div>
                                        @else
                                            <button class="btn btn-primary btn-xs" type="submit">
                                                <i class="fa fa-plus"></i>添加新页面
                                            </button>
                                        @endif
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