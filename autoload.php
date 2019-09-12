<?php

// 需要注册的php类路径 (相对于依赖库的目录, 这里库就在当前目录dirname(__FILE__))
$prefixList = ['lib', 'controller', 'service'];

foreach ($prefixList as $prefix) {
	spl_autoload_register(function ($clazz) use ($prefix) {
		$baseDir = APP_ROOT . DIRECTORY_SEPARATOR . str_replace('\\', '/', $prefix);

		$len = strlen($prefix);
		if (strncmp($prefix, $clazz, $len) !== 0) {
			return;
		}
		$relativeClass = substr($clazz, $len);
		$file = $baseDir . str_replace('\\', '/', $relativeClass . '.php');
		if (file_exists($file)) {
			require $file;
		}
	});
}