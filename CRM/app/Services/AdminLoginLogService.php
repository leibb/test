<?php

namespace App\Services;

use App\Models\AdminLoginLog;

class AdminLoginLogService
{
    /**
     * 登陆信息保存
     * @param $request
     * @param $admin_id
     * @return bool
     * @throws \Exception
     */
    public function logSave($request, $admin_id)
    {
        $model = AdminLoginLog::query()->create([
            'admin_id' => $admin_id,
            'login_time' => date('Y-m-d H:i:s', time()),
            'login_ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        if (!$model->save()) {
            throw new \Exception("保存失败");
        }
        return true;
    }

    /**
     * 获取最新登陆信息
     * @param $admin_id
     * @return mixed|string
     */
    public function lastLoginLog($admin_id)
    {
        $model = AdminLoginLog::query()->where('admin_id', $admin_id)->orderByDesc('id')->first();
        if (!$model) {
            return '';
        }
        return $model->login_time;
    }
}