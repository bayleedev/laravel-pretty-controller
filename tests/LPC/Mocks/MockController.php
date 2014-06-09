<?php

namespace LPC\Mocks;

use Blainesch\LaravelPrettyController\Action\PrettyController;

class MockController extends PrettyController {

	public function indexAction()
	{
		return ['name' => 'blainesch'];
	}

}