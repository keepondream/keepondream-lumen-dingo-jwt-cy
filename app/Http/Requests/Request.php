<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-16 23:21
 */

namespace App\Http\Requests;


use App\Common\Interfaces\ISelfCheck;
use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-06-17 14:50
 * Class Request
 * @package App\Http\Requests
 * @method \Dingo\Api\Http\Request all()
 * @method \Dingo\Api\Http\Request input()
 */
abstract class Request implements ISelfCheck
{
    /**
     * @var \Dingo\Api\Http\Request
     */
    protected $request;


    public function __construct(\Dingo\Api\Http\Request $request)
    {
        $this->request = $request;

        $this->selfCheck();
    }

    public function selfCheck(): bool
    {
        $params = $this->getValidateParams();

        $rules = $this->getValidateRules();

        $messages = $this->getValidateMessages();

        $customAttrs = $this->getCustomAttributes();

        return $this->validateParams($params, $rules, $messages, $customAttrs);
    }

    abstract public function getValidateParams();

    abstract protected function getValidateRules();

    abstract protected function getCustomAttributes();

    protected function getValidateMessages()
    {
        return [
            'accepted' => ':attribute 必须接受',
            'active_url' => ':attribute 必须是一个合法的 URL',
            'after' => ':attribute 必须是 :date 之后的一个日期',
            'after_or_equal' => ':attribute 必须是 :date 之后或相同的一个日期',
            'alpha' => ':attribute 只能包含字母',
            'alpha_dash' => ':attribute 只能包含字母、数字、中划线或下划线',
            'alpha_num' => ':attribute 只能包含字母和数字',
            'array' => ':attribute 必须是一个数组',
            'before' => ':attribute 必须是 :date 之前的一个日期',
            'before_or_equal' => ':attribute 必须是 :date 之前或相同的一个日期',
            'between' => [
                'numeric' => ':attribute 必须在 :min 到 :max 之间',
                'file' => ':attribute 必须在 :min 到 :max KB 之间',
                'string' => ':attribute 必须在 :min 到 :max 个字符之间',
                'array' => ':attribute 必须在 :min 到 :max 项之间',
            ],
            'boolean' => ':attribute 字符必须是 true 或 false',
            'confirmed' => ':attribute 二次确认不匹配',
            'date' => ':attribute 必须是一个合法的日期',
            'date_format' => ':attribute 与给定的格式 :format 不符合',
            'different' => ':attribute 必须不同于 :other',
            'digits' => ':attribute 必须是 :digits 位.',
            'digits_between' => ':attribute 必须在 :min 和 :max 位之间',
            'dimensions' => ':attribute 具有无效的图片尺寸',
            'distinct' => ':attribute 字段具有重复值',
            'email' => ':attribute 必须是一个合法的电子邮件地址',
            'exists' => '选定的 :attribute 是无效的.',
            'file' => ':attribute 必须是一个文件',
            'filled' => ':attribute 的字段是必填的',
            'image' => ':attribute 必须是 jpeg, png, bmp 或者 gif 格式的图片',
            'in' => '选定的 :attribute 是无效的',
            'in_array' => ':attribute 字段不存在于 :other',
            'integer' => ':attribute 必须是个整数',
            'ip' => ':attribute 必须是一个合法的 IP 地址。',
            'json' => ':attribute 必须是一个合法的 JSON 字符串',
            'max' => [
                'numeric' => ':attribute 的最大长度为 :max 位',
                'file' => ':attribute 的最大为 :max',
                'string' => ':attribute 的最大长度为 :max 字符',
                'array' => ':attribute 的最大个数为 :max 个.',
            ],
            'mimes' => ':attribute 的文件类型必须是 :values',
            'min' => [
                'numeric' => ':attribute 的最小长度为 :min 位',
                'file' => ':attribute 大小至少为 :min KB',
                'string' => ':attribute 的最小长度为 :min 字符',
                'array' => ':attribute 至少有 :min 项',
            ],
            'not_in' => '选定的 :attribute 是无效的',
            'numeric' => ':attribute 必须是数字',
            'present' => ':attribute 字段必须存在',
            'regex' => ':attribute 格式是无效的',
            'required' => ':attribute 字段是必须的',
            'required_if' => ':attribute 字段是必须的当 :other 是 :value',
            'required_unless' => ':attribute 字段是必须的，除非 :other 是在 :values 中',
            'required_with' => ':attribute 字段是必须的当 :values 是存在的',
            'required_with_all' => ':attribute 字段是必须的当 :values 是存在的',
            'required_without' => ':attribute 字段是必须的当 :values 是不存在的',
            'required_without_all' => ':attribute 字段是必须的当 没有一个 :values 是存在的',
            'same' => ':attribute 和 :other 必须匹配',
            'size' => [
                'numeric' => ':attribute 必须是 :size 位',
                'file' => ':attribute 必须是 :size KB',
                'string' => ':attribute 必须是 :size 个字符',
                'array' => ':attribute 必须包括 :size 项',
            ],
            'string' => ':attribute 必须是一个字符串',
            'timezone' => ':attribute 必须是个有效的时区.',
            'unique' => ':attribute 已存在',
            'url' => ':attribute 无效的格式',
            'mobile' => ':attribute 手机号码无效',
        ];
    }

    public function __call($name, $arguments)
    {
        return $this->request->$name($arguments);
    }

    private function validateParams($params, array $rules = [], array $messages = [], array $customAttrs = []): bool
    {
        Log::debug('receive request.', $params);
        $validator = Validator::make($params, $rules, $messages, $customAttrs);
        if ($validator->fails()) {
            $backtrace = debug_backtrace();
            array_shift($backtrace);
            Log::debug('receive request params: ' . json_encode($params, JSON_UNESCAPED_UNICODE) . ' valid rules : ' . json_encode($rules, JSON_UNESCAPED_UNICODE));
            Log::error($backtrace[0]['class'] . '::' . $backtrace[0]['function'] . ' validator request', array_merge($params, $validator->errors()->toArray()));
            $errorMegs = $this->errorMessageDistinct($validator->errors()->messages());
            throw new ValidationHttpException($errorMegs);
        }

        return true;
    }

    private function errorMessageDistinct(array $messages)
    {
        $newMessage = array_reduce($messages, 'array_merge', array());
        return array_unique($newMessage);
    }

}