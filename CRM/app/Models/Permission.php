<?php

namespace App\Models;

/**
 * Class Permission
 * @package App\Models
 * @property int $id null
 * @property string $permission_name 权限名称
 * @property string $permission_route 权限路由
 * @property string $created_at 修改时间
 * @property string $updated_at 创建时间
 */
class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = [
        'permission_name', 'permission_route', 'created_at', 'updated_at'
    ];

}