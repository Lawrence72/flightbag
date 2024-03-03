<?php

declare(strict_types=1);

use Lawrence72\Sanitizer\Sanitizer;
use PHPUnit\Framework\TestCase;

final class StringTest extends TestCase {
	protected Sanitizer $sanitizer;

	public function setUp(): void {
		$this->sanitizer = new Sanitizer();
	}
	public function testPlainText() {
		$text = "hello world";
		$this->assertSame("hello world", $this->sanitizer->clean($text));
	}

	public function testSpecialCharacters() {
		$text = "hello world!@#$%^&*()_+";
		$this->assertSame("hello world!@#$%^&amp;*()_+", $this->sanitizer->clean($text));
	}

	public function testGreaterThanLessThan() {
		$text = "hello world< >";
		$this->assertSame("hello world&lt; &gt;", $this->sanitizer->clean($text));
	}

	public function testHTMLTags() {
		$text = "<b>hello world</b>";
		$this->assertSame("hello world", $this->sanitizer->clean($text));
	}

	public function testHTMLTagsAllowed() {
		$text = "<b>hello world</b>";
		$this->assertSame("<b>hello world</b>", $this->sanitizer->clean($text, ['b']));
	}

	public function testHTMLTagsAllowedWithSpecialCharacters() {
		$text = "<b>hello world!@#$%^&*()_+</b>";
		$this->assertSame("<b>hello world!@#$%^&amp;*()_+</b>", $this->sanitizer->clean($text, ['b']));
	}
}
