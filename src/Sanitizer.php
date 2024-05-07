<?php

namespace Lawrence72\Flightbag;

use flight\util\Collection;

class Sanitizer {

	/**
	 * 
	 * @param mixed $data 
	 * @param array $allowed_tags 
	 * @return mixed 
	 * allowed tags should be in the format of ['b', 'i', 'u', 'a']
	 */
	public function clean($data, array $allowed_tags = [], $encoding = 'UTF-8') {

		if (is_string($data)) {
			return $this->cleanStrings($data, $allowed_tags, $encoding);
		} elseif ($data instanceof Collection) {
			return $this->cleanCollection($data, $allowed_tags, $encoding);
		} elseif (is_array($data)) {
			return $this->cleanArray($data, $allowed_tags, $encoding);
		} elseif (is_object($data)) {
			return $this->cleanObject($data, $allowed_tags, $encoding);
		} else {
			return $data;
		}
	}

	protected function cleanStrings($data, array $allowed_tags = [], $encoding = 'UTF-8') {
		$data = trim($data);

		$cleaned_data = $this->removeNonPrintables($data);

		$cleaned_data = empty($allowed_tags) ?
			htmlspecialchars(strip_tags($cleaned_data)) :
			$this->cleanStringWithTags($cleaned_data, $allowed_tags);

		return !empty($encoding) ?
			$this->encodeString($cleaned_data, $encoding) :
			$cleaned_data;
	}

	protected function cleanStringWithTags($data, array $allowed_tags = [], $encoding = 'UTF-8') {
		$strip_tags = strip_tags($data, '<' . implode('><', $allowed_tags) . '>');

		$cleaned_data = preg_replace_callback(
			'/(<(?:'
				. implode('|', $allowed_tags)
				. ')[^>]*>)(.*?)(<\/(?:'
				. implode('|', $allowed_tags)
				. ')>)/is',
			function ($matches) {
				return  $matches[1] . htmlspecialchars($matches[2]) . $matches[3];
			},
			$strip_tags
		);

		return $cleaned_data;
	}

	protected function cleanCollection(Collection $data, array $allowed_tags = [], $encoding = 'UTF-8') {
		$cleaned_data = [];
		foreach ($data as $key => $value) {
			$cleaned_data[$key] = $this->clean($value, $allowed_tags, $encoding);
		}
		return new Collection($cleaned_data);
	}

	protected function cleanArray(array $data, array $allowed_tags = [], $encoding = 'UTF-8') {
		$cleaned_data = [];
		foreach ($data as $key => $value) {
			$cleaned_data[$key] = $this->clean($value, $allowed_tags, $encoding);
		}
		return $cleaned_data;
	}

	protected function cleanObject($data, array $allowed_tags = [], $encoding = 'UTF-8') {
		$cleaned_data = new \stdClass();
		foreach ($data as $key => $value) {
			$cleaned_data->$key = $this->clean($value, $allowed_tags, $encoding);
		}
		return $cleaned_data;
	}

	protected function encodeString($data, $encoding = 'UTF-8') {
		return \htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, $encoding);
	}

	protected function removeNonPrintables($data) {
		return \preg_replace(
			'/[\x00-\x08\x0B\x0C\x0E-\x1F]/',
			'',
			$data
		);
	}
}
