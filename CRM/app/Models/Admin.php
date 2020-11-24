<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class Admin
 * @package App\Models
 * @property int $id null
 * @property string $number 工号
 * @property string $name 姓名
 * @property string $nickname 昵称
 * @property string $phone 手机号
 * @property string $email 邮箱
 * @property string $password 密码
 * @property string $remember_token
 * @property string $status 账号状态1启用2停用
 * @property int $dep_id 部门id
 * @property string $created_at 修改时间
 * @property string $updated_at 创建时间
 */
class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'number', 'name', 'nickname', 'phone', 'email', 'password', 'remember_token', 'status', 'dep_id', 'created_at', 'updated_at'
    ];

    protected $hidden = [
        'password'
    ];

    public function role()
    {
        return $this->belongsToMany('App\Models\Role', 'admin_role_permission');
    }

    /**
     * 获取账户对应的部门
     */
    public function dep()
    {
        return $this->belongsTo('App\Models\Department');
    }

}