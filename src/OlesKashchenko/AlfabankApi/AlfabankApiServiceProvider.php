<?php

namespace OlesKashchenko\AlfabankApi;

use Illuminate\Support\ServiceProvider;

class AlfabankApiServiceProvider extends ServiceProvider {

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
		$this->package('oles-kashchenko/alfabank-api');

		/*
		include __DIR__.'/../../helpers.php';
		//include __DIR__.'/../../filters.php';
		include __DIR__.'/../../routes.php';
		*/
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['alfabankapi'] = $this->app->share(function($app) {
			return new AlfabankApi();
		});
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
