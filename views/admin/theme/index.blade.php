@extends('admin::layouts')
@section('title')主题管理@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li class="active"><a href="{{ url('admin/theme')}}">主题管理</a></li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-12">
                    @if(isset($message))
                        <div class="alert alert-success alert-dismissible" role="alert" style="margin-bottom: 15px;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <p><strong>提示：</strong></p>
                            @foreach($message as $item)
                                <p>{{ $item }}</p>
                            @endforeach
                        </div>
                    @endif
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading mb20"><i>主题管理</i></div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="col-md-5">分组名称</th>
                                    <th class="col-md-3">分组别名</th>
                                    <th class="col-md-4">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($themes as $theme)
                                    <tr>
                                        <td>
                                            <strong>{{ $theme->getTitle() }}</strong>
                                        </td>
                                        <td>{{ $theme->getAlias() }}</td>
                                        <td>
                                            <form action="{{ url('admin/theme/' . $theme->getAlias()) }}" method="post">
                                                <input type="hidden" name="_method" value="put">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <div class="btn-group">
                                                    @if($theme->isDefault())
                                                        <button class="btn btn-info btn-xs" disabled>
                                                            <i class="fa fa-check"></i>当前默认模板
                                                        </button>
                                                    @else
                                                        <button class="btn btn-info btn-xs">
                                                            <i class="fa fa-circle-o"></i>设为默认主题
                                                        </button>
                                                    @endif
                                                    <a href="{{ url('admin/theme/publish/' . $theme->getAlias()) }}" class="btn btn-success btn-xs">
                                                        <i class="fa fa-upload"></i>发布静态资源
                                                    </a>
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