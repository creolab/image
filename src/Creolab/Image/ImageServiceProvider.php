<?php namespace Creolab\Image;

use Illuminate\Support\ServiceProvider;

class ImageServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		// Register the package
		$this->package('creolab/image', 'image', __DIR__.'/../../');

		// Register theme singleton
		$this->app->singleton('creolab.image', function() { return new \Creolab\Image\Image; });

		// Also register a facade
		if ($alias = $this->app['config']->get('image::alias'))
		{
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader->alias($alias, '\Creolab\Image\ImageFacade');
		}
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
