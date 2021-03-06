<?php 
namespace App\Providers;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot(UrlGenerator $url)
	{
	  if(env('REDIRECT_HTTPS'))
	  {
	    $url->forceSchema('https');
	  }
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('path.public', function() {
          return base_path().'/public_html';
        });
	}

}
