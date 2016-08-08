@extends('admin::layouts')
@section('title')重置密码@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li class="active"><a href="{{ url('eminem/password')}}">重置密码</a></li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-lined clearfix mb30">
                        <div class="panel-heading mb20"><i>重置密码</i></div>
                        <div class="col-md-4 col-md-offset-4 mb5">
                            @if (count($errors) > 0)
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    @foreach ($errors->all() as $error)
                                        <p><strong>{{ $error }}</strong></p>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <form class="form-horizontal col-md-12" action="{{ url('admin/password') }}" autocomplete="off" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">原密码</label>
                                <div class="col-md-4">
                                    <input type="password" class="form-control" name="oldpassword">
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">新密码</label>
                                <div class="col-md-4">
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label">确认新密码</label>
                                <div class="col-md-4">
                                    <input type="password" class="form-control" name="password_confirmation">
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label class="col-md-4 control-label"></label>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary btn-sm" style="width: 100%;">提交</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection