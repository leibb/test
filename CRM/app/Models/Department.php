<?php

namespace App\Models;

/**
 * Class Department
 * @package App\Models
 * @property int $id null
 * @property string $dep_name 部门名称
 * @property string $dep_des 部门描述
 * @property int $dep_num 部门人数
 * @property int $parent_dep_id 部门父id
 * @property string $status 部门状态1启用2停用
 * @property string $created_at 修改时间
 * @property string $updated_at 创建时间
 */
class Department extends Model
{
    protected $table = 'department';

    protected $fillable = [
        'dep_name', 'dep_des', 'dep_num', 'parent_dep_id', 'status', 'created_at', 'updated_at'
    ];

    /**
     * 获取部门的账号
     */
    public function admins()
    {
        return $this->hasMany('App\Models\Admin');
    }
}