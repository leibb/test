<?php

namespace App\Services;

use App\Helpers\QueryHelper;
use App\Models\Role;
use App\Models\RolePermission;

class RoleService
{

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $statusOpen;

    /**
     * @var \Illuminate\Config\Repository|mixed
     */
    protected $statusClose;

    /**
     * DepartmentService constructor.
     */
    public function __construct()
    {
        $this->statusOpen = config('status.role.statusOpen');
        $this->statusClose = config('status.role.statusClose');
    }


    /**
     * @param $role_id
     * @param $key
     * @return mixed
     */
    public function getRoleValue($role_id, $key)
    {
        return Role::query()->find($role_id)->$key;
    }

    /**
     * 获取所有角色
     * @return array
     */
    public function allRoleLists()
    {
        return Role::query()->select(['id', 'role_name', 'parent_role_id'])->where('status', config('status.role.statusOpen'))->get()->toArray();
    }

    /**
     * 根据角色获取权限
     * @param $role_id
     * @return array
     */
    public function getRolePermission($role_id)
    {
        $role = Role::query()->find($role_id);
        $permissions = [];
        foreach ($role->permission as $permission) {
            $permissions[] = $permission->toArray();
        }
        return $permissions;
    }


    /**
     * 角色列表
     * @param $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function roleLists($request)
    {
        $query = Role::query();
        //搜索角色
        if ($roleName = trim($request->get('role_name'))) {
            $query->where("role_name", 'like', "%" . $roleName . "%");
        }
        //分页数据
        $data = QueryHelper::page($query);
        foreach ($data['data'] as &$item) {
            $item['parent_role_name'] = null;
            if ($item['parent_role_id']) {
                $item['parent_role_name'] = $this->getRoleValue($item['parent_role_id'], 'role_name');
            }
        }
        return $data;
    }


    /**
     * 角色信息
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function roleInfo($id)
    {
        $model = Role::query()->where('id', $id)->select(['id', 'role_name', 'role_des', 'parent_role_id'])->first();
        if (!$model) {
            throw new \Exception("数据不存在");
        }
        $roleInfo = $model->toArray();
        $roleInfo['parent_role_name'] = null;
        if ($roleInfo['parent_role_id']) {
            $roleInfo['parent_role_name'] = $this->getRoleValue($roleInfo['parent_role_id'], 'role_name');
        }
        return $roleInfo;
    }

    /**
     * 角色保存
     * @param $request
     * @return bool
     * @throws \Exception
     */
    public function roleSave($request)
    {
        $id = $request->post("id");
        $role_name = trim($request->post('role_name'));
        $role_des = trim($request->post('role_des'));
        $parent_role_id = trim($request->post('parent_role_id'));

        if (!$role_name) {
            throw new \Exception("请输入角色名称");
        }
        if ($id) {
            $model = Role::query()->where('id', $id)->first();
            if ($role_name != $model->role_name && $this->isRoleName($role_name)) {
                throw new \Exception("部门名已存在");
            }
        } else {
            $model = new Role();
            if ($this->isRoleName($role_name)) {
                throw new \Exception("部门名已存在");
            }
        }
        $model['role_name'] = $role_name;
        if ($role_des) {
            $model['role_des'] = $role_des;
        }
        if ($parent_role_id) {
            $model['parent_role_id'] = $parent_role_id;
        }
        if (!$model->save()) {
            throw new \Exception("保存失败");
        }
        return true;
    }

    /**
     * 角色权限保存
     * @param $request
     * @return bool
     * @throws \Exception
     */
    public function saveRolePermission($request)
    {
        $role_id = $request->post("role_id");
        $permissions = $request->post('permission');
        $data = [];
        foreach ($permissions as $permission) {
            $item['role_id'] = $role_id;
            $item['permission_id'] = $permission;
            $data[] = $item;
        }
        RolePermission::query()->where('role_id', $role_id)->delete();
        $model = RolePermission::query()->insert($data);
        if (!$model) {
            throw new \Exception("保存失败");
        }
        (new PermissionService())->flushUserPermission();
        return true;
    }

    /**
     * 角色是否启动
     * @param $role_id
     * @return bool
     */
    public function checkRoleStatus($role_id)
    {
        return Role::query()->where(['status' => $this->statusOpen, 'id' => $role_id])->first() ? true : false;
    }

    /**
     * 角色名是否存在
     * @param $role_name
     * @return bool
     */
    public function isRoleName($role_name)
    {
        return Role::query()->where(['role_name' => $role_name])->first() ? true : false;
    }

    /**
     * 修改角色状态
     * @param $role_id
     * @param $status
     * @return bool
     */
    public function changeRoleStatus($role_id, $status)
    {
        if (Role::query()->where(['id' => $role_id])->update(['status' => $status])) {
            (new PermissionService())->flushUserPermission();
            return true;
        }
        return false;
    }
}