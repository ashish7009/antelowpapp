<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel {

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	protected $middleware = [
		'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
		'Illuminate\Cookie\Middleware\EncryptCookies',
		'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
		'Illuminate\Session\Middleware\StartSession',
		'Illuminate\View\Middleware\ShareErrorsFromSession',
		'App\Http\Middleware\VerifyCsrfToken',
		'App\Http\Middleware\SessionDataCheckMiddleware',
	];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'adminauth' 	=> 'App\Http\Middleware\Manage\AuthenticateAdmin',
		'adminguest' 	=> 'App\Http\Middleware\Manage\RedirectIfAuthenticatedAdmin',
		'userauth'		=> 'App\Http\Middleware\AuthenticateUser',
		'userguest' 	=> 'App\Http\Middleware\RedirectIfAuthenticatedUser'
	];

}
