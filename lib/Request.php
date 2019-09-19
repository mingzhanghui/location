<?php

namespace lib;

class Request {
	/** @var $get array assoc */
	protected $get;
	/** @var $post array assoc */
	protected $post;

	public function __construct() {
		$this->get = $_GET;
		$this->post = $_POST;
	}

	public function get($name, $default = "") {
		if (empty($this->get)) {
			$this->get = $_GET;
		}

		return $this->input($this->get, $name, $default);
	}

	public function post($name, $default = "") {
		if (empty($this->post)) {
			$this->post = $_POST;
		}
		return $this->input($this->post, $name, $default);
	}

	protected function input( /* array */$data, $name = "", $default = "") {
		if (isset($name[0])) {
			if (isset($data[$name])) {
				return $data[$name];
			}
			return $default;
		}
		return $data;
	}

    public function getClientIP() {
        $realip = "";
        if (isset($_SERVER)) {
            foreach(['HTTP_X_FORWARED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'] as $name) {
                if (isset($_SERVER[$name])) {
                    $realip = $_SERVER[$name];
                    break;
                }
            }
        } else {
            $realip = getenv('HTTP_X_FORWARDED_FOR') || getenv('HTTP_CLIENT_IP') || getenv('REMOTE_ADDR');
        }
        return $realip;
    }

}