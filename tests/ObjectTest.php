<?php

declare(strict_types=1);

use Lawrence72\Sanitizer\Sanitizer;
use PHPUnit\Framework\TestCase;

final class ObjectTest extends TestCase {
	protected Sanitizer $sanitizer;

	public function setUp(): void {
		$this->sanitizer = new Sanitizer();
	}
	public function testObjectPlainText() {
		$object = new stdClass();
		$object->key1 = "hello world";
		$object->key2 = "hello world";

		$results = $this->sanitizer->clean($object);

		$this->assertSame($results->key1, 'hello world');
	}

	public function testObjectSpecialCharacters() {
		$object = new stdClass();
		$object->key1 = "hello world!@#$%^&*()_+";
		$object->key2 = "hello world!@#$%^&*()_+";

		$results = $this->sanitizer->clean($object);

		$this->assertSame($results->key1, 'hello world!@#$%^&amp;*()_+');
	}

	public function testObjectGreaterThanLessThan() {
		$object = new stdClass();
		$object->key1 = "hello world< >";
		$object->key2 = "hello world< >";

		$results = $this->sanitizer->clean($object);

		$this->assertSame($results->key1, 'hello world&lt; &gt;');
	}

	public function testObjectHTMLTags() {
		$object = new stdClass();
		$object->key1 = "<b>hello world</b>";
		$object->key2 = "<b>hello world</b>";

		$results = $this->sanitizer->clean($object);

		$this->assertSame($results->key1, 'hello world');
	}

	public function testObjectHTMLTagsAllowed() {
		$object = new stdClass();
		$object->key1 = "<b>hello world</b>";
		$object->key2 = "<b>hello world</b>";

		$results = $this->sanitizer->clean($object, ['b']);

		$this->assertSame($results->key1, '<b>hello world</b>');
	}

	public function testObjectHTMLTagsAllowedWithSpecialCharacters() {
		$object = new stdClass();
		$object->key1 = "<b>hello world!@#$%^&*()_+</b>";
		$object->key2 = "<b>hello world!@#$%^&*()_+</b>";

		$results = $this->sanitizer->clean($object, ['b']);

		$this->assertSame($results->key1, '<b>hello world!@#$%^&amp;*()_+</b>');
	}
}
