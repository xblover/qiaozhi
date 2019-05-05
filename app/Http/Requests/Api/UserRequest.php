<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * 确定用户是否有权发出此请求。
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * 获取适用于请求的验证规则。
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|between:3,25|unique:users,name',
            'password' => 'required|string|min:6',
            'phone' => [
                'required',
                'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199|(147))\d{8}$/',
                'unique:users'
            ],
//            'verification_key' => 'required|string',
//            'verification_code' => 'required|string',

        ];
    }

//    public function attributes()
//    {
//        return [
//            'verification_key' => '短信验证码 key',
//            'verification_code' => '短信验证码',
//        ];
//    }

}
