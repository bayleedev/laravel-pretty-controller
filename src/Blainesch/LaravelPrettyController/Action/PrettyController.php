<?php

namespace Blainesch\LaravelPrettyController\Action;

use CoreController;
use View;
use Input;
use Request;
use Blainesch\LaravelPrettyController\Http\Media;

class PrettyController extends CoreController {

	public function __call($method, $params)
	{
		$this->method = $method;
		$this->params = $params;
		return Media::render($this->request(), $this->response());
	}

	protected function request()
	{
		return array(
			'type' => end($this->params),
			'accept' => Request::header('Accept'),
			'controller' => get_called_class(),
			'method' => $this->method,
		);
	}

	protected function response()
	{
		$method = $this->method . 'Action';
		return call_user_func_array(array($this, $method), $this->params);
	}

}