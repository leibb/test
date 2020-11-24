<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\Redis;

class PermissionService
{
    /**
     * 用户权限
     * @var
     */
    public static $adminPermissions;

    /**
     * 用户权限缓存key
     * @var string
     */
    public $adminPermissionsKey = "crm_admin_permissions";

    /**
     * 所有权限列表缓存key
     * @var string
     */
    public $permissionsListKey = "crm_permission_list";

    /**
     * 获取权限列表
     * @return array
     */
    public function permissionLists()
    {
        $permissions = unserialize(Redis::get($this->permissionsListKey));
        if (!$permissions) {
            $permissions = Permission::query()->select(['id', 'permission_name', 'permission_route'])->get()->toArray();
            Redis::set($this->permissionsListKey, serialize($permissions));
        }
        return $permissions;
    }

    /**
     * 权限保存
     * @param $request
     * @return bool
     * @throws \Exception
     */
    public function permissionSave($request)
    {
        $permission_name = trim($request->post('permission_name'));
        $permission_route = trim($request->post('permission_route'));

        $role = Permission::query()->where('permission_name', $permission_name)->orWhere('permission_route', $permission_route)->first();
        if ($role) {
            throw new \Exception("权限名或路径已存在");
        }

        $model = Permission::query()->create([
            'permission_name' => $permission_name,
            'permission_route' => $permission_route
        ]);
        if (!$model->save()) {
            throw new \Exception("保存失败");
        }
        $this->flushPermissionList();
        return true;
    }

    /**
     * 刷新指定用户权限
     * @param $adminId
     * @return array
     */
    public function refreshAdminPermission($adminId)
    {
        $admins = Admin::query()->find($adminId);
        $adminPermissions = [];
        foreach ($admins->role as $role) {
            $permissions = Role::query()->where('status', config('status.role.statusOpen'))->find($role->id);
            if (isset($permissions->permission)) {
                foreach ($permissions->permission as $permission) {
                    $adminPermissions[] = $permission->permission_route;
                }
            }
        }
        Redis::hSet($this->adminPermissionsKey, $adminId, serialize($adminPermissions));
        return $adminPermissions;
    }

    /**
     * 获取指定用户权限
     * @param $adminId
     * @return array|mixed
     */
    public function getAdminPermission($adminId)
    {
        if (!isset(self::$adminPermissions[$adminId])) {
            $permissions = Redis::hGet($this->adminPermissionsKey, $adminId);
            if ($permissions) {
                self::$adminPermissions[$adminId] = unserialize($permissions);
            } else {
                self::$adminPermissions[$adminId] = $this->refreshAdminPermission($adminId);
            }
        }
        return self::$adminPermissions[$adminId];
    }

    /**
     * 清空用户权限缓存
     */
    public function flushUserPermission()
    {
        Redis::del($this->adminPermissionsKey);
    }

    /**
     * 清空权限所有列表缓存
     */
    public function flushPermissionList()
    {
        Redis::del($this->permissionsListKey);
    }

}