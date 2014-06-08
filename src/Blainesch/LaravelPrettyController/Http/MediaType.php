<?php

namespace Blainesch\LaravelPrettyController\Http;

use Negotiation\FormatNegotiator;

class MediaType {

	public $options = array();

	public static $negotiator = null;

	public function __construct($options) {
		$this->options = array_replace_recursive(array(
			'conditions' => array(
				'type' => null,
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

	public function respondsTo($request)
	{
		return $this->respondsToType($request) || $this->respondsToAccept($request);
	}

	protected function respondsToType($request)
	{
		return $this->type() === $request['type'];
	}

	protected function respondsToAccept($request)
	{
		print_r(array(
			'request' => $request,
			'accept' => $this->accept(),
		));
		$wants = $this->negotiator()->getBest($request['accept'], $this->accept());
		return in_array($wants->getValue(), $this->accept());
	}

	protected function negotiator()
	{
		return static::$negotiator = static::$negotiator ?: new FormatNegotiator();
	}

}