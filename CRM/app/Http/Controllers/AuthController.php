<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Services\AdminLoginLogService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use AuthenticatesUsers;


    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * 登陆
     */
    public function doLogin(Request $request)
    {
        if (Auth::attempt(['phone' => $request->post('phone'), 'password' => $request->post('password')])) {
            $admin = Admin::query()->where('phone', $request->post('phone'))->first();
            if (!(new AdminLoginLogService())->logSave($request, $admin->id)) {
                $this->ajaxError('系统错误');
            };
            return $this->ajaxSuccess();
        }

        return $this->ajaxError('用户名或密码错误');
    }


}
