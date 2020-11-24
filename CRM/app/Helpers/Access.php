<?php

namespace App\Helpers;


use App\Services\PermissionService;
use artem_c\emmet\Emmet;
use Illuminate\Support\Facades\Log;

class Access
{
    /**
     * 检测指定用户是否有指定权限的访问权限
     * 只有设置了权限的操作才进行权限判断
     * @param string $permission 指定权限
     * @param bool $adminId 指定用户
     * @return bool
     */
    public static function can($permission, $adminId = false)
    {
        //未指定用户，默认为当前登录用户
        if (!$adminId) {
            $admin = app("request")->user();
            $adminId = $admin->id;
        }
        //只有设置了权限的操作才进行权限判断
        $permissionLists = (new PermissionService)->permissionLists();
        if (!in_array($permission, array_column($permissionLists, 'permission_route'))) {
            return false;
        }
        $userPermissions = (new PermissionService())->getAdminPermission($adminId);
        //用户有访问权限
        if (in_array($permission, $userPermissions)) {
            return true;
        }
        return false;
    }


    /**
     * @param $permission
     * @param $html
     * @param bool $ret
     * @return bool
     */
    public static function canAndRender($permission, $html, $ret = false)
    {
        $html = self::can($permission) ? str_replace(':url', $permission, $html) : '';
        if ($ret) {
            return $html;
        } else {
            echo $html;
        }
    }

    /**
     * @param $permission
     * @param $emmet
     */
    public static function canEmmet($permission, $emmet)
    {
        $html = self::can($permission) ? (new Emmet($emmet))->create(['url' => url($permission)]) : '';
        echo $html;
    }


}