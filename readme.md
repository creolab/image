Image
=====

Resize, crop and cache images for Laravel 4.
Framework agnostic coming soon.

## Installation

Simply add the following to your **composer.json** file:

    "creolab/image": "dev-master"

And you can also add the service provider to **app/config/app.ph**:

    'Creolab\Image\ImageServiceProvider',

And register the facade in the same file under aliases:

    'Image' => 'Creolab\Image\ImageFacade',

## Usage

You can use the library directly in your views like this:

    <img src="{{ Image::resize('public/path/to/image.jpg', 640, 480) }}">

Also to generate square thumbs:

    <img src="{{ Image::thumb('public/path/to/image.jpg', 80) }}">

[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/creolab/image/trend.png)](https://bitdeli.com/free "Bitdeli Badge")
