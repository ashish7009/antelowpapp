<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Application Debug Mode
	|--------------------------------------------------------------------------
	|
	| When your application is in debug mode, detailed error messages with
	| stack traces will be shown on every error that occurs within your
	| application. If disabled, a simple generic error page is shown.
	|
	*/

	'debug' => env('APP_DEBUG'),

	/*
	|--------------------------------------------------------------------------
	| Application URL
	|------------------------------------------------------------------------
	|
	| This URL is used by the console to properly generate URLs when using
	| the Artisan command line tool. You should set this to the root of
	| your application so that it is used when running Artisan tasks.
	|
	*/

	'url' => 'http://antelowpapp.com',
	//'url' => 'http://seeris.com',

	/*
	|--------------------------------------------------------------------------
	| Application Timezone
	|--------------------------------------------------------------------------
	|
	| Here you may specify the default timezone for your application, which
	| will be used by the PHP date and date-time functions. We have gone
	| ahead and set this to a sensible default for you out of the box.
	|
	*/

	'timezone' => 'America/New_York',

	/*
	|--------------------------------------------------------------------------
	| Application Locale Configuration
	|--------------------------------------------------------------------------
	|
	| The application locale determines the default locale that will be used
	| by the translation service provider. You are free to set this value
	| to any of the locales which will be supported by the application.
	|
	*/

	'locale' => 'en',

	/*
	|--------------------------------------------------------------------------
	| Application Fallback Locale
	|--------------------------------------------------------------------------
	|
	| The fallback locale determines the locale to use when the current one
	| is not available. You may change the value to correspond to any of
	| the language folders that are provided through your application.
	|
	*/

	'fallback_locale' => 'en',

	/*
	|--------------------------------------------------------------------------
	| Encryption Key
	|--------------------------------------------------------------------------
	|
	| This key is used by the Illuminate encrypter service and should be set
	| to a random, 32 character string, otherwise these encrypted strings
	| will not be safe. Please do this before deploying an application!
	|
	*/

	'key' => env('APP_KEY', 'SomeRandomString'),

	'cipher' => MCRYPT_RIJNDAEL_128,

	/*
	|--------------------------------------------------------------------------
	| Logging Configuration
	|--------------------------------------------------------------------------
	|
	| Here you may configure the log settings for your application. Out of
	| the box, Laravel uses the Monolog PHP logging library. This gives
	| you a variety of powerful log handlers / formatters to utilize.
	|
	| Available Settings: "single", "daily", "syslog", "errorlog"
	|
	*/

	'log' => 'daily',

	/*
	|--------------------------------------------------------------------------
	| Autoloaded Service Providers
	|--------------------------------------------------------------------------
	|
	| The service providers listed here will be automatically loaded on the
	| request to your application. Feel free to add your own services to
	| this array to grant expanded functionality to your applications.
	|
	*/

	'providers' => [

		/*
		 * Laravel Framework Service Providers...
		 */

		'Illuminate\Foundation\Providers\ArtisanServiceProvider',
		//'Illuminate\Auth\AuthServiceProvider',
		'Ollieread\Multiauth\MultiauthServiceProvider',
		'Illuminate\Bus\BusServiceProvider',
		'Illuminate\Cache\CacheServiceProvider',
		'Illuminate\Foundation\Providers\ConsoleSupportServiceProvider',
		'Illuminate\Routing\ControllerServiceProvider',
		'Illuminate\Cookie\CookieServiceProvider',
		'Illuminate\Database\DatabaseServiceProvider',
		'Illuminate\Encryption\EncryptionServiceProvider',
		'Illuminate\Filesystem\FilesystemServiceProvider',
		'Illuminate\Foundation\Providers\FoundationServiceProvider',
		'Illuminate\Hashing\HashServiceProvider',
		'Illuminate\Mail\MailServiceProvider',
		'Illuminate\Pagination\PaginationServiceProvider',
		'Illuminate\Pipeline\PipelineServiceProvider',
		'Illuminate\Queue\QueueServiceProvider',
		'Illuminate\Redis\RedisServiceProvider',
		//'Illuminate\Auth\Passwords\PasswordResetServiceProvider',
		'Ollieread\Multiauth\Passwords\PasswordResetServiceProvider',
		'Illuminate\Session\SessionServiceProvider',
		'Illuminate\Translation\TranslationServiceProvider',
		'Illuminate\Validation\ValidationServiceProvider',
		'Illuminate\View\ViewServiceProvider',
		'Collective\Html\HtmlServiceProvider',
		'Intervention\Image\ImageServiceProvider',
		'Laravel\Socialite\SocialiteServiceProvider',
		/*
		 * Application Service Providers...
		 */
		'App\Providers\AppServiceProvider',
		'App\Providers\BusServiceProvider',
		'App\Providers\ConfigServiceProvider',
		'App\Providers\EventServiceProvider',
		'App\Providers\RouteServiceProvider',

	],

	/*
	|--------------------------------------------------------------------------
	| Class Aliases
	|--------------------------------------------------------------------------
	|
	| This array of class aliases will be registered when this application
	| is started. However, feel free to register as many as you wish as
	| the aliases are "lazy" loaded so they don't hinder performance.
	|
	*/

	'aliases' => [

		'App'       => 'Illuminate\Support\Facades\App',
		'Artisan'   => 'Illuminate\Support\Facades\Artisan',
		'Auth'      => 'Illuminate\Support\Facades\Auth',
		'Blade'     => 'Illuminate\Support\Facades\Blade',
		'Bus'       => 'Illuminate\Support\Facades\Bus',
		'Cache'     => 'Illuminate\Support\Facades\Cache',
		'Config'    => 'Illuminate\Support\Facades\Config',
		'Cookie'    => 'Illuminate\Support\Facades\Cookie',
		'Crypt'     => 'Illuminate\Support\Facades\Crypt',
		'DB'        => 'Illuminate\Support\Facades\DB',
		'Eloquent'  => 'Illuminate\Database\Eloquent\Model',
		'Event'     => 'Illuminate\Support\Facades\Event',
		'File'      => 'Illuminate\Support\Facades\File',
		'Hash'      => 'Illuminate\Support\Facades\Hash',
		'Input'     => 'Illuminate\Support\Facades\Input',
		'Inspiring' => 'Illuminate\Foundation\Inspiring',
		'Lang'      => 'Illuminate\Support\Facades\Lang',
		'Log'       => 'Illuminate\Support\Facades\Log',
		'Mail'      => 'Illuminate\Support\Facades\Mail',
		'Password'  => 'Illuminate\Support\Facades\Password',
		'Queue'     => 'Illuminate\Support\Facades\Queue',
		'Redirect'  => 'Illuminate\Support\Facades\Redirect',
		'Redis'     => 'Illuminate\Support\Facades\Redis',
		'Request'   => 'Illuminate\Support\Facades\Request',
		'Response'  => 'Illuminate\Support\Facades\Response',
		'Route'     => 'Illuminate\Support\Facades\Route',
		'Schema'    => 'Illuminate\Support\Facades\Schema',
		'Session'   => 'Illuminate\Support\Facades\Session',
		'Storage'   => 'Illuminate\Support\Facades\Storage',
		'URL'       => 'Illuminate\Support\Facades\URL',
		'Validator' => 'Illuminate\Support\Facades\Validator',
		'View'      => 'Illuminate\Support\Facades\View',
		'Form' 	    => 'Collective\Html\FormFacade',
		'Html' 	    => 'Collective\Html\HtmlFacade',
		'Image'     => 'Intervention\Image\Facades\Image',
		'Socialite' => 'Laravel\Socialite\Facades\Socialite'
	],

	/* WEBSITE RELATED CONSTANTS */
	'constants' => [

		// SOME COMMON CONSTANTS
		'website'						=> 'Schemk',
		'websitedomainname'				=> 'schemk.com',
		'admin_email'					=> 'pstung92@gmail.com',
		// 'admin_email'					=> 'eval_samuel@yahoo.com',
		'perpage'						=> 10,
		'dateformat_listing_datetime'	=> 'F d, Y h:i A',
		'dateformat_grudgecard'			=> 'M d, Y h:i A',
		'dir_thumb'						=> 'thumb/',
		'dir_original'					=> 'original/',
			
		// USER RELATED DATA
		'path_user'				   		=> '/data/users/',
		'user_image_width' 	   			=> 500,
		'user_image_height' 	   		=> 500,

		// SERIES RELATED DATA
		'path_series'					=> '/data/seeris/',
		'dir_series_files'				=> 'files/',
		'dir_medias'					=> 'medias/',
		'dir_file'						=> 'file/',
		'series_image_width' 	   		=> 440,
		'series_image_height' 	   		=> 500,

		//  INTERESTS RELATED DATA
		'interests' 				=> [
			'Reading' 					=> 'Reading',
			'Watching TV' 				=> 'Watching TV',
			'Family Time' 				=> 'Family Time',
			'Going to Movies' 			=> 'Going to Movies',
			'Fishing' 					=> 'Fishing',
			'Computer' 					=> 'Computer',
			'Gardening' 				=> 'Gardening',
			'Renting Movies' 			=> 'Renting Movies',
			'Walking' 					=> 'Walking',
			'Exercise' 					=> 'Exercise',
			'Listening to Music' 		=> 'Listening to Music',
			'Entertaining' 				=> 'Entertaining',
			'Hunting' 					=> 'Hunting',
			'Team Sports' 				=> 'Team Sports',
			'Shopping' 					=> 'Shopping',
			'Traveling' 				=> 'Traveling',
			'Sleeping' 					=> 'Sleeping',
			'Socializing' 				=> 'Socializing',
			'Sewing' 					=> 'Sewing',
			'Golf' 						=> 'Golf',
			'Church Activities' 		=> 'Church Activities',
			'Relaxing' 					=> 'Relaxing',
			'Playing Music' 			=> 'Playing Music',
			'Housework' 				=> 'Housework',
			'Crafts' 					=> 'Crafts',
			'Watching Sports' 			=> 'Watching Sports',
			'Bicycling' 				=> 'Bicycling',
			'Playing Cards' 			=> 'Playing Cards',
			'Hiking' 					=> 'Hiking',
			'Cooking' 					=> 'Cooking',
			'Eating Out' 				=> 'Eating Out',
			'Dating Online' 			=> 'Dating Online',
			'Swimming' 					=> 'Swimming',
			'Camping' 					=> 'Camping',
			'Skiing' 					=> 'Skiing',
			'Working on Cars' 			=> 'Working on Cars',
			'Writing' 					=> 'Writing',
			'Boating' 					=> 'Boating',
			'Motorcycling' 				=> 'Motorcycling',
			'Animal Care' 				=> 'Animal Care',
			'Bowling' 					=> 'Bowling',
			'Painting' 					=> 'Painting',
			'Running' 					=> 'Running',
			'Dancing' 					=> 'Dancing',
			'Horseback Riding' 			=> 'Horseback Riding',
			'Tennis' 					=> 'Tennis',
			'Theater' 					=> 'Theater',
			'Billiards' 				=> 'Billiards',
			'Beach' 					=> 'Beach',
			'Volunteer Work' 			=> 'Volunteer Work',
			'Gaming'					=> 'Gaming'
		],

		// EMAIL TEMPLATES RELATED DATA
		'emailtemplates' 				=> [
			'newuserregistraion' 				=> 1,
			'userresetpassword'					=> 2,
			'useremailverification'				=> 3,
			'invitefriendsmail'					=> 4,
			'notifyfollowersfornewmedia'		=> 5,
			'contactusmailtoadmin'				=> 6
		]
	]

];
