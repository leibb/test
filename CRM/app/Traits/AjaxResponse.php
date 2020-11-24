<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait AjaxResponse
{
    /**
     * 成功返回
     * @param null $data
     * @return JsonResponse
     */
    protected function ajaxSuccess($data = null)
    {
        $ret = array(
            'code' => 200,
            'msg' => 'success',
            'data' => $data
        );
        return $this->ajaxReturn($ret);
    }

    /**
     * 失败返回
     * @param string $msg
     * @param int $code
     * @param null $data
     * @return JsonResponse
     */
    protected function ajaxError($msg = 'error', $code = 300, $data = null)
    {
        $ret = array(
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        );
        return $this->ajaxReturn($ret);
    }

    /**
     * ajax返回
     * @param $data
     * @return JsonResponse
     */
    protected function ajaxReturn($data)
    {
        $data = json_encode($data);
        $data = str_replace(":null", ':""', $data);
        $data = json_decode($data, true);
        $response = JsonResponse::create($data);
        if (config('app.debug')) {
            $response->header('environment', config('app.env'));
        }
        return $response;
    }

}