<?php

namespace Blainesch\LaravelPrettyController\Http;


class MediaType {

	public $options = array();

	public function __construct($options) {
		$this->options = array_replace_recursive(array(
			'conditions' => array(
				'type' => false,
				'accept' => array(),
			),
			'encode' => null,
		), $options);
	}

	public function accept()
	{
		return $this->options['conditions']['accept'];
	}

	public function type()
	{
		return $this->options['conditions']['type'];
	}

	public function encode($request, $response)
	{
		return call_user_func($this->options['encode'], $request, $response);
	}

}