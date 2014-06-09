<?php

namespace Blainesch\LaravelPrettyController\Http;

use Negotiation\FormatNegotiator;

MediaType::add('html', [
	'conditions' => [
		'accept' => [
			'text/html',
			'*/*',
		],
	],
	'encode' => function($request, $response) {
		$class = strtolower(str_replace('Controller', '', $request['controller']));
		return \View::make("{$class}.{$request['method']}")->with($response);
	},
]);

MediaType::add('json', [
	'conditions' => [
		'type' => 'json',
		'accept' => [
			'application/json',
			'application/x-json',
		],
	],
	'encode' => function($request, $response) {
		return json_encode($response);
	},
]);

class MediaType {

	public $options = [];

	public static $types = [];

	public function __construct($options) {
		$this->options = array_replace_recursive([
			'conditions' => [
				'type' => false,
				'accept' => [],
			],
			'encode' => null,
		], $options);
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

	public static function add($type, $options = [])
	{
		$type = static::$types[$type] = new MediaType($options);
		return $type;
	}

	public static function all()
	{
		return static::$types;
	}

	public static function findByType($type)
	{
		foreach (static::all() as $key => $value) {
			if ($value->type() === $type) {
				return $value;
			}
		}
		return false;
	}

	public static function allAcceptTypes()
	{
		return array_reduce(static::all(), function($memo, $value) {
			return array_merge($memo, $value->accept());
		}, []);
	}

	public static function findByAccept($accept)
	{
		$negotiator = new FormatNegotiator();
		$best = $negotiator->getBest($accept, static::allAcceptTypes());
		foreach (static::all() as $name => $mediaType) {
			if (in_array($best->getValue(), $mediaType->accept())) {
				return $mediaType;
			}
		}
		return false;
	}

}