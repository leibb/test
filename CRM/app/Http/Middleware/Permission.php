<?php

namespace App\Http\Middleware;

use App\Helpers\Access;
use App\Services\PermissionService;
use Closure;
use Elasticsearch\Common\Exceptions\Serializer\JsonErrorException;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     *
     */
    public function handle($request, Closure $next)
    {
        //所有权限
        $permissionLists = (new PermissionService())->permissionLists();
        $permissions = array_column($permissionLists, 'permission_route');
        //请求路径
        $path = '/' . trim($request->getPathInfo(), '/');
        //只有权限定义存在才会判断是否有权限
        if (in_array($path, $permissions) && !Access::can($path)) {
            if ($request->expectsJson()) {
                throw new AccessDeniedHttpException("无权限操作");
            }
            echo "无操作权限";
            exit;
        }
        return $next($request);
    }

}
