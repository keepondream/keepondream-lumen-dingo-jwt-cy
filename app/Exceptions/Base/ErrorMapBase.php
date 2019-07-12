<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-07-11 15:01
 */

namespace App\Exceptions\Base;


use Illuminate\Support\Facades\Log;

abstract class ErrorMapBase
{
    protected function __clone()
    {
    }

    public function getDisplayErrMsg($errno, $params) {

        if($params instanceof ExceptionContext)
        {
            $params = $params->toArray();
        }
        elseif (is_array($params)) {}
        elseif(!is_array($params) && is_string($params) && strlen($params) > 0){
            $params = [$params];
        } else {
            $params = [];
        }

        $errMap = $this->getErrMsgMapping();
        if (isset($errMap[$errno])) {
            $tmp_error_content = $errMap[$errno];
            $strParams = empty($params) ? '' : json_encode($params);
            $tmp_error_content = strlen($strParams) > 0 ? $tmp_error_content.':'.$strParams : $tmp_error_content;
            return $tmp_error_content;
        } else {
            Log::warning('Not Found Errno Map Msg...');
            return '';
        }
    }

    /**
     * @return array
     */
    abstract protected function getErrMsgMapping();
}