<?php

namespace lib;

class Request {
	/** @var $get array assoc */
	protected $get;
	/** @var $post array assoc */
	protected $post;

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
}