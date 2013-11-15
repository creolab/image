<?php namespace Creolab\Image;

use Config, File, Log;

class Image {

	/**
	 * Type of library to use, defaults to GD
	 * @var string
	 */
	protected $library = 'gd';

	/**
	 * Instance of Imagine package
	 * @var Imagine\Gd\Imagine
	 */
	protected $imagine;

	/**
	 * Always force overwriting of files
	 * @var boolean
	 */
	public $overwrite = false;

	/**
	 * Quality of compression
	 * @var integer
	 */
	public $quality = 85;

	/**
	 * Initialize image service
	 * @return void
	 */
	public function __construct($library = null)
	{
		if ( ! $this->imagine)
		{
			$this->library = $library ? $library : null;

			// Use image magick if available
			if ( ! $this->library and class_exists('Imagick')) $this->library = 'imagick';
			else                                               $this->library = 'gd';

			// Now create instance
			if     ($this->library == 'imagick') $this->imagine = new \Imagine\Imagick\Imagine();
			elseif ($this->library == 'gmagick') $this->imagine = new \Imagine\Gmagick\Imagine();
			elseif ($this->library == 'gd')      $this->imagine = new \Imagine\Gd\Imagine();
			else                                 $this->imagine = new \Imagine\Gd\Imagine();
		}
	}

	/**
	 * Resize an image
	 * @param  string  $url
	 * @param  integer $width
	 * @param  integer $height
	 * @param  boolean $crop
	 * @return string
	 */
	public function resize($url, $width = 100, $height = null, $crop = false, $quality = null)
	{
		if ($url)
		{
			// URL info
			$info = pathinfo($url);

			// The size
			if ( ! $height) $height = $width;

			// Quality
			$quality = ($quality) ? $quality : $this->quality;

			// Directories and file names
			$fileName       = $info['basename'];
			$sourceDirPath  = public_path() . $info['dirname'];
			$sourceFilePath = $sourceDirPath . '/' . $fileName;
			$targetDirName  = $width . 'x' . $height . ($crop ? '_crop' : '');
			$targetDirPath  = $sourceDirPath . '/' . $targetDirName . '/';
			$targetFilePath = $targetDirPath . $fileName;
			$targetUrl      = url($info['dirname'] . '/' . $targetDirName . '/' . $fileName);

			// Create directory if missing
			try
			{
				try {
					if ( ! File::isDirectory($targetDirPath) and $targetDirPath) @File::makeDirectory($targetDirPath);
				} catch(\Exception $e) {
					die();
				}

				// Set the size
				$size = new \Imagine\Image\Box($width, $height);

				// Now the mode
				$mode = $crop ? \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND : \Imagine\Image\ImageInterface::THUMBNAIL_INSET;

				if ($this->overwrite or ! File::exists($targetFilePath) or (File::lastModified($targetFilePath) < File::lastModified($sourceFilePath)))
				{
					$this->imagine->open($sourceFilePath)
					              ->thumbnail($size, $mode)
					              ->save($targetFilePath, array('quality' => $quality));
				}
			}
			catch (\Exception $e)
			{
				Log::error('[IMAGE SERVICE] Failed to resize image "' . $url . '" [' . $e->getMessage() . ']');
			}

			return $targetUrl;
		}
	}

	/**
	 * Helper for creating thumbs
	 * @param  string  $url
	 * @param  integer $width
	 * @param  integer $height
	 * @return string
	 */
	public function thumb($url, $width, $height = null)
	{
		return $this->resize($url, $width, $height, true);
	}

	/**
	 * Creates image dimmensions based on a configuration
	 * @param  string $url
	 * @param  array  $dimmensions
	 * @return void
	 */
	public function createDimmensions($url, Array $dimmensions)
	{
		// Get default dimmensions
		$defaultDimensions = Config::get('media.image_dimmensions');

		if (is_array($defaultDimensions)) $dimmensions = array_merge($defaultDimensions, $dimmensions);

		foreach ($dimmensions as $dimmension)
		{
			// Get dimmensions and quality
			$width   = (int) $dimmension[0];
			$height  = isset($dimmension[1]) ?  (int) $dimmension[1] : $width;
			$crop    = isset($dimmension[2]) ? (bool) $dimmension[2] : false;
			$quality = isset($dimmension[3]) ?  (int) $dimmension[3] : $this->quality;

			// Run resizer
			$img = $this->resize($url, $width, $height, $crop, $quality);
		}
	}

}
