<?php

namespace lib;

class View {
	protected $data = [];

	function __construct() {
		// echo "this is the view<br />";
	}

	public function assign($name, $value) {
		$this->data[$name] = $value;
	}

	public function get($name) {
		if (!isset($this->data[$name])) {
			return "";
		}
		return $this->data[$name];
	}

	public function render($name, $noInclude = false) {
		if ($noInclude == true) {
			require_once APP_ROOT . '/views/' . $name . '.php';
		} else {
			require APP_ROOT . '/views/header.php';
			$phpPath = APP_ROOT . '/views/' . $name . '.php';
			if (file_exists($phpPath)) {
				include $phpPath;
			} else {
				include APP_ROOT . '/views/' . $name . '.html';
			}
			require APP_ROOT . '/views/footer.php';
		}
	}
}