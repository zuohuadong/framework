@extends('admin::layouts')
@section('title')排序 - 分组：{{ $group->title }} - 菜单管理@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li><a href="{{ url('admin/menu') }}">菜单管理</a></li>
            <li><a href="{{ url('admin/menu/' . $group->id) }}">分组：{{ $group->title }}</a></li>
            @foreach($crumbs as $crumb)
                <li><a href="{{ url('admin/menu/item/' . $crumb->id) }}">{{ $crumb->title }}</a></li>
            @endforeach
            <li><a href="{{ url('admin/menu/' . $parent->id . '/sort') }}">排序</a></li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading"><i>分组：{{ $group->title }} - 排序</i></div>
                        <form action="{{ url('admin/menu/item/' . $parent->id . '/sorting') }}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <ul class="list-group sort-list-group" data-toggle="sortable">
                                @foreach($items as $item)
                                    <li class="list-group-item">
                                        <input type="hidden" name="order[{{ $item->id }}]" value="{{ $item->order_id }}">
                                        <span class="badge right">{{ $item->order_id }}</span>
                                        <strong>{{ $item->title }}</strong>
                                    </li>
                                @endforeach
                            </ul>
                            <button type="submit" class="btn btn-primary mb20 mr20 mt20 right">提交</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('admin-js')
    <script src="{{ asset('statics/admin/js/jquery-ui.min.js') }}"></script>
@endsection