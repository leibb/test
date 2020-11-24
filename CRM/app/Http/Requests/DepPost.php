<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepPost extends FormRequest
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
            'dep_name' => 'required|max:20',
            'dep_des' => 'nullable|max:50',
            'parent_dep_id' => 'nullable|integer',
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
            'dep_name.required' => '部门名不能为空',
            'dep_name.max' => '部门名长度最大20',
            'dep_des.max' => '部门描述长度最大50',
        ];
    }
}
