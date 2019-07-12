<?php
/**
 * Description:
 * Author: WangSx
 * DateTime: 2019-07-12 09:50
 */

namespace App\Library\DingDing;


use App\Library\FreeApi\freeApi;

class DingHook
{
    const REMOTE_SERVER = 'https://oapi.dingtalk.com/robot/send?access_token=111bbb8fcb24aa30d1a22294744545b823d9d1432cd29730117008c78888d0ee';

    /**
     * Description: 发送文本消息
     * Author: WangSx
     * DateTime: 2019-07-12 14:54
     * @param string $message
     * @param array|null $mobiles
     * @param bool|null $waring
     * @return bool|string
     */
    public static function sendMsg(string $message, ?array $mobiles = [], ?bool $waring = false)
    {
        empty($mobiles) && $mobiles = [];
        empty($waring) && $waring = false;

        $data = [
            'msgtype' => 'text',
            'text' => [
                'content' => $message,
            ],
            'at' => [
                'atMobiles' => $mobiles,
                'isAtAll' => $waring
            ],
        ];

        return self::send($data);
    }

    /**
     * Description: 发送图文消息
     * Author: WangSx
     * DateTime: 2019-07-12 10:21
     * @param string $text 消息内容。如果太长只会部分展示
     * @param string $title 消息标题
     * @param string $picUrl 图片URL
     * @param string $messageUrl 点击消息跳转的URL
     * @return bool|string
     */
    public static function sendLink(string $text, string $title, string $picUrl, string $messageUrl)
    {
        $data = [
            'msgtype' => 'link',
            'link' => [
                'text' => $text,
                'title' => $title,
                'picUrl' => $picUrl,
                'messageUrl' => $messageUrl
            ],
        ];
        return self::send($data);
    }

    /**
     * Description: 发送markDown
     * Author: WangSx
     * DateTime: 2019-07-12 10:24
     * @param string $title 首屏会话透出的展示内容
     * @param string $text markdown格式的消息
     * @return bool|string
     */
    public static function sendMarkdown(string $title, string $text)
    {
        $data = [
            'msgtype' => 'markdown',
            'markdown' => [
                'title' => $title,
                'text' => $text,
            ],
        ];
        return self::send($data);
    }

    /**
     * Description: send
     * Author: WangSx
     * DateTime: 2019-07-12 10:19
     * @param array $data
     * @return bool|string
     */
    protected static function send(array $data)
    {
        $data_string = json_encode($data, JSON_UNESCAPED_UNICODE);
        $result = self::request_by_curl($data_string);
        return $result;
    }

    /**
     * Description: curl
     * Author: WangSx
     * DateTime: 2019-07-12 10:19
     * @param $post_string
     * @return bool|string
     */
    protected static function request_by_curl($post_string)
    {
        if (empty($remote_server)) {
            $remote_server = self::REMOTE_SERVER;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=utf-8'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 线下环境不用开启curl证书验证, 未调通情况可尝试添加该代码
        // curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }


}

