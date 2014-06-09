<?php

namespace Blainesch\LaravelPrettyController\Http;

use App;

class Media {

	public static function render($request, $response)
	{
		$mediaByType = MediaType::findByType($request['type']);
		$mediaByAccept = MediaType::findByAccept($request['accept']);
		$mediaType = $mediaByType ?: $mediaByAccept;
		if (!$mediaType) {
			return App::abort(406, 'Unrecognized accept type.');
		}
		return $mediaType->encode($request, $response);
	}

}