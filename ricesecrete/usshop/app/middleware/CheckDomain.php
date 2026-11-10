<?php

namespace app\middleware;

use app\base\BaseRequest;
use think\facade\Config;

/**
 * 中间件-跨域处理
 * Class CheckDomain
 * @package app\middleware
 */
class CheckDomain
{
    public function handle(BaseRequest $request, \Closure $next)
    {


        //不限制域名
        $origin = $request->header('Origin', '');
        header("Access-Control-Allow-Origin: {$origin}");
        header("Access-Control-Allow-Headers: Authorization,Origin, X-Requested-With, Content-Type, Accept,Client-Type");
        header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Credentials: true');
        if ($request->isOptions()) {
            exit();
        }
        //限制有效域名
//        $origin = isset($_SERVER["HTTP_ORIGIN"]) ? $_SERVER["HTTP_ORIGIN"] : '';
//        $allow_origin = Config::get('app.allow_origin');
//        if (in_array($origin, $allow_origin)) {
//            header("Access-Control-Allow-Origin: {$origin}");
//            header("Access-Control-Allow-Headers: Authorization,Origin, X-Requested-With, Content-Type, Accept,Client-Type");
//            header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
//            header('Access-Control-Allow-Credentials: true');
//            if ($request->isOptions()) {
//                exit();
//            }
//        }
        return $next($request);
    }
}
