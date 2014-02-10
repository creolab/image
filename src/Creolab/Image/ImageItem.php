<?php namespace Creolab\Image;

use Creolab\Image\ImageFacade as Image;

class ImageItem {

	/**
	 * Image source path
	 * @var string
	 */
	protected $src;

	/**
	 * Init new image item
	 * @param string $src
	 */
	public function __construct($src)
	{
		$this->src = $src;
	}

	/**
	 * Return original image src
	 * @return string
	 */
	public function src()
	{
		return $this->src;
	}

	/**
	 * Return original image url
	 * @return string
	 */
	public function url()
	{
		return asset($this->src);
	}

	/**
	 * Create a thumb
	 * @param  int $width
	 * @param  int $height
	 * @return string
	 */
	public function thumb($width, $height = null)
	{
		return Image::thumb($this->src, $width, $height);
	}

	/**
	 * Resize an image
	 * @param  int $width
	 * @param  int  $height
	 * @param  boolean $crop
	 * @param  int  $quality
	 * @return string
	 */
	public function resize($width = 100, $height = null, $crop = false, $quality = null)
	{
		return Image::resize($this->src, $width, $height, $crop, $quality);
	}

	/**
	 * String representation
	 * @return string
	 */
	public function __toString()
	{
		return $this->src();
	}

}
