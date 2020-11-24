<?php

namespace App\Http\Controllers;

use App\Http\Requests\RolePost;
use App\Models\Admin;
use App\Services\AdminService;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class RoleController extends Controller
{

    /**
     * 角色管理
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $roleList = (new RoleService())->allRoleLists();
        return view("role.index", [
            'roles' => $roleList
        ]);
    }

    /**
     * 角色列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists(Request $request)
    {
        $data = (new RoleService())->roleLists($request);
        return Response::json($data);
    }

    /**
     * 角色详情
     * @param Request $request
     * @return RoleController
     * @throws \Exception
     */
    public function info(Request $request)
    {
        $id = $request->get("id");
        if (!$id) {
            throw new \Exception("请求参数错误");
        }
        try {
            $data = (new RoleService())->roleInfo($id);
            return $this->ajaxSuccess($data);
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
    }


    /**
     * 角色保存
     * @param RolePost $request
     * @return static
     */
    public function save(RolePost $request)
    {
        $request->validated();
        try {
            (new RoleService())->roleSave($request);
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess();
    }

    /**
     * 修改角色状态
     * @param Request $request
     * @return static
     */
    public function saveRoleStatus(Request $request)
    {
        try {
            (new RoleService())->changeRoleStatus($request->post('id'), $request->post('status'));
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess();
    }

    /**
     * 角色权限保存
     * @param Request $request
     * @return static
     */
    public function savePermission(Request $request)
    {
        try {
            (new RoleService())->saveRolePermission($request);
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess();
    }
}
