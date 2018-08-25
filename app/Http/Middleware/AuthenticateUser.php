<?php namespace App\Http\Middleware;
use Auth;
use Closure;
use Illuminate\Contracts\Auth\Guard;
class AuthenticateUser {
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

		if ($this->auth->guest()) {
			if ($request->ajax()) {
				$data = [];
				$data['type'] = 'error';
				$data['caption'] = 'Unauthorized access.';
				$data['redirectUrl'] = url('/user/signin');
				return response()->json($data);
			}
			else {
				return redirect()->guest('/user/signin');
			}
		}

		return $next($request);
	}

}
