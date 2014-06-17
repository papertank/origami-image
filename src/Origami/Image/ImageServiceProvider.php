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

        $this->overwriteConfig();

		$this->app->register('Intervention\Image\ImageServiceProvider');
        $this->app->alias('Image', 'Intervention\Image\Facades\Image');
	}

    protected function overwriteConfig()
    {
        $this->app['config']->package('intervention/imagecache', __DIR__.'/../../../vendor/intervention/imagecache/src/config', 'imagecache');

        $match = [
            'route' => 'sizes_route',
            'paths' => 'paths',
            'lifetime' => 'cache'
        ];

        foreach ( $match as $key => $origami ) {
            $this->app['config']->set('imagecache::'.$key, $this->app['config']->get('origami/image::'.$origami));
        }

        $templates = [];

        foreach ( $this->app['config']->get('origami/image::sizes') as $size => $dimensions ) {
            $templates[$size] = function($image) use($dimensions) {
                return $image->fit($dimensions[0],$dimensions[1]);
            };
        }

        $this->app['config']->set('imagecache::templates', $templates);
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
