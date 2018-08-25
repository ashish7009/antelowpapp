<?php

use App\Seriesmedialikedislike;
use App\Seriesmedia;
use App\User;

// demo
// use Illuminate\Http\Request;
// use App\Http\Controllers\PushNotification;
// use App\Notificationlog;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('test',function ()
{
    return base_path(); 
});
Route::get('/', 												'HomeController@index');
Route::get('/get-paid', 										'HomeController@getpaid');
Route::get('/schedule-series', 										'HomeController@schedule_series');

Route::get('auth/facebook', 'SigninController@redirectToFacebook');
Route::get('auth/facebook/callback', 'SigninController@handleFacebookCallback');

Route::get('/user/signup', 										'SignupController@index');
Route::post('/user/signup', 									'SignupController@submit');
Route::get('/user/verify/{token}', 								'SignupController@verify');

Route::get('/user/signin', 										'SigninController@index');
Route::post('/user/signin', 									'SigninController@submit');
Route::get('/user/settoken','API\PostAPIController@settoken');
Route::group(array('prefix' => 'api'), function() {
    Route::post('/user/gettoken','API\PostAPIController@gettoken');
});

Route::get('/user/edit', 										'UserController@index');
Route::post('/user/tour', 										'UserController@tour');
Route::post('/user/edit', 										'UserController@submit');
Route::get('/user/logout',                 						'UserController@logout');
Route::get('/user/email/verify/{token}', 						'UserController@emailverify');
Route::get('/user/view/{id}', 									'UserController@view');
Route::get('/user/myfollower', 									'UserController@myfollower');
Route::get('/user/schedule', 									'UserController@schedule');
Route::get('/user/schedule_day/{keyword}', 					    'UserController@schedule_day');
Route::post('/user/follow', 									'UserController@follow');
Route::post('/user/search', 							        'UserController@search');
Route::get('/user/search/{keyword}',                            'UserController@searchResult');
Route::get('/user/list',                                      'UserController@getAllUsers');
Route::post('/user/people',                                      'UserController@getData');
Route::get('/user/hashtag/{searchtag?}','UserController@hash_tag');
Route::get('/user/user_notification', 							'UserController@user_notification')->middleware('SessionDataCheckMiddleware');
Route::post('/user/report',                                     'UserController@report');
Route::get('/story',                                     'SerieslistController@load_story_page');
Route::post('/series/story',                                     'SerieslistController@load_story');
Route::get('/user/forgot-password', 							'ForgetpasswordController@index');
Route::post('/user/forgot-password', 							'ForgetpasswordController@submit');
Route::get('/user/reset-password/{token}',						'ForgetpasswordController@resetpassword');
Route::post('/user/reset-password',								'ForgetpasswordController@resetpasswordsubmit');
Route::post('/user/truefriend-request',                                      'UserController@trequest');
Route::post('/user/truefriend-request-update',                                      'UserController@trequestUpdate');
Route::post('/user/truefriend-request-check',                                      'UserController@trequestCheck');

//Route::get('/series/add', 										'SeriesController@add');
//Route::get('/series/edit/{slug}', 								'SeriesController@edit');
//Route::post('/series/destroy',									'SeriesController@destroy');
Route::post('/series/medias/isviewed',                           'SeriesMediaController@isviewed');
Route::get('/upload',                                           'SeriesMediaController@upload')->middleware('SessionDataCheckMiddleware');;
Route::get('/upload_days',                                      'SeriesController@upload_days');
Route::post('/series/addnewupload',                        'SeriesMediaController@addnewupload')->middleware('SessionDataCheckMiddleware');

Route::get('/vlogs/edit', 										'SeriesMediaController@edit')->middleware('SessionDataCheckMiddleware');
Route::post('/vlogs/store', 									'SeriesController@submit');
Route::post('/series/medias/report',                           'SeriesMediaController@report');
Route::post('/series/medias/destroy',                           'SeriesMediaController@destroymedia');
Route::post('/series/medias/makeMediaAvailable',                 'SeriesMediaController@makeMediaAvailable');
Route::post('/series/medias/comments/addn',   'SeriesMediaController@addcommentn');
//Route::post('/series/medias/comments/add',						'SeriesController@addcomment');
Route::post('/series/medias/comments/get',						'SeriesController@getcomments');
Route::post('/series/medias/comments/deleten',					'SeriesMediaController@deletecommentn');

Route::get('/series', 											'SerieslistController@index');
Route::get('/series/available_now', 							'SerieslistController@available_now');

Route::post('/series/addnew',                                   'SeriesController@addnew');
Route::get('/series/search/{keyword}', 							'SerieslistController@search');
/*Route::get('/series/my', 										'SerieslistController@myseries');
Route::get('/series/my/search/{keyword}', 						'SerieslistController@mysearch');*/
Route::get('/series/{slug}/{mediastring?}',                     'SerieslistController@view');
Route::post('/series/load', 									'SerieslistController@load');
Route::post('/series/load_available_now', 						'SerieslistController@load_available_now');
Route::post('/series/media/liken', 								'SeriesMediaController@liken');
Route::post('/series/media/adminlike', 								'SeriesMediaController@adminlike');
Route::post('/series/media/request-counter',                     'SeriesMediaController@requestCounter');
Route::post('/invite/friends',									'InviteController@invitefriends');

Route::post('/seriesmedia/click', 								'AnalyticsController@mediaclick');

Route::get('/contact-us', 										'ContactusController@index');
Route::post('/contact-us', 										'ContactusController@submit');

Route::get('/demo',function(){
    return view('demo');
});
// Route::post('/demo',function(Request $request){
//     $title = $request->title;
//     $msg = $request->message;
//   $msgPayload = array(
//             'mtitle'    => $title,
//             'mdesc'     => $msg,
//         );
//   $PNO = new PushNotification();
//   $user = User::where('email','=','vivid.ajay@gmail.com')->first();
//     $token = $user->registration_id;

//   $status = $PNO->android($msgPayload,$token);
//   echo $status;
// });


//Influencer 
Route::get('/influencer','InfluencerController@index');
Route::get('/influencer/get_user_list','InfluencerController@getUserList');
Route::post('/influencer/get_user_list','InfluencerController@verifyUser');
Route::post('/influencer/selectedUser/{userselected}','InfluencerController@selectedUser');

Route::get('/schedule-data',function(){
$users = User::frontuser()->get();

    foreach ($users as $user){
	$globaldata['notifications'] = $user->notifications()->orderBy('notificationid', 'desc')->get();
        $notificationcounts = count($globaldata['notifications']);
        
        $episodes_will_air_today = Seriesmedia::active()->ofuser($user->userid)->noimmidiatepublish()->airtoday()->get()->count();
        $episodes_will_air_today1 = Seriesmedia::active()->ofuser($user->userid)->noimmidiatepublish()->airtoday()->get();
         echo $episodes_will_air_today;
         echo "<pre>"; print_r($episodes_will_air_today1);echo "</pre>";
        if($episodes_will_air_today > 0) {
            $notificationcounts++;
        }
        $globaldata['episodes_will_air_today_count'] = $episodes_will_air_today;
        $justairedepisodes = Seriesmedia::active()->ofuser($user->userid)->noimmidiatepublish()->airedinlasthour()->orderBy('publishdate', 'desc')->get();
        if(!$justairedepisodes->isEmpty()) {
            $notificationcounts += count($justairedepisodes);
        }
        $globaldata['justairedepisodes'] = $justairedepisodes;
        $globaldata['notificationcounts'] = $notificationcounts;
        $globaldata['myseriesmedialikes'] = array_column(Seriesmedialikedislike::ofUser($user->userid)->likes()->get()->toArray(), 'seriesmediaid');

     }
});


View::composer('errors/404', function($view)
{
	$globaldata = [];
	if(Auth::user()->check()) {
		$user = Auth::user()->get();
        $globaldata['user'] = $user;
        $globaldata['notifications'] = $user->notifications()->orderBy('notificationid', 'desc')->get();
        $notificationcounts = count($globaldata['notifications']);
        $episodes_will_air_today = Seriesmedia::active()->noimmidiatepublish()->airtoday()->get()->count();
        if($episodes_will_air_today > 0) {
            $notificationcounts++;
        }
        $globaldata['episodes_will_air_today_count'] = $episodes_will_air_today;
        $justairedepisodes = Seriesmedia::active()->ofuser($user->userid)->noimmidiatepublish()->airedinlasthour()->orderBy('publishdate', 'desc')->get();
        if(!$justairedepisodes->isEmpty()) {
            $notificationcounts += count($justairedepisodes);
        }
        $globaldata['justairedepisodes'] = $justairedepisodes;
        $globaldata['notificationcounts'] = $notificationcounts;
        $globaldata['myseriesmedialikes'] = array_column(Seriesmedialikedislike::ofUser($user->userid)->likes()->get()->toArray(), 'seriesmediaid');
        //$globaldata['userfollow'] = array_column(Followuser::get()->toArray(), 'followuserid');
	}
    View::share('globaldata', $globaldata);
});

/*----------------------------------------BACKEND ROUTES-----------------------------------------------*/

View::composer('manage/errors/404', function($view)
{
	$globaldata = [];
	if(Auth::admin()->check()) {
		$admin = Auth::admin()->get();
		$globaldata['admin'] = $admin;
	}
	$view->with('globaldata', $globaldata);
});

/* ADMIN DEFAULT PAGE ROUTES */
Route::get('/manage',                        					'Manage\AdminController@index');
Route::get('/manage/dashboard',                        			'Manage\AdminController@index');
Route::get('/manage/404',                        				'Manage\AdminController@pagenotfound');

/* ADMIN LOGIN/LOGOUT PAGES ROUTES */
Route::get('/manage/login',                  					'Manage\LoginController@login');
Route::post('/manage/login',                 					'Manage\LoginController@submit');
Route::get('/manage/logout',                 					'Manage\LogoutController@logout');

/* PROFILE PAGES ROUTES */
Route::get('/manage/profile',                					'Manage\AdminController@profile');
Route::post('/manage/profile',                                  'Manage\AdminController@submit');

/* USERS PAGES ROUTES */
Route::get('/manage/users',               						'Manage\UsersController@index');
Route::post('/manage/users/load',         						'Manage\UsersController@load');
Route::get('/manage/users/add',       							'Manage\UsersController@add');
Route::get('/manage/users/edit/{id}', 							'Manage\UsersController@edit');
Route::post('/manage/users/store',    							'Manage\UsersController@store');
Route::post('/manage/users/destroy',      						'Manage\UsersController@destroy');
Route::post('/manage/users/influencersubmit',                   'Manage\UsersController@influencerssubmit');
?>
