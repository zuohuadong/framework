@extends('admin::layouts')
@section('title')页面管理@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li><a href="{{ url('admin/category') }}">分类管理</a></li>
            @foreach($crumbs as $crumb)
                <li><a href="{{ url('admin/category/' . $crumb->id) }}">{{ $crumb->title }}</a></li>
            @endforeach
            <li><a href="{{ url('admin/category/' . $category->id . '/move') }}">移动</a></li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading"><i>分类：{{ $category->title }} - 移动</i></div>
                        <form action="{{ url('admin/category/' . $category->id . '/moving') }}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <div class="btn-group-vertical mt20 mb20" data-toggle="buttons">
                                            @if($category->parent_id == 0)
                                                <label class="btn btn-primary active">
                                                    <input type="radio" name="parent_id" value="0" checked>根页面(无父级页面)
                                                </label>
                                            @else
                                                <label class="btn btn-primary">
                                                    <input type="radio" name="parent_id" value="0">根页面(无父级页面)
                                                </label>
                                            @endif
                                            @foreach($list as $value)
                                                @if($category->parent_id == $value->id)
                                                    <label class="btn btn-primary active">
                                                        <input type="radio" name="parent_id" value="{{ $value->id }}" checked>{{ $value->title }}
                                                    </label>
                                                @else
                                                    <label class="btn btn-primary">
                                                        <input type="radio" name="parent_id" value="{{ $value->id }}">{{ $value->title }}
                                                    </label>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary mb20 mt20 btn-sm" style="width: 100%;">提交</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection