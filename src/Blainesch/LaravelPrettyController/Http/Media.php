<?php

namespace Blainesch\LaravelPrettyController\Http;

use Blainesch\LaravelPrettyController\Action\BadAcceptType;
use Negotiation\FormatNegotiator;
use App;

class Media {

	public $request = null;

	public $response = null;

	public function __construct($request, $response)
	{
		$this->request = $request;
		$this->response = $response;
	}

	public function findByType()
	{
		foreach (MediaTypes::all() as $name => $mediaType) {
			if ($mediaType->type() === $this->request['type']) {
				return $mediaType;
			}
		}
		return false;
	}

	public function findBestAccept()
	{
		$allAcceptTypes = MediaTypes::allAcceptTypes();
		$negotiator = new FormatNegotiator();
		return $negotiator->getBest($this->request['accept'], $allAcceptTypes);
	}

	public function findByAccept()
	{
		$best = $this->findBestAccept();
		foreach (MediaTypes::all() as $name => $mediaType) {
			if (in_array($best->getValue(), $mediaType->accept())) {
				return $mediaType;
			}
		}
		return false;
	}

	public static function render($request, $response)
	{
		$media = new Media($request, $response);
		$mediaType = $media->findByType() ?: $media->findByAccept();
		if (!$mediaType) {
			return App::abort(406, 'Unrecognized accept type.');
		}
		return $mediaType->encode($request, $response);
	}

}