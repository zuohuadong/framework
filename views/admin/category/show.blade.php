@extends('admin::layouts')
@section('title')分类管理@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li><a href="{{ url('admin/category') }}">分类管理</a></li>
            @foreach($crumbs as $crumb)
                <li><a href="{{ url('admin/category/' . $crumb->id) }}">{{ $crumb->title }}</a></li>
            @endforeach
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading mb20"><i>分类管理</i></div>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th class="col-md-4">栏目名称</th>
                                <th class="col-md-2">是否开启</th>
                                <th class="col-md-6">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td>
                                        <a href="{{ url('category/' . $category->id) }}" target="_blank"><strong>{{ $category->title }}</strong></a>
                                    </td>
                                    <td>
                                        <form action="{{ url('admin/category/' . $category->id . '/status') }}" method="post">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <div class="btn-group">
                                                @if($category->enabled)
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
                                        <form action="{{ url('admin/category/' . $category->id) }}" method="POST">
                                            <input name="_method" type="hidden" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <div class="btn-group">
                                                <a class="btn btn-primary btn-xs" href="{{ url('admin/category', ['category' => $category->id]) }}">
                                                    <i class="fa fa-search-plus"></i>子级分类
                                                    <span class="badge" title="该分类下子级页面数量">{{ $category->countSubCategories() }}</span>
                                                </a>
                                                <a class="btn btn-success btn-xs" href="{{ url('admin/category/' . $category->id . '/edit') }}"><i class="fa fa-edit"></i>编辑</a>
                                                <a class="btn btn-info btn-xs" href="{{ url('admin/article/' . $category->id) }}">
                                                    <i class="fa fa-list"></i>文章列表
                                                    <span class="badge" title="该分类下文章数量">{{ $category->countArticles() }}</span>
                                                </a>
                                                <a class="btn btn-info btn-xs" href="{{ url('admin/article/create/?category=' . $category->id) }}"><i class="fa fa-plus-square"></i>添加文章</a>
                                                <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-trash-o"></i>删除</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <form action="{{ url('admin/category') }}" method="post" autocomplete="off">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="parent_id" value="{{ $id }}">
                            <table class="table table-hover">
                                <tr>
                                    <td class="col-md-4"><strong>当前级别有{{ $count }}个分类</strong></td>
                                    <td class="col-md-4">
                                        <input type="text" class="form-control input-sm" name="title" placeholder="输入分类名称"></td>
                                    <td class="col-md-4">
                                        <button class="btn btn-primary btn-xs" type="submit">
                                            <i class="fa fa-plus"></i>创建新分类
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