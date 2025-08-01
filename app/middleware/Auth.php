<?php
namespace app\middleware;

use think\Response;

class Auth
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        // 认证逻辑已移到 BaseController::initialize() 中
        // 这里直接放行请求
        return $next($request);
    }
}