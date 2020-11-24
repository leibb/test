<?php

namespace App\Http\Controllers;

use App\Helpers\QueryHelper;
use App\Http\Requests\PermissionPost;
use App\Models\Permission;
use App\Services\PermissionService;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PermissionController extends Controller
{
    /**
     * 分配角色权限列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function rolePermission(Request $request)
    {
        $role_id = $request->get('role_id') ?? '1';
        $role = (new RoleService())->roleInfo($role_id);
        //获取当前角色拥有的权限
        $role_permissions = (new RoleService())->getRolePermission($role_id);
        $permissions = (new PermissionService())->permissionLists();
        foreach ($permissions as &$item) {
            $item['is_role'] = '2';
            foreach ($role_permissions as $v) {
                if ($v['id'] == $item['id']) {
                    $item['is_role'] = '1';
                }
            }
        }
        return view("permission.rolePermission", [
            'permissions' => $permissions,
            'role' => $role
        ]);
    }


    /**
     * 权限首页
     * @return static
     */
    public function index()
    {
        return view("permission.index");
    }

    /**
     * 权限列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists(Request $request)
    {
        $query = Permission::query();
        if ($permissionName = trim($request->get('permission_name'))) {
            $query->where("permission_name", 'like', "%" . $permissionName . "%");
        }
        if ($permissionRoute = trim($request->get('permission_route'))) {
            $query->where("permission_route", 'like', "%" . $permissionRoute . "%");
        }
        $data = QueryHelper::page($query);
        return Response::json($data);
    }

    /**
     * 权限保存
     * @param PermissionPost $request
     * @return static
     */
    public function save(PermissionPost $request)
    {
        $request->validated();
        try {
            $data = (new PermissionService())->permissionSave($request);
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess();
    }
}
