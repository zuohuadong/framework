@extends('admin::layouts')
@section('title')支付配置@endsection
@section('content')
    <div class="page clearfix">
        <ol class="breadcrumb breadcrumb-small">
            <li>后台首页</li>
            <li class="active"><a href="{{ url('admin/payment')}}">支付配置</a></li>
        </ol>
        <div class="page-wrap">
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ url('admin/payment') }}" autocomplete="off" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="panel panel-lined clearfix mb20">
                            <div class="panel-heading mb20"><i>支付宝配置</i></div>
                            <div class="form-horizontal col-md-12">
                                <div class="form-group form-group-sm">
                                    <label class="col-md-4 control-label">商户身份</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="alipay_partner_id" value="{{ $alipay_partner_id }}">
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label class="col-md-4 control-label">商户密钥</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="alipay_partner_secret" value="{{ $alipay_partner_secret }}">
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label class="col-md-4 control-label">商户邮箱地址</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="alipay_partner_email" value="{{ $alipay_partner_email }}">
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label class="col-md-4 control-label">返回地址</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="alipay_return_url" value="{{ $alipay_return_url }}">
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label class="col-md-4 control-label">通知地址</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="alipay_notify_url" value="{{ $alipay_notify_url }}">
                                    </div>
                                </div>
                            </div>
                            <div class="panel-heading mb20"><i>微信支付配置</i></div>
                            <div class="form-horizontal col-md-12">
                                <div class="form-group form-group-sm">
                                    <label class="col-md-4 control-label">身份标识</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="wechat_app_id" value="{{ $wechat_app_id }}">
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label class="col-md-4 control-label">身份密钥</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="wechat_app_secret" value="{{ $wechat_app_secret }}">
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label class="col-md-4 control-label">微信支付商户号</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="wechat_mch_id" value="{{ $wechat_mch_id }}">
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label class="col-md-4 control-label">微信支付商户密钥</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="wechat_mch_secret" value="{{ $wechat_mch_secret }}">
                                    </div>
                                </div>
                                <div class="form-group form-group-sm">
                                    <label class="col-md-4 control-label">回调地址</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="wechat_notify_url" value="{{ $wechat_notify_url }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-horizontal col-md-12 mt20">
                                <div class="form-group form-group-sm">
                                    <label class="col-md-4 control-label"></label>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary btn-sm" style="width: 100%;">提交</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection