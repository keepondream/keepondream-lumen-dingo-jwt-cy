<?php

namespace App\Http\Controllers;

use Dingo\Api\Routing\Helpers;
use Illuminate\Filesystem\Cache;
use Laravel\Lumen\Routing\Controller as BaseController;
use Tymon\JWTAuth\JWTAuth;

class Controller extends BaseController
{
    use Helpers;

    /**
     * @var JWTAuth|null
     */
    protected $jwt = null;

    protected $redis = null;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;

    }
}
