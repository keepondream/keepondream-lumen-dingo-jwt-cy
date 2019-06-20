<?php

namespace App\Exceptions;

use App\Common\Constants\CONSTANT_ErrCodes;
use Dingo\Api\Exception\ValidationHttpException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        // 参数验证错误的异常
        if ($exception instanceof ValidationHttpException) {
            return failed($exception->getErrors()->first(), $exception->getStatusCode());
        }

        // token 无效
        if ($exception instanceof TokenInvalidException) {
            return failed(CONSTANT_ErrCodes::getError(10001), 10001);
        }

        // token 失效
        if ($exception instanceof TokenExpiredException) {
            return failed(CONSTANT_ErrCodes::getError(10002), 10002);
        }

        // token 不存在
        if ($exception instanceof TokenExpiredException) {
            return failed(CONSTANT_ErrCodes::getError(10003), 10003);
        }

        // 授权异常
        if ($exception instanceof AuthorizationException) {
            return failed(CONSTANT_ErrCodes::getError(403), 403);
        }

        // 用户认证的异常,黑名单
        if ($exception instanceof UnauthorizedHttpException) {
            return failed(CONSTANT_ErrCodes::getError(10004), 10004);
        }

        // 其他未知错误,可hook

        return parent::render($request, $exception);
    }
}
