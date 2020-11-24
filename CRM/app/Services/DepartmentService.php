<?php

namespace App\Services;

use App\Helpers\QueryHelper;
use App\Models\Admin;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Http\Request;

class DepartmentService
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
        $this->statusOpen = config('status.dep.statusOpen');
        $this->statusClose = config('status.dep.statusClose');
    }

    /**
     * @param $dep_id
     * @param $key
     * @return mixed
     */
    public function getDepValue($dep_id, $key)
    {
        return Department::query()->find($dep_id)->$key;
    }


    /**
     * 获取所有角色
     * @return array
     */
    public function allDepLists()
    {
        return Department::query()->select(['id', 'dep_name', 'parent_dep_id'])->where('status', $this->statusOpen)->get()->toArray();
    }


    /**
     * 部门列表
     * @param $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function depLists($request)
    {
        $query = Department::query();
        //搜索部门
        if ($depName = trim($request->get('dep_name'))) {
            $query->where("dep_name", 'like', "%" . $depName . "%");
        }
        //分页数据
        $data = QueryHelper::page($query);
        foreach ($data['data'] as $k => $item) {
            if ($item['parent_dep_id']) {
                foreach ($data['data'] as &$v) {
                    if ($v['id'] == $item['parent_dep_id']) {
                        $v['child_dep_name'] = $item['dep_name'];
                    } else {
                        $v['child_dep_name'] = null;
                    }
                }
            } else {
                $data['data'][$k]['child_dep_name'] = null;
            }
        }
        return $data;
    }


    /**
     * 部门信息
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function depInfo($id)
    {
        $model = Department::query()->where('id', $id)->select(['id', 'dep_name', 'dep_des', 'parent_dep_id'])->first();
        if (!$model) {
            throw new \Exception("数据不存在");
        }
        $depInfo = $model->toArray();
        $depInfo['parent_dep_name'] = null;
        if ($depInfo['parent_dep_id']) {
            $depInfo['parent_dep_name'] = $this->getDepValue($depInfo['parent_dep_id'], 'dep_name');
        }
        return $depInfo;
    }

    /**
     * 部门保存
     * @param $request
     * @return bool
     * @throws \Exception
     */
    public function depSave($request)
    {
        $id = $request->post("id");
        $dep_name = trim($request->post('dep_name'));
        $dep_des = trim($request->post('dep_des'));
        $parent_dep_id = trim($request->post('parent_dep_id'));
        $status = trim($request->post('status'));
        if ($status) {
            if (Department::query()->where(['id' => $id])->update(['status' => $status])) {
                return true;
            }
            return false;
        }

        if ($id) {
            $model = Department::query()->where('id', $id)->first();
            if ($dep_name != $model->dep_name && $this->isDepName($dep_name)) {
                throw new \Exception("部门名已存在");
            }
        } else {
            $model = new Department();
            if ($this->isDepName($dep_name)) {
                throw new \Exception("部门名已存在");
            }
        }
        $model['dep_name'] = $dep_name;
        if ($dep_des) {
            $model['dep_des'] = $dep_des;
        }
        if ($parent_dep_id) {
            $model['parent_dep_id'] = $parent_dep_id;
        }
        if (!$model->save()) {
            throw new \Exception("保存失败");
        }
        return true;
    }

    /**
     * 部门是否启动
     * @param $dep_id
     * @return bool
     */
    public function checkDepStatus($dep_id)
    {
        return Department::query()->where(['status' => $this->statusOpen, 'id' => $dep_id])->first() ? true : false;
    }

    /**
     * 部门名是否存在
     * @param $dep_name
     * @return bool
     */
    public function isDepName($dep_name)
    {
        return Department::query()->where(['dep_name' => $dep_name])->first() ? true : false;
    }

    /**
     * 修改角色状态
     * @param $role_id
     * @param $status
     * @return bool
     */
    public function changeDepStatus($role_id, $status)
    {
        return Department::query()->where(['id' => $role_id])->update(['status' => $status]) ? true : false;
    }
}