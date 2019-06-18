<?php

namespace App\Http\Controllers;

use App\Common\Interfaces\ControllerInterface;
use Dingo\Api\Routing\Helpers;
use Laravel\Lumen\Routing\Controller as BaseController;

abstract class Controller extends BaseController implements ControllerInterface
{
    use Helpers;

}
