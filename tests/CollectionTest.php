<?php

declare(strict_types=1);

use flight\util\Collection;
use Lawrence72\Sanitizer\Sanitizer;
use PHPUnit\Framework\TestCase;

final class CollectionTest extends TestCase {
	protected Sanitizer $sanitizer;

	public function setUp(): void {
		$this->sanitizer = new Sanitizer();
	}
	public function testCollectionPlainText() {
		$collection = new Collection(["hello world", "hello world"]);
		$result = $this->sanitizer->clean($collection);
		$this->assertSame($result[0], 'hello world');
		$this->assertInstanceOf(Collection::class, $result);
	}

	public function testCollectionSpecialCharacters() {
		$collection = new Collection(["hello world!@#$%^&*()_+", "hello world!@#$%^&*()_+"]);
		$result = $this->sanitizer->clean($collection);
		$this->assertSame($result[0], 'hello world!@#$%^&amp;*()_+');
		$this->assertInstanceOf(Collection::class, $result);
	}

	public function testCollectionGreaterThanLessThan() {
		$collection = new Collection(["hello world< >", "hello world< >"]);
		$result = $this->sanitizer->clean($collection);
		$this->assertSame($result[0], 'hello world&lt; &gt;');
		$this->assertInstanceOf(Collection::class, $result);
	}

	public function testCollectionHTMLTags() {
		$collection = new Collection(["<b>hello world</b>", "<b>hello world</b>"]);
		$result = $this->sanitizer->clean($collection);
		$this->assertSame($result[0], 'hello world');
		$this->assertInstanceOf(Collection::class, $result);
	}

	public function testCollectionHTMLTagsAllowed() {
		$collection = new Collection(["<b>hello world</b>", "<b>hello world</b>"]);
		$result = $this->sanitizer->clean($collection, ['b']);
		$this->assertSame($result[0], '<b>hello world</b>');
		$this->assertInstanceOf(Collection::class, $result);
	}

	public function testCollectionHTMLTagsAllowedWithSpecialCharacters() {
		$collection = new Collection(["<b>hello world!@#$%^&*()_+</b>", "<b>hello world!@#$%^&*()_+</b>"]);
		$result = $this->sanitizer->clean($collection, ['b']);
		$this->assertSame($result[0], '<b>hello world!@#$%^&amp;*()_+</b>');
		$this->assertInstanceOf(Collection::class, $result);
	}
}
