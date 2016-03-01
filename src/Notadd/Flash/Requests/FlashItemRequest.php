<?php
/**
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.com
 */
namespace Notadd\Flash\Requests;
use Notadd\Foundation\Http\FormRequest;
/**
 * Class FlashItemRequest
 * @package Notadd\Flash\Requests
 */
class FlashItemRequest extends FormRequest {
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
            'title.required' => '必须填写标题！',
            'title.max'      => '标题长度超过最大限制字数！',
        ];
    }
    /**
     * @return array
     */
    public function rules() {
        return ['title' => 'required|max:300'];
    }
}