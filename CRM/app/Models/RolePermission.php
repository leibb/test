<?php

namespace App\Models;

class RolePermission extends Model
{

    protected $table = 'role_permission';

    protected $fillable = [
        'role_id', 'permission', 'created_at', 'updated_at'
    ];

}