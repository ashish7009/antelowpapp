<!Doctype html>

<html lang="en" class="@yield('htmlclass')">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="base-url" content="{{ secure_url('/') }}"/>

    <title>@if(isset($pagetitle)) {{ $pagetitle }} @else @yield('pagetitle') @endif</title>

    <meta name="description"
          content="Turn your life into a tv series. Upload clips that grab your friends' attention! Air your experiences when you want to and Keep your friends in the loop for all your new postings. Upload 60 seconds video  that  document your experiences.">

    <link rel="icon" href="{{ secure_asset('/images/favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,400i,700,700i|Lobster+Two:400,400i,700,700i|Alfa+Slab+One"
          rel="stylesheet">
    <link rel="stylesheet" href="{{ secure_asset('plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('plugins/bootstrapselect/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('/plugins/select2/select2.min.css') }}"/>
    <link rel="stylesheet" href="{{ secure_asset('plugins/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('plugins/animation/animate.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('plugins/alertify/css/alertify.core.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('plugins/alertify/css/alertify.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('plugins/icheck/square/yellow.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('plugins/datetimepicker/css/bootstrap-datetimepicker.min.css') }}"/>
    <link rel="stylesheet" href="{{ secure_asset('css/form-elements.css?cache=2.1') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/jquery-ui.structure.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/jquery-ui.theme.css') }}">

    <link rel="stylesheet" href="{{ secure_asset('css/style.css?cache=2.6') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/responsive.css?cache=2.1') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/circle.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/lightbox.min.css') }}">
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css">--}}
    <link rel="stylesheet" href="{{ secure_asset('css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/animate.css') }}">
    @yield('page-css')

 
	 <script src="{{ secure_asset('plugins/jquery/jquery-2.1.4.min.js') }}"></script>

    <script src="{{ secure_asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ secure_asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ secure_asset('plugins/bootstrapselect/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ secure_asset('plugins/bootstrapfilestyle/bootstrap-filestyle.min.js') }}"></script>
    <script src="{{ secure_asset('plugins/wow/wow.min.js') }}"></script>
    <script src="{{ secure_asset('plugins/scrollspeed/jquery.scrollspeed.js') }}"></script>
    <script src="{{ secure_asset('plugins/jquery/jquery.form.min.js') }}"></script>
    <script src="{{ secure_asset('plugins/jquery/jquery.validate.min.js') }}"></script>
    <script src="{{ secure_asset('plugins/jquery/jquery.validate.additional.methods.min.js') }}"></script>
    <script src="{{ secure_asset('plugins/alertify/js/alertify.js') }}"></script>
    <script src="{{ secure_asset('plugins/icheck/icheck.min.js') }}"></script>
    <script src="{{ secure_asset('plugins/datetimepicker/js/bootstrap-datetimepicker.js') }}"></script>
    <script src="{{ secure_asset('/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ secure_asset('plugins/common/common-front.js?cache=2.0') }}"></script>
    <script src="{{ secure_asset('plugins/jquery.inview.min.js') }}"></script>
    <script src="{{ secure_asset('js/main.js?cache=2.0') }}"></script>
    <script src="{{ secure_asset('js/moment.js') }}"></script>
    <script src="{{ secure_asset('js/combodate.js') }}"></script>
    <script src="{{ secure_asset('js/progress-circle.js') }}"></script>
    <script src="{{ secure_asset('js/webcam.js') }}"></script>
    <script src="{{ secure_asset('js/series.js?cache=2.13') }}"></script>
    <script src="{{ secure_asset('js/lightbox.min.js') }}"></script>
    <script src="{{ secure_asset('js/jquery.lazy.min.js') }}"></script>

   {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>--}}
    <script src="{{ secure_asset('js/owl.carousel.min.js') }}"></script>
    @yield('page-scripts')

</head>
<body @yield('bodyattr')>
<div class="wrapper">

    <header class="header @if(!isset($hideheaderanimation)) wow fadeInDown @endif @if(isset($page_home)) desktop-block @endif">
        <nav class="navbar">
            <div class="container header-container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#headernav">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="{{ isset($logotohome) ? secure_url('/') : secure_url('/series') }}" class="navbar-logo-mobil mt-2-tab">Pota<span>toes</span></a>
                    <a class="navbar-brand logo hideinhome" href="{{ isset($logotohome) ? secure_url('/') : secure_url('/series') }}">
							<span class="top-logo">
								Seeris
							</span>
                    </a>
                    @if(!isset($page_home) && isset($globaldata['user']))
                        <!-- <a href="javascript:void(0);" class="navbar-toggle-mobil" data-toggle="collapse"
                           data-target=".mobil-dropdown">
                            <img src="http://www.schemk.com/images/ic_profile@2X.png" alt="" style="width: 100%;">
                        </a> -->
                        <a href="{{ secure_url('/user/hashtag') }}" class="navbar-search1"></a>
                        <div class="navbar-search-inner">
                            <div class="header__back search_back">Cancel</div>
                            {!! Form::open(['url' =>'/user/search','id' => 'searchuser']) !!}
                            <select name="search_tag" id="search_tag" class="search_tag">
                                <option value="hashtag">Hashtag</option>
                                <option value="person">Person</option>
                            </select>
                            {!! Form::text('seriessearch', ((isset($headersearch)) ? $headersearch : '') , ['class' => 'hideinhome searchtext mobil-search', 'id' => 'seriessearch', 'placeholder' => 'Search for anythings..']) !!}
                            {!! Form::close() !!}
                        </div>
                        <a href="#" data-toggle="modal" data-target="#uploadModal" id="add-video-mobil" class="add-video-mobil"><img
                                    src="{{ secure_asset('images/ic_camera@2X.png') }}" alt=""></a>
                    @endif

                    <a class="navbar-brand get-paid btn btn-bordered btn-white showinhome"
                       href="{{ secure_url('/get-paid') }}">
                        Get Paid
                    </a>
                </div>
                
                <div class="collapse navbar-collapse menu-container" id="headernav">
                    @if(isset($globaldata['user']))
                        <div class="dropdown inline-block relative hideinhome notification-div">
                            <a class="dropdown-toggle no-hover nounderline notification-link" data-toggle="dropdown"
                               href="#">
                                <span class="notification-text">Notifications</span>
                                <span class="notification">
									<span class="fa fa-bell"></span>
                                    @if(isset($globaldata['notificationcounts']) && intval($globaldata['notificationcounts']) > 0)
                                        <span class="count color-black">{{ intval($globaldata['notificationcounts']) }}</span>
                                    @endif
								</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right notification-dropdown">
                                @if(isset($globaldata['notificationcounts']) && $globaldata['notificationcounts'] > 0)
                                    @if(isset($globaldata['episodes_will_air_today_count']) && $globaldata['episodes_will_air_today_count'] > 0)
                                        <li>
                                            <a>
                                                <span>{{ $globaldata['episodes_will_air_today_count'] }} @if($globaldata['episodes_will_air_today_count'] == 1)
                                                        post @else posts @endif will air today</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if(isset($globaldata['justairedepisodes']) && !$globaldata['justairedepisodes']->isEmpty())
                                        @foreach($globaldata['justairedepisodes'] as $justairedepisode)
                                            <li>
                                                <a target="_blank"
                                                   href="{{ secure_url('series/'.$justairedepisode->series->slug.'/'.seriesmedia_unique_string($justairedepisode)) }}">
                                                    <span>Your episode <b>{{ $justairedepisode->title }}</b> just aired</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif
                                    @if(isset($globaldata['notifications']))
                                        @foreach($globaldata['notifications'] as $notification)
									<?php  if($notification->notificationtext!=null && $notification->notificationtext!=''){?>
                                            <li>
                                                <a target="_blank" href="{{ secure_url($notification->notificationurl) }}">
                                                    <span>{{ $notification->notificationtext }}</span>
                                                </a>
                                            </li>
									<?php } ?>
                                        @endforeach
                                    @endif
                                @else
                                    <li>
                                        <a>
                                            <span>No notifications</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    @endif

                    @if(isset($globaldata['user']))
                        @if(isset($page_home))
                            <span class="inline-block showinhome pt5 mt5 invite-friends">
							<a class="btn btn-blue box-shadow" href="javascript: void(0);"
                               onclick="openInvitePopup(0, 'Invite Friends');">Invite Friends</a>
						</span>
                        @endif
                    @endif

                    {!! Form::text('seriessearch', ((isset($headersearch)) ? $headersearch : '') , ['class' => 'hideinhome searchtext', 'id' => 'seriessearch', 'placeholder' => 'Enter name...']) !!}
                    <ul class="nav navbar-nav">
                        <li class="@if(isset($menu_series)) active @endif">
                            <a href="{{ secure_url('/series') }}">Airs</a>
                        </li>
                        @if(!isset($globaldata['user']))
                            <li class="@if(isset($start_series)) active @endif hideinhome">
                                <a href="{{ secure_url('/user/signin') }}">Start an Air</a>
                            </li>
                            <li class="@if(isset($menu_signin)) active @endif">
                                <a href="{{ secure_url('/user/signin') }}">Sign In</a>
                            </li>
                            <li class="@if(isset($menu_signup)) active @endif">
                                <a href="{{ secure_url('/user/signup') }}">Sign Up</a>
                            </li>
                        @else
                            <li class="@if(isset($start_series)) active @endif hideinhome">
                                <a href="{{ secure_url('/vlogs/edit') }}">Start an Air</a>
                            </li>
                            <li class="dropdown">
                                <a class="dropdown-toggle no-hover" data-toggle="dropdown"
                                   href="#">{{ $globaldata['user']->firstname }} <span
                                            class="fa fa-caret-down text-center arrow-circle ml5"></span></a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li class="@if(isset($menu_profile)) active @endif">
                                        <a href="{{ secure_url('/user/edit') }}">
                                            <i class="fa fa-user"></i>
                                            <span>Profile</span>
                                        </a>
                                    </li>
                                    <li class="@if(isset($menu_myseries)) active @endif">
                                        <a href="{{ secure_url('/vlogs/edit') }}">
                                            <i class="fa fa-paper-plane-o"></i>
                                            <span>My Airs</span>
                                        </a>
                                    </li>
                                    <li class="@if(isset($menu_myfollower)) active @endif">
                                        <a href="{{ secure_url('/user/myfollower') }}">
                                            <i class="fa fa-user-plus fa-follower" aria-hidden="true"></i>
                                            <span>Followers</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript: void(0);" onclick="openInvitePopup(0, 'Invite Friends');"
                                           class="btn bg-pink">
                                            <i class="fa fa-share-alt"></i>
                                            <strong>Invite Friends</strong>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ secure_url('/user/logout') }}" class="text-center color-red">
                                            <strong>LOGOUT</strong>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <section class="content-section">
        @yield('content')
    </section>

    <!-- <footer class="footer color-white">
        <div class="container">
            <div class="text-center">
                <div class="color-white04 sm-p">&copy; 2017 Seeris.COM | New York </div>
                <div class="color-white04 sm-p"><a class="color-light-grey" target="_blank" href="#"> User Agreement</a></div>
            </div>
        </div>
    </footer> -->

</div>

<div id="model"></div>
<div id="loader">
    <img src="{{ secure_asset('images/load.png') }}" class="fa-pulse fa-spin fa-3x fa-fw" alt="">
    {{-- <span class="fa fa-spinner fa-pulse fa-spin fa-3x fa-fw"></span> --}}
    <label>Please wait</label>
</div>
<div id="progressloader">
    <!-- <div id="progress-label" class="text-center"></div> -->
    <div class="progress">
        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0"
             aria-valuemax="100" style="width: 20%;"></div>
    </div>
</div>
<!-- <a id="scrolltop" class="scrolltop fa fa-angle-up"></a> -->

@if(isset($globaldata['user']))
    <div id="seriesinvitemodel" class="modal md fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                {!! Form::open(['url' => '/invite/friends', 'id' => 'seriesinviteform']) !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title modeltitle">Invite friends</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <input type="hidden" name="seriesmediaid" class="seriesmediaid"/>
                        <div class="form-group text-center">
                            <img src="{{secure_asset('/images/circle.png')}}" width="90" height="90" />
                        </div>
                        <div class="form-group form-label">
                            <label class="control-label">
                                Enter email
                                {{--<span class="text-danger">*</span><br>--}}
                                {{--<small>(Enter comma seperated email addresses here.)</small>--}}
                            </label>
                            <div>
                                {!! Form::text('emails', null, ['class' => 'form-control','placeholder'=>'name@example.com']) !!}
                            </div>
                        </div>
                        {{--<div class="form-group m0">--}}
                            {{--<label class="control-label">Message <span class="text-danger">*</span></label>--}}
                            {{--<div>--}}
                                {{--{!! Form::textarea('message', null, ['class' => 'form-control']) !!}--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-invite">Invite</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>



    <div class="modal" id="uploadModal" tabindex="-1" role="dialog">
        <div class="modal-dialog1" role="document">
            <div class="modal-content1">
                {!! Form::open(['url' => '/series/addnewupload', 'id' => 'formseriesmediaaddnew']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-2">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="col-xs-10 text-right">
                            @if($globaldata['user']->publish_day != '')
                                <div class="col-xs-12 col0 pt3">
                                    <input type="submit" class="upload3__btn" name="submit" value="Done">
                                    <!-- <input type="button" class="upload3__btn up_time" style="display:none" value="DONE"> -->
                                </div>

                            @else
                                <div class="col-xs-12 col0 pt3">
                                    <input type="submit" class="upload3__btn" name="submit" value="Done">
                                    <!-- <input type="button" class="upload3__btn up_time" style="display:none"  value="DONE"> -->
                                </div>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="camera" id="camera" value="">
                    <input type="hidden" name="seriesmediaimmidiatepublish" value="0"/>
                    <div class="row">
                        <div class="col-xs-12 stitle">
                            <div class="form-group">
                                <textarea id="seriesmediatitle" name="seriesmediatitle"
                                          class="form-control input-md invisible-form-control seriesmediatitle seriesmedia-textarea upload-seriesmediatitle"
                                          placeholder="Enter Caption"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="slide1">
                        <div class="row">
                            <div class="col-xs-12 text-center upload-instruction">
                                {{-- <a href="javascript:void(0);"><i class="fa fa-angle-left left-arrow" style="display:none;" aria-hidden="true"></i></a> --}}
                                <img src="/images/photo-camera.png" class="upload-image" height="100" width="100">
                                <img id="image-preview" style="display: none;">
                                <div id="my_camera" style="display: none;"></div>
                                <div style="margin-top: 10%;"><span class="upload-text">Upload New Image</span></div>
                                <a href="javascript:void(0);"><i class="fa fa-angle-right right-arrow"
                                                                 aria-hidden="true"></i></a>
                            </div>
                        </div>
                        <div class="modal-footer1">
                            <ul class="footer-button">
                                <li class="first-li">
                                    <div class="col0 pt3 d-upload">
                                        <div class="upload-content-in-content ">
                                            <div class="uploader text-center form-group mb0 upload__btn">
                                                <div onclick="selectvideo(this);">
                                                    <div class=""><span class=""></span></div>
                                                    <div><span><i class="fa fa-file-image-o"></i></span></div>
                                                </div>
                                                <input type="file" name="seriesmediaimagefile" id="seriesmediaimagefile"
                                                       class="seriesmideiavideouploader videofile not-ignore"
                                                       accept="image/*">
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="second-li">
                                    <span class="upload-img-count" style="display: none;">0</span>
                                </li>
                                <li class="third-li">
                                    <div class="text-center">
                                        <button type="button" id="cameara-button" style="display:none;"><img src="/images/record button.png"
                                                                                       alt="" height="60" width="60"
                                                                                       onClick="setup();"></button>
                                        <button type="button" id="cameara-capture-button" style="display:none;"><img
                                                    src="/images/record button.png" alt="" height="60" width="60"
                                                    onClick="take_snapshot();"></button>
                                    </div>
                                </li>
                                <li class="forth-li">
                                    <div class="col0 pt3 d-upload">
                                        @if($globaldata['user']->publish_day != '')
                                            <p class="up_time
										@if($globaldata['user']->publish_day == 'Mondays')
                                                    upload__day_mondays
@elseif($globaldata['user']->publish_day == 'Tuesdays')
                                                    upload__day_tuesdays
@elseif($globaldata['user']->publish_day == 'Wednesdays')
                                                    upload__day_wednesday
@elseif($globaldata['user']->publish_day == 'Thursdays')
                                                    upload__day_thursdays
@elseif($globaldata['user']->publish_day == 'Fridays')
                                                    upload__day_fridays
@elseif($globaldata['user']->publish_day == 'Saturdays')
                                                    upload__day_saturdays
@elseif($globaldata['user']->publish_day == 'Sundays')
                                                    upload__day_sundays
@endif
                                                    text-center uploadtime">{{ date('D',strtotime($globaldata['user']->publish_day)) }}
                                                at {{ date('ha', strtotime($globaldata['user']->publish_time)) }}</p>
                                            <div class="clearfix"></div>
                                        @else
                                            <p class="up_time">Pick date & time</p>
                                            <div class="clearfix"></div>
                                        @endif
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="slide2" style="display: none;">
                        <div class="row">
                            <div class="col-xs-12 text-center upload-video-instruction">
                                <a href="javascript:void(0);"><i class="fa fa-angle-left left-arrow"
                                                                 style="display:none;" aria-hidden="true"></i></a>
                                <img src="/images/video_upload.png" class="video-image" height="100" width="100">
                                <div id="my_recorder" style="display: none;"></div>
                                <div style="margin-top: 10%;"><span class="video-text">Upload Clip that are 3 minutes or less</span>
                                </div>
                                <!-- <a href="javascript:void(0);"><i class="fa fa-angle-right right-arrow" aria-hidden="true"></i></a> -->
                            </div>
                        </div>
                        <div class="modal-footer1">
                            <ul class="footer-button">
                                <li class="first-li">
                                    <div class="col0 pt3 d-upload">
                                        <div class="upload-content-in-content ">
                                            <div class="uploader text-center form-group mb0 upload__btn">
                                                <div onclick="selectvideo(this);">
                                                    <div class=""><span class=""></span></div>
                                                    <div><span><i class="fa fa-file-image-o"></i></span></div>
                                                </div>
                                                <input type="file" name="seriesmediavideofile" id="seriesmediavideofile"
                                                       class="seriesmideiavideouploader videofile not-ignore"
                                                       accept="video/*">
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li class="second-li">
                                    <span class="upload-video-count" style="display: none;">0</span>
                                </li>
                                <li class="third-li">
                                    <div class="col0 pt3 d-upload upload_thumbnail">
                                        <div class="upload-content-in-content left_side_space">
                                            <div class="uploader text-center form-group mb0 video-upload-btn">
                                                <img onclick="selectvideo_thumbnail(this);"
                                                     src={{secure_asset('/images/video_upload_btn.png')}} class="video-thumbnail-icon"
                                                     height="30" width="50"/>
                                                {{-- <div class=""><span class=""></span></div>
                                                <div>span class="">--}}
                                                {{-- <i class="fa fa-check checkcolor"></i> --}}
                                                {{-- <img src='/images/video_upload_btn.png' height="50" width="50"> --}}
                                                {{-- </span></div> --}}
                                                {{-- </div> --}}
                                                <input type="file" name="seriesmediavideofile_thumbnail"
                                                       id="seriesmediavideofile_thumbnail"
                                                       class="seriesmideiavideouploader_thumbnail videofile not-ignore"
                                                       accept="image/*">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center"><span class="whitecolor">Add video thumbnail</span></div>
                                </li>
                                <li class="forth-li">
                                    <div class="col0 pt3 d-upload">
                                        @if($globaldata['user']->publish_day != '')
                                            <p class="up_time
									@if($globaldata['user']->publish_day == 'Mondays')
                                                    upload__day_mondays
@elseif($globaldata['user']->publish_day == 'Tuesdays')
                                                    upload__day_tuesdays
@elseif($globaldata['user']->publish_day == 'Wednesdays')
                                                    upload__day_wednesday
@elseif($globaldata['user']->publish_day == 'Thursdays')
                                                    upload__day_thursdays
@elseif($globaldata['user']->publish_day == 'Fridays')
                                                    upload__day_fridays
@elseif($globaldata['user']->publish_day == 'Saturdays')
                                                    upload__day_saturdays
@elseif($globaldata['user']->publish_day == 'Sundays')
                                                    upload__day_sundays
@endif
                                                    text-center uploadtime">{{ date('D',strtotime($globaldata['user']->publish_day)) }}
                                                at {{ date('ha', strtotime($globaldata['user']->publish_time)) }}</p>
                                            <div class="clearfix"></div>
                                        @else
                                            <p class="up_time">Pick date & time</p>
                                            <div class="clearfix"></div>
                                        @endif
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="upload3" style="display:none">
                    @if($globaldata['user']->publish_day != '')
                        <input type="hidden" class="choose_another_date" value=""/>
                    @else
                        <input type="hidden" class="choose_another_date" value="yes"/>
                    @endif
                    <p class="text-center">When do you want your contents to air ? </p>
                    <ul class="upload__day__list">
                        <li>
                            <div class="upload__day__item upload__day_mondays"><span>Every <br>Mondays</span></div>
                        </li>
                        <li>
                            <div class="upload__day__item upload__day_tuesdays"><span>Every <br>Tuesdays</span></div>
                        </li>
                        <li>
                            <div class="upload__day__item upload__day_wednesday"><span>Every <br>Wednesdays</span></div>
                        </li>
                        <li>
                            <div class="upload__day__item upload__day_thursdays"><span>Every <br>Thursdays</span></div>
                        </li>
                        <li>
                            <div class="upload__day__item upload__day_fridays"><span>Every <br>Fridays</span></div>
                        </li>
                        <li>
                            <div class="upload__day__item upload__day_saturdays"><span>Every <br>Saturdays</span></div>
                        </li>
                        <li>
                            <div class="upload__day__item upload__day_sundays"><span>Every <br>Sundays</span></div>
                        </li>
                    </ul>

                    <div class="col-xs-12 col0">
                        <div class="col-xs-6 pt4 width100-500 col2">
                            <div class="col-xs-5 width50-500 col2 form-group mb0">
                                <select class="form-control form-control-small seriesmediaimmidiatepublishno-controls"
                                        name="seriesmediahour" id="hour">
                                    <option value="">HH</option>
                                    @for($i=0;$i <= 23;$i++)
                                        @if($i < 10)
                                            <option value="0{{ $i }}"
                                                    @if($globaldata['user']->publish_time != "") @if(  explode(':',$globaldata['user']->publish_time)[0]  == $i) selected @endif @endif >
                                                0{{ $i }}</option>
                                        @else
                                            <option value="{{ $i }}"
                                                    @if($globaldata['user']->publish_time != "") @if(  explode(':',$globaldata['user']->publish_time)[0]  == $i) selected @endif @endif >{{ $i }}</option>
                                        @endif
                                    @endfor
                                </select>
                            </div>
                            <div class="col-xs-1 hide-500 col2 text-center">:</div>
                            <div class="col-xs-5 width50-500 col2 form-group mb0">
                                <select class="form-control form-control-small seriesmediaimmidiatepublishno-controls"
                                        name="seriesmediaminute" id="minute">
                                    <option value="">MM</option>
                                    @for($i=0;$i <= 59;$i++)
                                        @if($i < 10)
                                            <option value="0{{ $i }}"
                                                    @if($globaldata["user"]->publish_time != "") @if(  explode(':',$globaldata['user']->publish_time)[1]  == $i) selected @endif @endif >
                                                0{{ $i }}</option>
                                        @else
                                            <option value="{{ $i }}"
                                                    @if($globaldata['user']->publish_time != "") @if(  explode(':',$globaldata['user']->publish_time)[1]  == $i) selected @endif @endif >{{ $i }}</option>
                                        @endif
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col-xs-12">

                            <div class="col-xs-4 pt4 form-group" style="padding-left: 11px;padding-right:4px;">
                                <input type="text" class="form-control form-control-small black-back"
                                       name="seriesmediapublish_day" id="currDay"
                                       value="{{ $globaldata['user']->publish_day }}" readonly="">
                            </div>
                            <div class="col-xs-1 text-center" style="padding:8px 0px; width:5%">
                                <span>at </span>
                            </div>
                            <div class="col-xs-4 pt4 form-group" style="padding-left: 4px;">
                                <input type="text" class="form-control form-control-small black-back"
                                       name="seriesmediapublish_time" id="cDay"
                                       value="{{ $globaldata['user']->publish_time }}" readonly="">
                            </div>

                        </div>
                    </div>
                    <div class="col-xs-12 col0">
                        <div class="row row0">
                            <div class="col-xs-12 col0 pt3">
                                <input type="button" class="upload3__btn submitBtn closedatepicker" name="submit"
                                       value="DONE" onclick="closeDatePickerView(this);" style="display: none;">


                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog-imagepreview">
            <div class="modal-content">
                <div class="modal-body-imagepreview">
                    <img src="" class="imagepreview" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>
@endif



@yield('modal')
<style>
   
    .owl-carousel .owl-item .pop img{
        width: 100% !important;
        height: 100% !important;
    }
</style>
<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-103683389-1', 'auto');
    ga('send', 'pageview');
</script>

<script>
    (function ($) {
        $(function () {
            $(".datepicker").datepicker();
            $('.header__user').click(function (event) {
                $('.header__user__nav').toggleClass('active');
            });
            $('.header__search').click(function (event) {
                $(this).addClass('active');
                $('.header__search__inner').addClass('active');
            });
            $('.search_back').click(function (event) {
                $('.header__search').removeClass('active');
                $('.header__search__inner').removeClass('active');
            });
            $('.upload__day__item').click(function (event) {
                var currDay = $(this).text();
                var day = currDay.split(' ');
                $('#currDay').val(day[1]);

                var time = $('#hour').val() + ':' + $('#minute').val();
                //   $('.upload3__btn').attr('href', 'upload2.html?day='+day[1]+ '&hour='+ time);
                console.log($('#hour').val());
            });

            var gets = (function () {
                var a = window.location.search;
                var b = new Object();
                a = a.substring(1).split("&");
                for (var i = 0; i < a.length; i++) {
                    c = a[i].split("=");
                    b[c[0]] = c[1];
                }
                return b;
            })();

            $('.upload__date a').html(gets['day'] + ' @ ' + gets['hour']);
        });
    })(jQuery);

    setTimeout(function () {
        var showChar = 50;
        var ellipsestext = "...";
        var moretext = "more";
        var lesstext = "less";
        $('.more').each(function () {
            var content = $(this).html();

            if (content.length > showChar) {

                var c = content.substr(0, showChar);
                var h = content.substr(showChar, content.length - showChar);

                var html = c + '<span class="moreellipses">' + ellipsestext + '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="javascript:void(0);" class="morelink">' + moretext + '</a></span>';

                $(this).html(html);
            }
        });
        $(".morelink").click(function () {
            if ($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(moretext);
            } else {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });

        /**********/

        $('.xmore').each(function () {
            var content = $(this).text();

            if (content.length > showChar) {

                var c = content.substr(0, showChar);
                var h = content.substr(showChar, content.length - showChar);

                var html = c + ellipsestext + '  ' + '&nbsp;<span style="color:lightgray">' + moretext + '</span>';

                //var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<span class="morelink">' + moretext + '</span></span>';

                $(this).html(html);
            }
        });
        $(".xmorelink").click(function () {
            if ($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(moretext);
            } else {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });
      
       $(function() {
          $('.lazy').lazy();
       });

       hashtag_regexp = /((?!(#[a-fA-F0-9]{3})(\W|$)|(#[a-fA-F0-9]{6})(\W|$))#[a-zA-Z0-9]*)/g
function linkHashtags(text) {
 return text.replace(
    hashtag_regexp,
    '<span class="user-episode1">$1</span>'
 );
}

jQuery(document).ready(function(){
 jQuery('.episode').each(function() {
    jQuery(this).html(linkHashtags($(this).html()));
  });
});

    }, 2000);

</script>

<!-- image pop up -->
<script>
//    setTimeout(function () {
//        $(function () {
//            $('.pop').on('click', function () {
//                $('.imagepreview').attr('src', $(this).find('img').attr('src'));
//                $('#imagemodal').modal('show');
//            });
//        });
//    }, 2000);
</script>

<!-- {{-- Upload Script --}} -->

<script>
    $('.right-arrow').on('click', function () {
        $(this).hide();
        $('.left-arrow').show();
        $('.slide2').show();
        $('.slide1').hide();
        $('.left-arrow').css('top', '20%');
    });

    $('.left-arrow').on('click', function () {
        $(this).hide();
        $('.right-arrow').show();
        $('.slide2').hide();
        $('.slide1').show();
        $('.right-arrow').css('top', '20%');
    });
    $('.upload-content-in-content').on('click', function () {
        Webcam.reset();
        $('.upload-img-count').hide();
        $('#my_camera').hide();
        $('#image-preview').hide();
        $('.upload-instruction').css('margin-top', '15%');
        $('.upload-image').show();
        $('.upload-text').show();
        $('#cameara-button').show();
        $('#cameara-capture-button').hide();
    });
    $('#hour,#minute').on('change', function () {
        var H = $('#hour').val();
        console.log(H);
        var m = $('#minute').val();
        console.log(m);
        var hour = H % 12 || 12;
        var ampm = H < 12 ? "AM" : "PM";
        var timeString = hour + ':' + m + ' ' + ampm;
        console.log(timeString);
        $('#cDay').val(timeString);
    });

    // function readURL(input) {
    // 	var url = input.value;
    // 	var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
    // 	if (input.files && input.files[0]&& (ext == "gif" || ext == "png" || ext == "jpeg" || ext == "jpg")) {
    // 		var reader = new FileReader();

    // 		reader.onload = function (e) {
    // 			$('#image-preview').show();
    // 			$('.right-arrow').css('top','40%');
    // 			$('.left-arrow').css('top','40%');
    // 			$('.upload-instruction').css('margin-top','0px');
    // 			$('.upload-image').hide();
    // 			$('.upload-text').hide();
    // 			$('#image-preview').attr('src', e.target.result);
    // 		}

    // 		reader.readAsDataURL(input.files[0]);

    // 		$('#camera').val('');
    // 	}
    // }

    $('#seriesmediaimagefile').on('change', function () {
        var file_count = $(this)[0].files.length;
        $('#camera').val('');
        if (file_count != 0) {
            $('.upload-img-count').show();
            $('.upload-img-count').text('+' + file_count);
        }
        else {
            file_count = 0;
            $('.upload-video-count').text('+' + file_count);
            $('.upload-img-count').hide();
        }
    });
    $('#seriesmediavideofile').on('change', function () {
        var file_count = 0;
        var file_count = $(this)[0].files.length;
        if (file_count != 0) {
            $('.upload-video-count').show();
            // var getcount = parseInt($('.upload-video-count').text());
            // var total = file_count + getcount;
            $('.upload-video-count').text('+' + file_count);
        }
        else {
            // var getcount = parseInt($('.upload-video-count').text());
            // var total = getcount - 1;
            // $('.upload-video-count').text('+' + total);
            // if (getcount == 0) {
                $('.upload-video-count').hide();
            // }
        }
    });
    $('#seriesmediavideofile_thumbnail').on('change', function () {
        var file_count = 0;
        file_count = $(this)[0].files.length;
        if (file_count != 0) {
            jQuery('.video-thumbnail-icon').attr('src', 'images/Thick.png');
            jQuery('.video-thumbnail-icon').attr('height', '50');
            jQuery('.video-thumbnail-icon').css('margin-top', '-14px');
            jQuery('.upload_thumbnail').popover({content: "Thumbnail Added", placement: "top"});
            jQuery('.upload_thumbnail').popover('show');
            jQuery('.whitecolor').hide();
        }
        else {
            $('.upload-video-count').hide();
        }
    });
</script>


<script language="JavaScript">
    function setup() {
        $('#my_camera').show();
        $('#image-preview').hide();
        $('#my_camera').attr('src', '');
        Webcam.set({
            width: 270,
            height: 310,
            dest_width: 700,
            dest_height: 410,
            image_format: 'jpeg',
            jpeg_quality: 90
        });
        Webcam.attach('#my_camera');
        $('#cameara-button').css('display', 'none');
        $('#cameara-capture-button').show();

        $('.right-arrow').css('top', '20%');
        $('.left-arrow').css('top', '20%');
        $('.upload-instruction').css('margin-top', '0px');
        $('.upload-image').hide();
        $('.upload-text').hide();
    }
</script>
<script>
    function take_snapshot() {
        // take snapshot and get image data
        Webcam.snap(function (data_uri) {
            $('#my_camera').html('<img src="' + data_uri + '" height="300" width="270">');
            $('.upload-img-count').show().text('+1');
            var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
            $('#seriesmediaimagefile').val('');
            $('#camera').val(raw_image_data);
        });
    }
</script>
@stack('footer_script')
</body>
</html>