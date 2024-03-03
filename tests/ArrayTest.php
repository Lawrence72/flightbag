<?php

declare(strict_types=1);

use Lawrence72\Sanitizer\Sanitizer;
use PHPUnit\Framework\TestCase;

final class ArrayTest extends TestCase {
	protected Sanitizer $sanitizer;

	public function setUp(): void {
		$this->sanitizer = new Sanitizer();
	}
	public function testArrayPlainText() {
		$array = ["hello world", "hello world"];
		$this->assertSame(['hello world', 'hello world'], $this->sanitizer->clean($array));
	}

	public function testArraySpecialCharacters() {
		$array = ["hello world!@#$%^&*()_+", "hello world!@#$%^&*()_+"];
		$this->assertSame(['hello world!@#$%^&amp;*()_+', 'hello world!@#$%^&amp;*()_+'], $this->sanitizer->clean($array));
	}

	public function testArrayGreaterThanLessThan() {
		$array = ["hello world< >", "hello world< >"];
		$this->assertSame(['hello world&lt; &gt;', 'hello world&lt; &gt;'], $this->sanitizer->clean($array));
	}

	public function testArrayHTMLTags() {
		$array = ["<b>hello world</b>", "<b>hello world</b>"];
		$this->assertSame(['hello world', 'hello world'], $this->sanitizer->clean($array));
	}

	public function testArrayHTMLTagsAllowed() {
		$array = ["<b>hello world</b>", "<b>hello world</b>"];
		$this->assertSame(['<b>hello world</b>', '<b>hello world</b>'], $this->sanitizer->clean($array, ['b']));
	}

	public function testArrayHTMLTagsAllowedWithSpecialCharacters() {
		$array = ["<b>hello world!@#$%^&*()_+</b>", "<b>hello world!@#$%^&*()_+</b>"];
		$this->assertSame(['<b>hello world!@#$%^&amp;*()_+</b>', '<b>hello world!@#$%^&amp;*()_+</b>'], $this->sanitizer->clean($array, ['b']));
	}
}
