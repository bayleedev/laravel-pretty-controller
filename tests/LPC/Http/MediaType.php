<?php

namespace LPC\Http;

use PHPUnit_Framework_TestCase;
use Blainesch\LaravelPrettyController\Http\MediaType;
use Mockery as m;

class MediaTypeTest extends PHPUnit_Framework_TestCase {

	public function testAccept()
	{
		$mediaType = new MediaType(['conditions' => ['accept' => ['test/xml']]]);
		$this->assertEquals(['test/xml'], $mediaType->accept());
	}

	public function testType()
	{
		$mediaType = new MediaType(['conditions' => ['type' => 'json']]);
		$this->assertEquals('json', $mediaType->type());
	}

	public function testEncode()
	{
		$mediaType = new MediaType(['encode' => function() {
			return 3;
		}]);
		$this->assertEquals(3, $mediaType->encode([], []));
	}

	public function testFindByType()
	{
		$html = MediaType::add('html', [
			'conditions' => ['type' => 'html'],
		]);
		$json = MediaType::add('json', [
			'conditions' => ['type' => 'json'],
		]);

		$this->assertEquals($json, MediaType::findByType('json'));
		$this->assertFalse(MediaType::findByType('xml'));
	}

	public function testAllAcceptTypes()
	{
		$html = MediaType::add('html', [
			'conditions' => ['accept' => ['one', 'two']],
		]);
		$json = MediaType::add('json', [
			'conditions' => ['accept' => ['three']],
		]);

		$this->assertEquals(['one', 'two', 'three'], MediaType::allAcceptTypes());
	}

	public function testFindByAccept()
	{
		$html = MediaType::add('html', [
			'conditions' => ['accept' => ['application/one', 'application/two']],
		]);
		$json = MediaType::add('json', [
			'conditions' => ['accept' => ['application/three', '*/*']],
		]);

		$this->assertEquals('html', MediaType::findByAccept('*/*')->options['name']);
		$this->assertEquals('html', MediaType::findByAccept('application/one;q=0.2,application/three;q=0.5,application/two;q=0.9')->options['name']);
		$this->assertEquals(false, MediaType::findByAccept('application/five;q=0.2'));
	}

}