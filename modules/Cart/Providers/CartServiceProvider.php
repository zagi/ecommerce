<?php namespace Modules\Cart\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Cart\Repositories\Cart;

class CartServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Boot the application events.
	 * 
	 * @return void
	 */
	public function boot()
	{
		$this->registerConfig();
		$this->registerTranslations();
		$this->registerViews();
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{		
		$this->app['cart'] = $this->app->share(function($app)
            {
                 $storage = $app['session'];
                 $events  = $app['events'];
                 $instanceName = 'cart';
                 $session_key  = 'RYKNBG3FVYmrueFd';
                     
                 return new Cart(
                     $storage,
                     $events,
                     $instanceName,
                     $session_key
                 );
            });
	}

	/**
	 * Register config.
	 * 
	 * @return void
	 */
	protected function registerConfig()
	{
		$this->publishes([
		    __DIR__.'/../Config/config.php' => config_path('cart.php'),
		]);
		$this->mergeConfigFrom(
		    __DIR__.'/../Config/config.php', 'cart'
		);
	}

	/**
	 * Register views.
	 * 
	 * @return void
	 */
	public function registerViews()
	{
		$viewPath = base_path('resources/views/modules/cart');

		$sourcePath = __DIR__.'/../Resources/views';

		$this->publishes([
			$sourcePath => $viewPath
		]);

		$this->loadViewsFrom([$viewPath, $sourcePath], 'cart');
	}

	/**
	 * Register translations.
	 * 
	 * @return void
	 */
	public function registerTranslations()
	{
		$langPath = base_path('resources/lang/modules/cart');

		if (is_dir($langPath)) {
			$this->loadTranslationsFrom($langPath, 'cart');
		} else {
			$this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'cart');
		}
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
