<?php

namespace controller;

use controller\Controller;
use lib\Request;
use service\SmsProvider;

class IndexController extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$this->view->assign("username", "mingzhanghui");
		$this->view->render("index");
	}

	/**
	 * http://172.16.0.224:8042/index.php/Index/queryBalance?userId=JA1030&password=785932
	 */
	public function queryBalance(Request $request) {
		$userId = $request->get("userId");
		$password = $request->get("password");
		$api = "http://211.100.34.185:8016/MWGate/wmgw.asmx";

		$sms = new SmsProvider($userId, $password, $api);
		$c = $sms->queryBalance();

		header("Content-Type: application/json");
		$this->success("获取余额成功", $c);
	}
}