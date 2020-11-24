<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RolePost extends FormRequest
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
            'role_name' => 'required|max:20',
            'role_des' => 'nullable|max:50',
            'parent_role_id' => 'nullable|integer',
            'status' => 'nullable|',
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
            'role_name.required' => '角色名不能为空',
            'role_name.max' => '角色名长度最大20',
            'role_des.max' => '角色描述长度最大50',
        ];
    }
}
