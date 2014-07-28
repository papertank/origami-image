<?php namespace Origami\Image;

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
        $this->package('origami/image', 'origami/image');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
    {
        $this->app['config']->package('origami/image', __DIR__.'/../../config', 'origami/image');

		$this->app->register('Intervention\Image\ImageServiceProvider');
        $this->app->alias('Image', 'Intervention\Image\Facades\Image');
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
