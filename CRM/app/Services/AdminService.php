<?php

namespace App\Services;

use App\Helpers\QueryHelper;
use App\Models\Admin;
use App\Models\AdminRolePermission;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class AdminService
{

    /**
     * @param $role_id
     * @param $key
     * @return mixed
     */
    public function getAdminValue($role_id, $key)
    {
        return Role::query()->find($role_id)->$key;
    }

    /**
     * 管理员列表
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function adminLists($request)
    {
        $query = Admin::query()
            ->from((new Admin())->getTable() . ' as a')
            ->select('a.id', 'a.number', 'a.name', 'a.nickname', 'a.phone', 'a.email', 'a.created_at', 'a.parent_admin_id', 'a.status', 'dep.dep_name', 'dep.id as dep_id')
            ->leftJoin((new Department())->getTable() . ' as dep', 'a.dep_id', '=', 'dep.id');

        //搜索姓名
        if ($name = trim($request->get('name'))) {
            $query->where("a.name", 'like', "%" . $name . "%");
        }
        //搜索手机号
        if ($phone = trim($request->get('phone'))) {
            $query->where("a.phone", 'like', "%" . $phone . "%");
        }
        //分页数据
        $data = QueryHelper::page($query);
        foreach ($data['data'] as &$item) {
            $item['parent_admin_name'] = null;
            $item['login_time'] = (new AdminLoginLogService())->lastLoginLog($item['id']);
            if ($item['parent_admin_id']) {
                $item['parent_admin_name'] = $this->getAdminValue($item['parent_admin_id'], 'name');
            }
        }
        return $data;
    }


    /**
     * 管理员信息
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function adminInfo($id)
    {
        $model = Admin::query()
            ->from((new Admin())->getTable() . ' as a')
            ->select('a.id', 'a.number', 'a.name', 'a.nickname', 'a.phone', 'a.email', 'dep.dep_name', 'dep.id as dep_id')
            ->leftJoin((new Department())->getTable() . ' as dep', 'a.dep_id', '=', 'dep.id')
            ->where(['a.id' => $id])
            ->first();

        if (!$model) {
            throw new \Exception("数据不存在");
        }
        $admins = $model->toArray();
        $admin = Admin::query()->find($id);
        $role_ids = [];
        foreach ($admin->role as $role) {
            $role_ids[] = $role->id;
        }
        $admins['roles'] = [
            'role_ids' => $role_ids,
        ];
        return $admins;
    }

    /**
     * 管理员保存
     * @param $request
     * @return bool
     * @throws \Exception
     */
    public function adminSave($request)
    {
        $id = $request->post("id");
        $number = trim($request->post('number'));
        $name = trim($request->post('name'));
        $nickname = trim($request->post('nickname'));
        $phone = trim($request->post('phone'));
        $email = trim($request->post('email'));
        $role_ids = $request->post('role_ids');
        $dep_id = trim($request->post('dep_id'));
        $parent_admin_id = trim($request->post('parent_admin_id'));

        if (!(new DepartmentService())->checkDepStatus($dep_id)) {
            throw new \Exception("部门已停用");
        }

        if ($id) {
            $model = Admin::query()->where('id', $id)->first();
        } else {
            $model = new Admin();
        }
        $model['number'] = $number;
        $model['name'] = $name;
        $model['nickname'] = $nickname;
        $model['phone'] = $phone;
        $model['dep_id'] = $dep_id;
        $model['password'] = bcrypt('123456');
        if ($email) {
            $model['email'] = $email;
        }
        if ($parent_admin_id) {
            $model['parent_admin_id'] = $parent_admin_id;
        }
        try {
            DB::beginTransaction();
            if (!$model->save()) {
                throw new \Exception("保存失败");
            }
            if ($role_ids) {
                AdminRolePermission::query()->where(['admin_id' => $id])->delete();
                $data = [];
                foreach ($role_ids as $role_id) {
                    $item['admin_id'] = $id;
                    $item['role_id'] = $role_id;
                    $data[] = $item;
                }
                AdminRolePermission::query()->insert($data);
                (new PermissionService())->flushUserPermission();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("保存失败");
        }

        return true;
    }

    /**
     * 修改账号状态
     * @param $admin_id
     * @param $status
     * @return bool
     */
    public function changeAdminStatus($admin_id, $status)
    {
        return Admin::query()->where(['id' => $admin_id])->update(['status' => $status]) ? true : false;
    }
}
