<?php
/**
 * Created by PhpStorm.
 * User: mingzhanghui
 * Date: 9/12/2019
 * Time: 16:32
 */

namespace controller;

use lib\Logger;
use lib\Request;
use service\Curl;
use service\SmsProvider;

class AdminController extends Controller
{
    /**
     * 需要发送给别人的长链接 => 短链接
     * @param Request $request
     * @return int
     */
    public function short(Request $request) {
        $mobile = $request->get('m');

        if (!self::isMobile($mobile)) {
            // throw new \InvalidArgumentException();
            $err = sprintf("手机号[%s]格式错误\n", $mobile);
            $code = 22;
            $this->fail($err, $code);
            return $code;
        }

        // URL 配置查看项目根目录的config.php
        // http://47.93.27.106:8042/?m=18771099612
        $url = URL.'?m='.$mobile;
        Logger::write("Generating short URL for: [%s]\n", $url);

        $this->success("success", Curl::shortURL($url));
        return 0;
    }

    public function send(Request $request) {
        $mobile = $request->get("m");
        $content = $request->get("c");

        $sms = new SmsProvider(SMS_USER_ID, SMS_PASSWORD, SMS_API);
        return $sms->send($mobile, $content);
    }

    // \service\SmsProvider::send

    private static function isMobile($mobile) {
        $matches = array();
        preg_match('/^1[356789][0-9]{9}$/', $mobile, $matches);
        return !empty($matches);
    }
}