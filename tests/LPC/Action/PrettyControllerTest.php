<?php

namespace LPC\Action;

use PHPUnit_Framework_TestCase;
use Blainesch\LaravelPrettyController\Http\MediaType;
use LPC\Mocks\MockController;

class PrettyControllerTest extends PHPUnit_Framework_TestCase {

	public function setUp()
	{
		$this->controller = new MockController;
		$this->controller->method = 'index';
		$this->controller->params = [];
		MediaType::add('html', [
			'conditions' => [
				'accept' => [
					'text/html',
					'*/*',
				],
			],
			'encode' => function($request, $response) {
				return $response;
			},
		]);
	}

	public function testCallsCorrectAction()
	{
		$response = $this->controller->response();
		$expected = ['name' => 'blainesch'];

		$this->assertEquals($expected, $response);
	}

	public function testRequest()
	{
		$response = $this->controller->request();
		$expected = [
			'type' => '',
			'accept' => null,
			'controller' => 'LPC\Mocks\MockController',
			'method' => 'index',
		];

		$this->assertEquals($expected, $response);
	}

}