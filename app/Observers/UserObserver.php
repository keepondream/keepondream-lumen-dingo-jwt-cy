<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-23 23:06
 */

namespace App\Observers;


use Illuminate\Support\Facades\Log;

class UserObserver
{
    public function created()
    {
        Log::debug('userObserver : ' . __FUNCTION__);
    }

    public function updated()
    {
        Log::debug('userObserver : ' . __FUNCTION__);
    }

    public function saved()
    {
        Log::debug('userObserver : ' . __FUNCTION__);
    }

    public function deleted()
    {
        Log::debug('userObserver : ' . __FUNCTION__);
    }
}