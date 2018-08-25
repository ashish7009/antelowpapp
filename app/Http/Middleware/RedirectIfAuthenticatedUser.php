<?php namespace App\Http\Middleware;
use Auth;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
class RedirectIfAuthenticatedUser {
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
		$this->auth = Auth::user();
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
				$data['caption'] = 'You are already logged in. Please logout first to continue.';
				$data['redirectUrl'] = url('/series');
				return response()->json($data);
			}
			else {
				return new RedirectResponse(url('/series'));
			}
		}

		return $next($request);
	}

}
