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
            <li><a href="{{ url('admin/page/' . $page->id . '/sort') }}">排序</a></li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading"><i>页面：{{ $page->title }} - 排序</i></div>
                        <form action="{{ url('admin/page/' . $page->id . '/sorting') }}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <ul class="list-group sort-list-group" data-toggle="sortable">
                                @foreach($pages as $value)
                                    <li class="list-group-item">
                                        <input type="hidden" name="order[{{ $value->id }}]" value="{{ $value->order_id }}">
                                        <span class="badge right">{{ $value->order_id }}</span>
                                        <strong>{{ $value->title }}</strong>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary mb20 mr20 mt20" style="width: 80%;">提交</button>
                            </div>
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