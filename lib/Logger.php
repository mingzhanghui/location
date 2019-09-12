<?php

namespace lib;

class Logger {
	/** @var resource */
	protected static $handler = null;

	private static function init() {
		$logDir = dirname(dirname(__FILE__)) . '/storage/logs';
		if (!is_dir($logDir)) {
			mkdir($logDir, 0777, true);
		}
		$logPath = $logDir . '/out.log';
		if (!is_resource(self::$handler)) {
			self::$handler = fopen($logPath, 'a');
		}
		$stat = fstat(self::$handler);
		if ($stat['size'] > 1048576) {
			rename($logPath, $logDir . '/out.archive.log');
			ftruncate(self::$handler, 0);
			fclose(self::$handler);
			self::$handler = fopen($logPath, 'a');
		}
	}

	public static function write($msg) {
		self::init();
		fwrite(self::$handler, $msg, strlen($msg));
	}

	public static function close() {
		if (is_resource(self::$handler)) {
			fclose(self::$handler);
			self::$handler = null;
		}
	}
}