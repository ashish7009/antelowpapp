<?php namespace App\Http\Controllers;

use App\Http\Controllers\FrontbaseController;
use Illuminate\Http\Request;
use App\Series;
use App\Seriesmedia;
use App\Seriesmediaadminlikedislike;
use App\User;
use App\Truefriend;
use App\Followuser;
use Carbon\Carbon;

class SerieslistController extends FrontbaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('userauth', ['only' => ['index', 'search', 'view', 'load']]);
        //$this->middleware('userauth', ['only' => ['myseries', 'mysearch']]);
    }

    /*public function myseries() {
        $data = [];
        $data['menu_myseries'] = true;
        $serieslist = Series::ofuser($this->globaldata['user']->userid)->latest()->get();
        $data['serieslist'] = $serieslist;
        return view('myseries', $data);
    }

    public function mysearch($keyword) {
        $data = [];
        $data['menu_myseries'] = true;
        $serieslist = Series::ofuser($this->globaldata['user']->userid)->search($keyword)->latest()->get();
        $data['keyword'] = $keyword;
        $data['serieslist'] = $serieslist;
        return view('myseries', $data);
    }*/

    public function index()
    {

        $data = [];
        $data['menu_series'] = true;
        $data['slug'] = '';
        $data['mediastring'] = '';
        $data['keyword'] = '';

        return view('series', $data);
        echo "hello";
        die;
    }

    public function available_now()
    {
        $data = [];
        $data['menu_series'] = true;
        $data['slug'] = '';
        $data['mediastring'] = '';
        $data['keyword'] = '';
        return view('availablenow', $data);
    }


    public function search($keyword)
    {
        $data = [];
        $data['menu_series'] = true;
        $data['headersearch'] = $keyword;
        $data['slug'] = '';
        $data['mediastring'] = '';
        $data['keyword'] = $keyword;
        return view('series', $data);
    }

    public function view($slug, $mediastring = '')
    {

        $series = Series::active()->where('slug', $slug)->get();
        if (!$series->isEmpty()) {
            $series = $series->first();
            $data = [];
            $data['menu_series'] = true;
            $data['headerslug'] = $slug;
            $data['slug'] = $slug;
            $data['mediastring'] = $mediastring;
            $data['keyword'] = '';
            return view('series', $data);
        } else {
            return view('errors.404');
        }
    }

    public function load(Request $request)
    {

        // AJAX REQUEST
        if ($request->ajax()) {

            $data = [];
            $page = intval($request->page);
            $slug = trim($request->slug);
            $mediastring = trim($request->mediastring);
            $keyword = trim($request->keyword);
            $perpage = 10;

            $seriesmedias = [];
            $lastpage = 0;
            //echo $this->globaldata['user']->userid."====current user";
            // get all followers
            $followusers = Followuser::where('followerid', $this->globaldata['user']->userid)->get();
            $f_users = [];
            foreach ($followusers as $fuser) {
                $f_users[] = $fuser->userid;
            }
            array_push($f_users, $this->globaldata['user']->userid);
            if ($slug != '') {
                //$series = Series::active()->where('slug', $slug)->whereIn('userid',$f_users)->get();
                $series = Series::select('seriesid')->active()->whereIn('userid', $f_users)->get();
                $s_users = [];
                foreach ($series as $suser) {
                    $s_users[] = $suser->seriesid;
                }
                if (!$series->isEmpty()) {
                    $series = $series->first();
                    if (!empty($mediastring)) {
                        $mediastring_array = explode('_', $mediastring);
                        $seriesmediaid = intval($mediastring_array[count($mediastring_array) - 1]);
                        $seriesmediasresult = Seriesmedia::active()->whereIn('seriesid', $s_users)->where('seriesmediaid', $seriesmediaid)->desc()->paginate($perpage);
                    } else {
                        $seriesmediasresult = Seriesmedia::active()->whereIn('seriesid', $s_users)->desc()->paginate($perpage);
                    }
                }

            } else if ($keyword != '') {
                //	$series = Series::active()->whereIn('userid',$f_users)->get();
                $series = Series::select('seriesid')->active()->whereIn('userid', $f_users)->get();
                $s_users = [];
                foreach ($series as $suser) {
                    $s_users[] = $suser->seriesid;
                }

                if (!$series->isEmpty()) {
                    $series = $series->first();

                    $seriesmediasresult = Seriesmedia::active()->whereIn('seriesid', $s_users)->search($keyword)->desc()->paginate($perpage);

                }

            } else {

                /****
                 *
                 *
                 * $series = Series::active()->whereIn('userid',$f_users)->get();
                 *
                 * if(!$series->isEmpty()) {
                 * $series = $series->first();
                 *
                 * $seriesmediasresult = Seriesmedia::active()->where('seriesid', $series->seriesid)->desc()->paginate($perpage);
                 *
                 * }
                 *
                 * //$seriesmediasresult = Seriesmedia::active()->desc()->paginate($perpage);
                 ***/
                $series = Series::select('seriesid')->active()->whereIn('userid', $f_users)->get();
                $s_users = [];
                foreach ($series as $suser) {
                    $s_users[] = $suser->seriesid;
                }

                if (!$series->isEmpty()) {


                    $series = $series->first();
                    $seriesmediasresult = Seriesmedia::active()->whereIn('seriesid', $s_users)->desc()->paginate($perpage);

                }
            }

            if (isset($seriesmediasresult) && !$seriesmediasresult->isEmpty()) {
                foreach ($seriesmediasresult as $seriesmedia) {
                    $series = $seriesmedia->series;
                    $userId = $series->userid;
                    $seriesmediaid = $seriesmedia->seriesmediaid;

                    $now = Carbon::now();
                    $episodes_will_air = Seriesmedia::active()->ofuser($userId)->where('publishdate', '>', $now)->get()->count();
                    $seriesmedia['episodes_will_air'] = ($episodes_will_air > 0) ? $episodes_will_air : 0;
                    $admin_likes = Seriesmediaadminlikedislike::where('seriesmediaid', $seriesmediaid)->get();

                    $seriesmedia['alikes'] = 0;
                    if (!empty($admin_likes)) {
                        foreach ($admin_likes as $alike) {
                            if (!empty($alike)) {
                                $seriesmedia['alikes'] = ($alike->no_of_likes) ? $alike->no_of_likes : 0;
                            }
                        }
                    }

                    $seriesmedia['episodes_will_air'] = ($episodes_will_air > 0) ? $episodes_will_air : 0;

                    if ($seriesmedia->isprocessing == 1 && $seriesmedia->videoid != '') {
                        $seriesmedia->updateProcessingStatus();
                        if ($seriesmedia->isprocessing == 0 && $seriesmedia->status == 1) {
                            $seriesmedias[] = $seriesmedia;
                        }
                    } else {
                        $seriesmedias[] = $seriesmedia;
                    }
                }
                $lastpage = $seriesmediasresult->lastPage();
            }
			$data['truefc'] = Truefriend::where('friend1_id',$this->globaldata['user']->userid)->orWhere('friend2_id',  $this->globaldata['user']->userid)->get();
            $data['seriesmedia'] = $seriesmedias;
			//dd($seriesmedias);
            $data['htmldata'] = view('ajax.series', ['seriesmedias' => $seriesmedias,'truefc'=>$data['truefc']])->render();
            $data['lastpage'] = $lastpage;
	    //$data['testtext'] = "abcd";

            return response()->json($data);

        } // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
    }

    public function load_story_page()
    {
            $data = [];
        //     // $page = intval($request->page);
        //     // $slug = trim($request->slug);
        //     // $mediastring = trim($request->mediastring);
        //     // $keyword = trim($request->keyword);
            $perpage = 10;
            

            $seriesmedias = [];
            $lastpage = 0;
        //     //echo $this->globaldata['user']->userid."====current user";
        //     // get all followers
            $followusers = Followuser::where('followerid', $this->globaldata['user']->userid)->get();
            $f_users = [];
            foreach ($followusers as $fuser) {
                $f_users[] = $fuser->userid;
            }
            array_push($f_users, $this->globaldata['user']->userid);
        
            $series = Series::select('seriesid')->active()->whereIn('userid', $f_users)->get();
            $s_users = [];
            foreach ($series as $suser) {
                $s_users[] = $suser->seriesid;
            }


            if (!$series->isEmpty()) {


                $series = $series->first();

                $seriesmediasresult = Seriesmedia::active()->whereIn('seriesid', $s_users)->desc()->paginate($perpage);

                }

            if (isset($seriesmediasresult) && !$seriesmediasresult->isEmpty()) {

                foreach ($seriesmediasresult as $seriesmedia) {
                    $series = $seriesmedia->series;
                    $userId = $series->userid;
                    $seriesmediaid = $seriesmedia->seriesmediaid;

                    $now = Carbon::now();
                    $episodes_will_air = Seriesmedia::active()->ofuser($userId)->where('publishdate', '>', $now)->get()->count();
                    $seriesmedia['episodes_will_air'] = ($episodes_will_air > 0) ? $episodes_will_air : 0;

                    $admin_likes = Seriesmediaadminlikedislike::where('seriesmediaid', $seriesmediaid)->get();

                    $seriesmedia['alikes'] = 0;
                    if (!empty($admin_likes)) {
                        foreach ($admin_likes as $alike) {
                            if (!empty($alike)) {
                                $seriesmedia['alikes'] = ($alike->no_of_likes) ? $alike->no_of_likes : 0;
                            }
                        }
                    }
                    
                    if ($seriesmedia->isprocessing == 1 && $seriesmedia->videoid != '') {
                        $seriesmedia->updateProcessingStatus();
                        if ($seriesmedia->isprocessing == 0 && $seriesmedia->status == 1) {
                            $seriesmedias[] = $seriesmedia;
                        }
                    } else {
                        $seriesmedias[] = $seriesmedia;
                    }
                }
            }
           
            return view('seriesStoryList', ['seriesmedias' => $seriesmedias]);
    }

    public function load_available_now(Request $request)
    {

        // AJAX REQUEST
        if ($request->ajax()) {

            $data = [];
            $page = intval($request->page);
            $slug = trim($request->slug);
            $mediastring = trim($request->mediastring);
            $keyword = trim($request->keyword);
            $perpage = 10;

            $seriesmedias = [];
            $lastpage = 0;
            //echo $this->globaldata['user']->userid."====current user";
            // get all followers
            $followusers = Followuser::where('followerid', $this->globaldata['user']->userid)->get();
            $f_users = [];
            foreach ($followusers as $fuser) {

                $res = User::where('userid', $fuser->userid)->get();
                $first_user = $res->first();
                // echo $first_user->publish_day."===".$day_array[$i];
                //echo strtolower(substr($first_user->publish_day, 0, -1)) == strtolower(date('l'));

                //if(strtolower(substr($first_user->publish_day, 0, -1)) == strtolower(date('l'))) {
                $f_users[] = $fuser->userid;
                //}
            }
            /* echo "<pre>";
            print_r($f_users);
            echo "</pre>";
            exit(); */

            if (1) {

                // $series = Series::active()->whereIn('userid',$f_users)->get();
                $series = Series::select('seriesid')->active()->whereIn('userid', $f_users)->get();
                $s_users = [];
                foreach ($series as $suser) {
                    $s_users[] = $suser->seriesid;
                }
                if (!$series->isEmpty()) {
                    $series = $series->first();

                    //    $seriesmediasresult = Seriesmedia::active()->where('seriesid', $series->seriesid)->desc()->paginate($perpage);

                    if (strtolower(date('l')) == "monday") {
                        $seriesmediasresult = Seriesmedia::active()->whereIn('seriesid', $s_users)->where('is_available', 1)->orderByRaw("FIELD(publish_day, 'Mondays','Sundays','Saturdays','Fridays','Thursdays','Wednesdays','Tuesdays') ASC")->orderByRaw("STR_TO_DATE(publish_time, '%H:%i:%s') desc")->paginate($perpage);
                    } else if (strtolower(date('l')) == "tuesday") {
                        $seriesmediasresult = Seriesmedia::active()->whereIn('seriesid', $s_users)->where('is_available', 1)->orderByRaw("FIELD(publish_day, 'Tuesdays','Mondays','Sundays','Saturdays','Fridays','Thursdays','Wednesdays') ASC")->orderByRaw("STR_TO_DATE(publish_time, '%H:%i:%s') desc")->paginate($perpage);
                    } else if (strtolower(date('l')) == "wednesday") {
                        $seriesmediasresult = Seriesmedia::active()->whereIn('seriesid', $s_users)->where('is_available', 1)->orderByRaw("FIELD(publish_day, 'Wednesdays','Tuesdays','Mondays','Sundays','Saturdays','Fridays','Thursdays') ASC")->orderByRaw("STR_TO_DATE(publish_time, '%H:%i:%s') desc")->paginate($perpage);
                    } else if (strtolower(date('l')) == "thursday") {
                        $seriesmediasresult = Seriesmedia::active()->whereIn('seriesid', $s_users)->where('is_available', 1)->orderByRaw("FIELD(publish_day, 'Thursdays','Wednesdays','Tuesdays','Mondays','Sundays','Saturdays','Fridays') ASC")->orderByRaw("STR_TO_DATE(publish_time, '%H:%i:%s') desc")->paginate($perpage);
                    } else if (strtolower(date('l')) == "friday") {
                        $seriesmediasresult = Seriesmedia::active()->whereIn('seriesid', $s_users)->where('is_available', 1)->orderByRaw("FIELD(publish_day, 'Fridays','Thursdays','Wednesdays','Tuesdays','Mondays','Sundays','Saturdays') ASC")->orderByRaw("STR_TO_DATE(publish_time, '%H:%i:%s') desc")->paginate($perpage);
                    } else if (strtolower(date('l')) == "saturday") {
                        $seriesmediasresult = Seriesmedia::active()->whereIn('seriesid', $s_users)->where('is_available', 1)->orderByRaw("FIELD(publish_day, 'Saturdays','Fridays','Thursdays','Wednesdays','Tuesdays','Mondays','Sundays') ASC")->orderByRaw("STR_TO_DATE(publish_time, '%H:%i:%s') desc")->paginate($perpage);
                    } else if (strtolower(date('l')) == "sunday") {
                        $seriesmediasresult = Seriesmedia::active()->whereIn('seriesid', $s_users)->where('is_available', 1)->orderByRaw("FIELD(publish_day, 'Sundays','Saturdays','Fridays','Thursdays','Wednesdays','Tuesdays','Mondays') ASC")->orderByRaw("STR_TO_DATE(publish_time, '%H:%i:%s') desc")->paginate($perpage);
                    }


                }

                //$seriesmediasresult = Seriesmedia::active()->desc()->paginate($perpage);
            }

            if (isset($seriesmediasresult) && !$seriesmediasresult->isEmpty()) {

                foreach ($seriesmediasresult as $seriesmedia) {
                    $series = $seriesmedia->series;
                    $userId = $series->userid;

                    $now = Carbon::now();
                    $episodes_will_air = Seriesmedia::active()->ofuser($userId)->where('publishdate', '>', $now)->get()->count();
                    $seriesmedia['episodes_will_air'] = ($episodes_will_air > 0) ? $episodes_will_air : 0;

                    if ($seriesmedia->ispublished) {
                        if ($seriesmedia->isprocessing == 1 && $seriesmedia->videoid != '') {
                            $seriesmedia->updateProcessingStatus();
                            if ($seriesmedia->isprocessing == 0 && $seriesmedia->status == 1) {
                                $seriesmedias[] = $seriesmedia;
                            }
                        } else {
                            $seriesmedias[] = $seriesmedia;
                        }
                    }
                }
                $lastpage = $seriesmediasresult->lastPage();
            }
            //echo "<pre>";
            // print_r($seriesmedias);
            //   echo "</pre>";
            // exit();

            $data['htmldata'] = view('ajax.series', ['seriesmedias' => $seriesmedias])->render();
            $data['lastpage'] = $lastpage;

            return response()->json($data);

        } // NON AJAX REQUEST
        else {
            return 'No direct access allowed!';
        }
    }
}
