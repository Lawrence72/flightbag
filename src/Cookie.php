<?php

namespace Lawrence72\Flightbag;

class Cookie {

	public function set(string $key, $value, $time = 3600, $path = '/', $domain = '', $secure = 0, $http_only = 1): bool {
		setcookie($key, $value, $time, $path, $domain, $secure, $http_only);
		return true;
	}

	public function get(string $key) {
		return $_COOKIE[$key] ?? null;
	}

	public function has(string $key) {
		return isset($_COOKIE[$key]);
	}

	public function remove(string $key) {
		unset($_COOKIE[$key]);
	}
}
