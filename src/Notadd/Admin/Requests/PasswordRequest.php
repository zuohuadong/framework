<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-08 14:32
 */
namespace Notadd\Admin\Requests;
use Notadd\Foundation\Http\FormRequest;
/**
 * Class PasswordRequest
 * @package Notadd\Admin\Requests
 */
class PasswordRequest extends FormRequest {
    /**
     * @return bool
     */
    public function authorize() {
        return true;
    }
    /**
     * @return array
     */
    public function messages() {
        return [
            'oldpassword.required' => '必须填写原密码！',
            'password.required' => '必须填写账号密码！',
            'password.confirmed' => '两次密码输入不一致！',
            'password.different' => '新密码与原密码不能一致！',
        ];
    }
    /**
     * @return array
     */
    public function rules() {
        return [
            'oldpassword' => 'required',
            'password' => 'required|confirmed|different:oldpassword',
        ];
    }
}