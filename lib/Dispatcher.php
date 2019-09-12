<?php

namespace lib;

class Dispatcher {

	/** @var $url string, trim GET parameters */
	protected $uri;

	const MAX_SCRIPT_LENGTH = 32;
	const DEFAULT_ENTRANCE = "index.php";
	const DEFAULT_CONTROLLER = "Index";
	const DEFAULT_METHOD = "index";

	public function __construct($uri) {
		$i = 0;
		for (;isset($uri[$i]) && $uri[$i] !== "?"; $i++) {}
		$this->uri = substr($uri, 0, $i);
	}

	public function dispatch() {
		$a = array_filter(explode('/', $this->uri), function ($s) {
			return isset($s[0]);
		});
		$a = array_values($a);

		if (!isset($a[0])) {
			$a[0] = self::DEFAULT_ENTRANCE;
		}
		if (strncmp($a[0], self::DEFAULT_ENTRANCE, self::MAX_SCRIPT_LENGTH) === 0) {
			array_shift($a);
		}
		if (!isset($a[0])) {
			$a[0] = self::DEFAULT_CONTROLLER;
		}
		if (!isset($a[1])) {
			$a[1] = self::DEFAULT_METHOD;
		}
		$ctlName = "controller\\" . \ucfirst($a[0]) . "Controller";
		$methodName = $a[1];

		$arguments = array();
		if (count($a) > 2) {
			$arguments = array_slice($a, 2);
		}
		$request = new Request();
		$controller = new $ctlName($request, $arguments);
		// echo '<pre>'; var_dump($controller);  var_dump($methodName);
		$controller->$methodName($request);
	}

}