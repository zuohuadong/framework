@extends('admin::layouts')
@section('title')插件管理@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li class="active"><a href="{{ url('admin/extension')}}">插件管理</a></li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-12">
                    @if(isset($message))
                        <div class="alert alert-success alert-dismissible" role="alert" style="margin-bottom: 15px;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <p><strong>提示：</strong>{{ $message }}</p>
                        </div>
                    @endif
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading mb20"><i>插件管理</i></div>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th class="col-md-5">插件名称</th>
                                <th class="col-md-4">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($list as $key=>$value)
                                <tr>
                                    <td>
                                        <strong>{{ $key }}</strong>
                                    </td>
                                    <td>
                                        <form action="{{ url('admin/extension/' . $key) }}" method="post">
                                            <input type="hidden" name="_method" value="put">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <div class="btn-group">
                                                @if($value)
                                                    <button class="btn btn-info btn-xs">
                                                        <i class="fa fa-check"></i>已启用
                                                    </button>
                                                @else
                                                    <button class="btn btn-info btn-xs">
                                                        <i class="fa fa-circle-o"></i>未启用
                                                    </button>
                                                @endif
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection