<?php

namespace Blainesch\LaravelPrettyController\Action;

use UnexpectedValueException;

class BadAcceptType extends UnexpectedValueException {

	protected $code = 406;

}