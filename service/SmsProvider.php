<?php

namespace service;

use lib\Logger;

class SmsProvider {

	/** @var string "JI0123" */
	private $userId;

	/** @var string "123456" */
	private $password;

	/** @var string http://211.100.34.185:8016/MWGate/wmgw.asmx */
	private $api;

	public function __construct($userId, $password, $api) {
		$this->userId = $userId;
		$this->password = $password;
		$this->api = $api;
	}

	private static function buildQuery($a) {
		$m = [];
		array_walk($a, function ($item, $key) use (&$m) {
			array_push($m, $key . '=' . urlencode($item));
		});
		return implode('&', $m);
	}

	private static function findIndexFollowDelim($s, $delim) {
		$n = strlen($delim);
		$j = 0;
		for ($i = 0;isset($s[$i]) && $j < $n; $i++) {
			if ($delim[$j] === $s[$i]) {
				$j += 1;
			} else {
				$j = 0;
			}
		}
		return $i;
	}

	private static function extractHttpBody($s, $delim = "\r\n\r\n") {
		return substr($s, self::findIndexFollowDelim($s, $delim));
	}

	private static function extractHttpHeader($s, $delim = "\r\n\r\n") {
		return substr($s, 0, self::findIndexFollowDelim($s, $delim) - strlen($delim));
	}

	public function send($phone, $content) {
		$ch = curl_init();

		$headers = array(
			"Content-Type: application/x-www-form-urlencoded",
		);
		$a = array(
			"userId" => $this->userId,
			"password" => $this->password,
			"pszMobis" => $phone,
			"pszMsg" => $content,
			"iMobiCount" => 1,
			"pszSubPort" => '*',
			"MsgId" => 111,
		);
		$postData = self::buildQuery($a);
		array_push($headers, sprintf("Content-Length: %d", strlen($postData)));

		curl_setopt_array($ch, [
			CURLOPT_URL => sprintf("%s/MongateSendSubmit", $this->api),
			CURLOPT_HEADER => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_POST => 1,
			CURLOPT_BINARYTRANSFER => 1,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_POSTFIELDS => $postData,
		]);
		$data = curl_exec($ch);
		Logger::write($data);
		curl_close($ch);
		$xml = self::extractHttpBody($data);
		sscanf($xml,
			"<?xml version=\"1.0\" encoding=\"utf-8\"?><string xmlns=\"http://tempuri.org/\">%d</string>",
			$retCode);
		return $retCode;
	}

	public function queryBalance() {
		$ch = curl_init();

		$headers = array(
			"Content-Type: application/x-www-form-urlencoded",
		);
		$a = array(
			"userId" => $this->userId,
			"password" => $this->password,
		);
		$postData = self::buildQuery($a);
		array_push($headers, sprintf("Content-Length: %d", strlen($postData)));

		curl_setopt_array($ch, [
			CURLOPT_URL => sprintf("%s/MongateQueryBalance", $this->api),
			CURLOPT_HEADER => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_POST => 1,
			CURLOPT_BINARYTRANSFER => 1,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_POSTFIELDS => $postData,
		]);
		$data = curl_exec($ch);
		Logger::write($data);
		curl_close($ch);
		$xml = self::extractHttpBody($data);
		sscanf($xml,
			"<?xml version=\"1.0\" encoding=\"utf-8\"?><int xmlns=\"http://tempuri.org/\">%d</int>",
			$balance);
		return $balance;
	}

	/**
	 * 取得短信错误码
	 * @param $code integer
	 * @return string
	 */
	public static function getMongateErrorMsg( /* long */$code) /* string */ {
		if ($code < -65535) {
			return "SUCCESS";
		}
		switch ($code) {
		case -1:$s = "参数为空。信息、电话号码等有空指针，登陆失败";
			break;
		case -12:$s = "有异常电话号码";
			break;
		case -13:$s = "请求数据格式错误";
			break;
		case -14:$s = "实际号码个数超过1000";
			break;
		case -999:$s = "服务器内部错误";
			break;
		case -10001:$s = "用户登陆不成功(帐号不存在/停用/密码错误)";
			break;
		case -10003:$s = "用户余额不足";
			break;
		case -10011:$s = "信息内容超长";
			break;
		case -10029:$s = "此用户没有权限从此通道发送信息(用户没有绑定该性质的通道，比如：用户发了小灵通的号码)";
			break;
		case -10030:$s = "不能发送移动号码";
			break;
		case -10031:$s = "手机号码(段)非法";
			break;
		case -10057:$s = "IP受限";
			break;
		case -10056:$s = "连接数超限";
			break;
		default:$s = "未知错误";
		}

		return $s;
	}

}