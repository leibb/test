<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminPost;
use App\Services\AdminService;
use App\Services\DepartmentService;
use App\Services\PermissionService;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminController extends Controller
{
    /**
     * 账号管理
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $allPermissions = (new PermissionService())->permissionLists();
        $roles = (new RoleService())->allRoleLists();
        $deps = (new DepartmentService())->allDepLists();
        return view("admin.index", [
            'allPermissions' => array_column($allPermissions, 'permission_name'),
            'roles' => $roles,
            'deps' => $deps
        ]);
    }

    /**
     * 账号列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists(Request $request)
    {
        $data = (new AdminService())->adminLists($request);
        return Response::json($data);
    }

    /**
     * 账号详情
     * @param Request $request
     * @return AdminController
     * @throws \Exception
     */
    public function info(Request $request)
    {
        $id = $request->get("id");
        if (!$id) {
            throw new \Exception("请求参数错误");
        }
        try {
            $data = (new AdminService())->adminInfo($id);
            return $this->ajaxSuccess($data);
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
    }

    /**
     * 账号保存
     * @param AdminPost $request
     * @return static
     */
    public function save(AdminPost $request)
    {
        $request->validated();
        try {
            (new AdminService())->adminSave($request);
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess();
    }

    /**
     * 修改账号状态
     * @param Request $request
     * @return static
     */
    public function saveAdminStatus(Request $request)
    {
        try {
            (new AdminService())->changeAdminStatus($request->post('id'), $request->post('status'));
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess();
    }
}
