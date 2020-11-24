<?php

namespace App\Models;

/**
 * Class AdminRolePermission
 * @package app\Models
 * @property int $id null
 * @property int $admin_id 后台用户id
 * @property int $role_id 角色id
 * @property string $permission 权限
 */
class AdminRolePermission extends Model
{
    protected $table = 'admin_role_permission';

    protected $fillable = [
        'admin_id', 'role_id', 'permission'
    ];
}