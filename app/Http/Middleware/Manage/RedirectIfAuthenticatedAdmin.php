<?php namespace App\Http\Middleware\Manage;
use Auth;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
class RedirectIfAuthenticatedAdmin {
	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;
	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct()
	{
		$this->auth = Auth::admin();
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{

		if ($this->auth->check()) {
			if ($request->ajax()) {
				$data = [];
				$data['type'] = 'success';
				$data['redirectUrl'] = url('/manage/dashboard');
				return response()->json($data);
			}
			else {
				return new RedirectResponse(url('/manage/dashboard'));
			}
		}

		return $next($request);
	}

}
