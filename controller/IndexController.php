<?php

namespace controller;

use lib\Logger;
use lib\Request;
use lib\Str;
use models\IpAccess;
use models\Snapshot;
use service\SmsProvider;

class IndexController extends Controller {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * http://myaliyun.club:8042/Index/index?m=18771099612
	 */
	public function index(Request $request) {
        $mobile = $request->get("m");
        $ip = $request->getClientIP();
        if (!Str::isMobile($mobile)) {
            // throw new \InvalidArgumentException();
            $err = sprintf("手机号[%s]格式错误\n", $mobile);
            $code = 22;
            $this->fail($err, $code);
            return $code;
        }
        // 记录ip访问
        $ipAccess = new IpAccess();
        $ipAccess->setIp($ip);
        $ipAccess->setMobile($mobile);
        $ipAccess->save();

        // $data = MapService::queryGeoByIp("124.64.17.8");
		$this->view->assign("ip", $ip);
		$this->view->render("index");
	}

    /**
     * 经纬度存到数据库 通过这网站查 纬度,经度 http://www.gpsspg.com/maps.htm
     * @param Request $request
     */
	public function reportLocation(Request $request) {
	    // var_dump(__METHOD__); die;
        $pos = new Snapshot();
        $pos->mobile = $request->get("mobile");
        $pos->longitude = $request->get('longitude');
        $pos->latitude = $request->get('latitude');
        $pos->created_at = date("Y-m-d H:i:s", time());
        $pos->save();
        Logger::write($pos);
    }

	/**
	 * http://172.16.0.224:8042/index.php/Index/queryBalance?userId=JA1234&password=0123456
	 */
	public function queryBalance(Request $request) {
		$userId = $request->get("userId", SMS_USER_ID);
		$password = $request->get("password", SMS_PASSWORD);
		if (!$userId || !$password) {
			$this->fail("用户名密码不能为空", 2);
			return;
		}
		$api = "http://211.100.34.185:8016/MWGate/wmgw.asmx";

		$sms = new SmsProvider($userId, $password, $api);
		$c = $sms->queryBalance();

		header("Content-Type: application/json");
		if ($c < 0) {
			$this->fail($sms->getMongateErrorMsg($c), $c);
		}
		$this->success("获取余额成功", $c);
	}
}