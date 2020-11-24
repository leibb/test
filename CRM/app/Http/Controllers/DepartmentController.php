<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepPost;
use App\Services\DepartmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DepartmentController extends Controller
{

    /**
     * 部门管理
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $deps = (new DepartmentService())->allDepLists();
        return view("department.index", [
            'deps' => $deps
        ]);
    }

    /**
     * 部门列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists(Request $request)
    {
        $data = (new DepartmentService())->depLists($request);
        return Response::json($data);
    }

    /**
     * 部门详情
     * @param Request $request
     * @return DepartmentController
     * @throws \Exception
     */
    public function info(Request $request)
    {
        $id = $request->get("id");
        if (!$id) {
            throw new \Exception("请求参数错误");
        }
        try {
            $data = (new DepartmentService())->depInfo($id);
            return $this->ajaxSuccess($data);
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
    }


    /**
     * 部门保存
     * @param DepPost $request
     * @return static
     */
    public function save(DepPost $request)
    {
        $request->validated();
        try {
            (new DepartmentService())->depSave($request);
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess();
    }


    /**
     * 修改部门状态
     * @param Request $request
     * @return static
     */
    public function saveDepStatus(Request $request)
    {
        try {
            (new DepartmentService())->changeDepStatus($request->post('id'), $request->post('status'));
        } catch (\Exception $e) {
            return $this->ajaxError($e->getMessage());
        }
        return $this->ajaxSuccess();
    }
}
