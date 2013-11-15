<?php namespace Creolab\Image;

use Illuminate\Support\Facades\Facade;

class ImageFacade extends Facade {

	protected static function getFacadeAccessor()
	{
		return 'creolab.image';
	}

}
