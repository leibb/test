<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'number' => 'required|max:20',
            'name' => 'required|max:20',
            'nickname' => 'required|max:20',
            'phone' => 'required|max:11|regex:/^1[345789][0-9]{9}$/',
            'email' => 'nullable|email',
            'dep_id' => 'required',
            'role_ids' => 'required',
        ];
    }

    /**
     * 获取被定义验证规则的错误消息
     * @return array
     * @translator laravelacademy.org
     */
    public function messages()
    {
        return [
            'number.required' => '工号不能为空',
            'number.max' => '工号长度最大20',
            'name.required' => '姓名不能为空',
            'name.max' => '姓名长度最大20',
            'nickname.required' => '昵称不能为空',
            'nickname.max' => '昵称长度最大20',
            'phone.required' => '手机不能为空',
            'phone.regex' => '手机格式不对',
            'email.email' => '邮箱格式不对',
            'dep_id.required' => '部门不能为空',
            'role_ids.required' => '角色不能为空',
        ];
    }
}
