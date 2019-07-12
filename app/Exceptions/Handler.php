<?php

namespace App\Exceptions;

use App\Exceptions\BOExceptions\AdminUserBOException;
use App\Exceptions\SyExceptions\SysException;
use App\Library\DingDing\DingHook;
use Dingo\Api\Exception\ValidationHttpException;
use Exception;
use Illuminate\Support\Facades\Redis;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
//        AuthorizationException::class,
//        HttpException::class,
//        ModelNotFoundException::class,
//        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {

        # 已知报错排除 用 && !( xxx instanceof xxxException)
        if (
            !($exception instanceof SysException)
            && !($exception instanceof AdminUserBOException)
        ) {
            # 开启钉钉预警
            $waring = false;
            $requestUrl = $request->getRequestUri();
            $requestMethod = $request->method();
            $requestParams = $request->all();
            $code = $exception->getCode();

            if ($exception instanceof ValidationHttpException) {
                $message = $exception->getErrors()->toJson(JSON_UNESCAPED_UNICODE);
                $waring = true;
            } elseif ($exception instanceof Exception) {
                $message = $exception->getMessage();
            } else {
                $message = $exception->getMessage();
            }
            $key = 'exception_list_count';
            $redis = Redis::connection();
            $code_count = $redis->zscore($key, $code);
            if (is_null($code_count)) {
                $redis->zadd($key, [$code => 0]);
            }
            $redis->zincrby($key, 1, $code);
            $code_out_count = $redis->zscore($key, $code);
            if ($waring) {
                $msg_header = '⚠️⚠️⚠️警告: 
';
            } else {
                $msg_header = '🐘🐘🐘通知:
';
            }
            $msg = $msg_header . '请求接口: ' . $requestUrl . ' ;
请求方式: ' . $requestMethod . ' ;
请求参数: ' . var_export($requestParams, true) . ' ;
错误码: ' . $code . ' ;
错误消息: ' . $message . ' ;
累计频次: ' . $code_out_count . ' ;';
//            $result = DingHook::sendMsg($msg, null, $waring);
        }

//        return parent::render($request, $exception);
    }
}
