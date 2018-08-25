<?php namespace App\Http\Middleware\Manage;
use Auth;
use Closure;
use Illuminate\Contracts\Auth\Guard;
class AuthenticateAdmin {
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

		if ($this->auth->guest()) {
			if ($request->ajax()) {
				/*return response('Unauthorized.', 401);*/
				$data = [];
				$data['type'] = 'error';
				$data['caption'] = 'Unauthorized access.';
				$data['redirectUrl'] = url('/manage/login');
				return response()->json($data);
			}
			else {
				return redirect()->guest('manage/login');
			}
		}

		return $next($request);
	}

}
