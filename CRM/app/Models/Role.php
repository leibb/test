<?php

namespace App\Models;

/**
 * Class Role
 * @package App\Models
 * @property int $id null
 * @property string $role_name 角色名称
 * @property string $role_des 角色描述
 * @property int $parent_role_id 角色父id
 * @property string $status 角色状态1启用2停用
 * @property string $created_at 修改时间
 * @property string $updated_at 创建时间
 */
class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'role_name', 'role_des', 'parent_role_id', 'status', 'created_at', 'updated_at'
    ];

    /**
     * 用户权限
     */
    public function permission()
    {
        return $this->belongsToMany('App\Models\Permission', 'role_permission');
    }
}