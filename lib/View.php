<?php

namespace lib;

class View {
	protected $data = [];

	/** @var string */
	protected static $resSrc = "";

	function __construct() {
		// echo "this is the view<br />";
        if (!isset(self::$resSrc[0])) {
            self::$resSrc = Str::extractBeforeDelim(BASE_URL, "/index.php");
        }
	}

	public function getResSrc() {
	    return self::$resSrc;
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

    /**
     * @param $name string template relative path
     * @param bool $include incluide header & footer ?
     */
	public function render($name, $include = true) {
		if ($include == false) {
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