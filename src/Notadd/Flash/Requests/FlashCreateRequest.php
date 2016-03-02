<?php
/**
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.com
 */
namespace Notadd\Flash\Requests;
use Notadd\Foundation\Http\FormRequest;
/**
 * Class FlashCreateRequest
 * @package Notadd\Flash\Requests
 */
class FlashCreateRequest extends FormRequest {
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
            'title.alpha_dash' => '分组名称只允许仅允许字母、数字、破折号（-）以及底线（_）！',
            'title.unique'     => '已有一个相同名称的分组存在！',
            'title.required'   => '必须填写分组名称！',
            'title.max'        => '分组名称长度超过最大限制字数！',
            'alias.alpha_dash' => '分组别名只允许仅允许字母、数字、破折号（-）以及底线（_）！',
            'alias.unique'     => '已有一个相同别名的分组存在！',
            'alias.required'   => '必须填写分组别名！',
            'alias.max'        => '分组别名长度超过最大限制字数！',
        ];
    }
    /**
     * @return array
     */
    public function rules() {
        return [
            'title' => 'required|unique:flashes|alpha_dash|max:300',
            'alias' => 'required|unique:flashes|alpha_dash|max:300',
        ];
    }
}