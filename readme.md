## What?

This library provides helpful content negotiations. For instance if you ask for json back from a typical laravel website you'd still get html back. I carefully look at the `Accept` header and `type` (`.json` for instance) to best determine what media type to render.

What does this mean? It means your controller actions are cleaner, all you do is return values!
~~~ php
class UserController extends BaseController {
	public function show()
	{
		return View::make('user.index')->with([
			'name' => 'BlaineSch',
		]);
	}
}
~~~

Now, let's respond to multiple content types and prettify our controller!
~~~ php
class UserController extends BaseController {
	public function showAction()
	{
		return ['name' => 'blainesch'];
	}
}
~~~

## Installation

### Update `Controller` and add `CoreController` values in your `app/config/app.php` file.
~~~ php
'aliases' => [
	// ...
	'Controller'      => 'Blainesch\LaravelPrettyController\Action\PrettyController',
	'CoreController'  => 'Illuminate\Routing\Controller',
	// ...
]
~~~

### Register your media types

Create a `bootstrap/media.php`
~~~ php
<?php

use Blainesch\LaravelPrettyController\Http\MediaType;

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
~~~

Include this file in `bootstrap/autoload.php` below composer autoloader
~~~ php
require __DIR__.'/media.php';
~~~
