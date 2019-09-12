<?php

namespace controller;

use lib\View;

class Controller {

	/**
	 * [$view description]
	 * @var $view lib\View
	 */
	protected $view;

	public function __construct() {
		$this->view = new View();
	}

	/**
	 * @param       $msg string
	 * @param       $data mixed
	 */
	public function success($msg, $data) {
		echo json_encode([
			'code' => 200,
			'data' => $data,
			'msg' => $msg,
		], JSON_UNESCAPED_UNICODE);
		exit(0);
	}

	/**
	 * @param       $msg string
	 * @param       $code int
	 */
	public static function fail($msg, $code) {
		echo json_encode([
			'code' => $code,
			'data' => null,
			'msg' => $msg,
		], JSON_UNESCAPED_UNICODE);
		exit($code);
	}

	/**
	 * @param    $data mixed
	 */
	public static function result($data) {
		echo json_encode([
			'code' => 200,
			'data' => $data,
			'msg' => 'success',
		], JSON_UNESCAPED_UNICODE);
		exit(0);
	}

	/**
	 * @param $funcName string
	 * @param $args array
	 */
	public function __call($funcName, $args) {
		printf("Method: [%s\\%s] does not exist!\n\n", get_called_class(), $funcName);
		var_dump($args);
		exit(2);
	}

}