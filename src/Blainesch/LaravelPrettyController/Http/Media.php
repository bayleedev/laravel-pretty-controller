<?php

namespace Blainesch\LaravelPrettyController\Http;

use Blainesch\LaravelPrettyController\Action\BadAcceptType;
use App;

Media::type('json', array(
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
Media::type('html', array(
	'conditions' => array(
		'accept' => array(
			'text/html',
			'*/*',
		),
	),
	'encode' => function($request, $response) {
		$class = strtolower(str_replace('Controller', '', $request['controller']));
		return View::make("{$class}.{$request['method']}")->with($response);
	},
));

class Media {

	public static $types = array();

	public static function type($type, $options = array())
	{
		return static::$types[$type] = new MediaType($options);
	}

	public static function types()
	{
		return static::$types;
	}

	public static function render($request, $response)
	{
		foreach (static::types() as $name => $mediaType) {
			if ($mediaType->respondsTo($request)) {
				return $mediaType->encode($request, $response);
			}
		}
		App::abort(406, 'Unrecognized accept type.');
	}

}