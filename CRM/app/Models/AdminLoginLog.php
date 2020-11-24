<?php

namespace App\Models;

/**
 * Class Admin
 * @package App\Models
 * @property int $id null
 * @property int $admin_id 账号id
 * @property string $login_time 登陆时间
 * @property string $login_ip 登陆ip
 * @property string $user_agent 登录ua
 * @property string $created_at 修改时间
 * @property string $updated_at 创建时间
 */
class AdminLoginLog extends Model
{
    protected $table = 'admin_login_log';

    protected $fillable = [
        'admin_id', 'login_time', 'login_ip', 'user_agent', 'created_at', 'updated_at'
    ];
}