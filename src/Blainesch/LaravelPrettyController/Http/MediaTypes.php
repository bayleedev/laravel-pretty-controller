<?php

namespace Blainesch\LaravelPrettyController\Http;

MediaTypes::add('html', array(
	'conditions' => array(
		'accept' => array(
			'text/html',
			'*/*',
		),
	),
	'encode' => function($request, $response) {
		$class = strtolower(str_replace('Controller', '', $request['controller']));
		return \View::make("{$class}.{$request['method']}")->with($response);
	},
));

MediaTypes::add('json', array(
	'conditions' => array(
		'type' => 'json',
		'accept' => array(
			'application/json',
			'application/x-json',
		),
	),
	'encode' => function($request, $response) {
		return json_encode($response);
	},
));

class MediaTypes {

	public static $types = array();

	public static $accept = array();

	public static function add($type, $options = array())
	{
		$type = static::$types[$type] = new MediaType($options);
		static::$accept = array_merge(static::$accept, $type->accept());
		return $type;
	}

	public static function allAcceptTypes()
	{
		return static::$accept;
	}

	public static function all()
	{
		return static::$types;
	}

}