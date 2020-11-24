<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionPost extends FormRequest
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
            'permission_name' => 'required|max:30',
            'permission_route' => 'required|max:40',
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
            'permission_name.required' => '权限名不能为空',
            'permission_name.max' => '权限名长度最大30',
            'permission_route.required' => '权限路径不能为空',
            'permission_route.max' => '权限名权限路径长度最大40',
        ];
    }
}
