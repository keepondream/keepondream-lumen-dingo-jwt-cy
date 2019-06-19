<?php
/**
 * Description: 解决跨域问题中间件
 * Author: WangSx
 * DateTime: 2019-06-19 10:10
 */

namespace App\Http\Middleware;


use Closure;
use Symfony\Component\HttpFoundation\Response;

class CrossHttp
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->method() == 'OPTIONS') {
            $response = response('');
        } else {
            $response = $next($request);
        }

        // 允许跨域访问
        if ($request->headers->has('origin')) {
            $this->addHeader($response, 'Access-Control-Allow-Origin', $request->headers->get('origin'));
        } else {
            $this->addHeader($response, 'Access-Control-Allow-Origin', '*');
        }

        // 允许带认证cookie
        $this->addHeader($response, 'Access-Control-Allow-Credentials', true);
        // 允许请求类型
        $this->addHeader($response, 'Access-Control-Allow-Methods', 'GET, POST, OPTIONS, DELETE, PUT');

        return $response;
    }

    private function addHeader($response, $key, $value)
    {
        if (!$response->headers->has($key)) {
            /**
             * 该Response 未定义header接口,使用set设置headers
             */
            if ($response instanceof Response) {
                $response->headers->set($key, $value);
            } else {
                $response->header($key, $value);
            }
        }
    }

}

