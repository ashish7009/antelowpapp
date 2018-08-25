    @extends('layouts.front')

    @section('pagetitle', 'Series')

    @section('content')

    <style>
    .content-section {
        background-color: #f6f6f6;
    }
</style>
<script>
    function getVideoHeight(url, fnCallback) {
        var video = document.createElement("video");
        video.autoplay = true;
        video.oncanplay = function () {
            fnCallback(this.offsetWidth, this.offsetHeight);
            this.src = "about:blank";
            document.body.removeChild(video);
        };

        document.body.appendChild(video);
        video.src = url;

    }
</script>
<div class="content-container pb0 mobil-feed">
    <div class="container">
        <div class="row">
            <div class="modal in" id="storymodel" tabindex="-1" role="dialog" aria-hidden="false"
            style="display:block;">
            <div class="modal-dialog1" role="document">
                <div class="modal-content1">
                    <div class="modal-body modalfull">
                        <div class="row btn-background">
                            <div class="col-xs-6">
                                <!-- text-center -->
                                <a href="{{url('/series')}}" class="close sch-close">
                                    <span aria-hidden="true">Close</span>
                                </a>
                            </div>
                            <div class="col-xs-6 pull pull-right">
                                <div class="col-xs-4"></div>
                                <div class="col-xs-8 pt3 text-center btn-container">
                                    <button class="btn btn-primary btn-play hidden" type="button">
                                        <i class="fa fa-play" aria-hidden="true"></i>
                                    </button>
                                    <button class="btn btn-primary btn-pause " type="button">
                                        <i class="fa fa-pause" aria-hidden="true"></i>
                                    </button>
                                    <div class="slide-progress"></div>
                                </div>
                            </div>
                            <!-- <div class="col-xs-3"> -->
                                <!-- <div class="col-xs-12 col0 pt3">
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button"
                                        data-toggle="dropdown">
                                        <img src="{{asset('images/sm-video-play-grey.png')}}" width="25"
                                        height="25">
                                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                          <li><a href="javascript:void(0);" class="btn-slide-show"
                                         data-published="1">Available Episodes</a></li>
                                          <li><a href="javascript:void(0);" class="btn-slide-show"
                                         data-published="0">All Episodes</a></li>
                                        </ul>
                                    </div>
                                </div> -->
                            <!-- </div> -->
                            
                        </div>

                        <div class="slide1">
                            <div class="row">
                                <div class="col-xs-12 text-center upload-instruction no-padding">
                                   @if(count($seriesmedias) > 0)
                                    <div class="owl-carousel owl-theme published-episode">

                                    @foreach($seriesmedias as $index => $seriesmedia)
                                    @if($seriesmedia->ispublished)
                                    <div class="item @if($seriesmedia->ispublished) published-episode @else unpublished-episode @endif ">
                                        <div data-seriesmediaid="{{ $seriesmedia->seriesmediaid }}"
                                           class="embed-responsive embed-responsive-16by9 video video100 seriesmediaid_{{$seriesmedia->seriesmediaid}} media-item @if($seriesmedia->ispublished) bindvideoplayanalytics @else  na-video {{ $seriesmedia->publishdateday }} @endif @if($seriesmedia->imagefile) imageheight @endif"
                                           @if(!$seriesmedia->ispublished) data-publishdateair="{{$seriesmedia->publishdateair }}" @endif>
                                            
                                            @if($seriesmedia->imagefile)
                                                <div class="pop" style="width: 100%;"
                                                data-title="{{ $seriesmedia->title }}">
                                                    <img src="/uploads/{{ $seriesmedia->imagefile }}"
                                                    onload="javascript:checkImg(this)"
                                                    class="popup-image @if($seriesmedia->ispublished) @else  @if($seriesmedia->imagefile) imageBlur  @endif @endif">
                                                </div>
                                                @if(!$seriesmedia->ispublished)
                                                <img src="{{ asset('images/Photo logo.png') }}"
                                                alt="Photo"
                                                class="image_on_video">
                                                @endif
                                                @if(!$seriesmedia->ispublished)
                                                   <div class="rel-video rel-video1 rel-video rel-video1-img {{ $seriesmedia->publishdateday }}"
                                                       @if(!$seriesmedia->ispublished) data-publishdateair="{{ $seriesmedia->publishdateair }}" @endif>
                                                       <span style="font-size: 26px; font-style: italic; margin-right: 5px;"></span>
                                                       
                        <div class="{{ $seriesmedia->publishdateday }} watchlike "><span><?php echo str_replace('on ','<div class="on">on</div>',str_replace('You can see this','<div class="avail">Available</div>',$seriesmedia->publishdateair)); ?></span></div>
                                                   
                                                   </div>
                                                   <div class="coming-soon-cls"><img
                                                    src="{{ asset('images/Comming_soon_1.png') }}"
                                                    class="commig_soon_lb"></div>
                                               @endif
                                            @else
                                                @if($seriesmedia->isfile)
                                                    @if($seriesmedia->hasthumb)
                                                        <div class="thumbimg"
                                                          style="background-image: url({{ asset($seriesmedia->thumbpath) }});"></div>
                                                    @endif
                                                    @if($seriesmedia->hasfile)
                                                        @if($seriesmedia->filethumbname != '')
                                                           <video id="series-{{$seriesmedia->seriesmediaid}}"
                                                             class="seeris-video embed-responsive-item bg modal-video"
                                                             preload="metadata" set-auto="false"
                                                             playsinline loop 
                                                             data-published="@if($seriesmedia->ispublished){{1}}@else{{0}}@endif">
                                                             <source src="{{ $seriesmedia->filepath }}#t=0.5"
                                                                type="video/mp4">
                                                                <source src="{{ $seriesmedia->filepath }}#t=0.5"
                                                                    type="video/ogg">
                                                                    <source src="{{ $seriesmedia->filepath }}#t=0.5"
                                                                        type="video/quicktime">
                                                                        Your browser does not support HTML5 video.
                                                            </video>
                                                        @else
                                                            <video id="series-{{$seriesmedia->seriesmediaid}}"
                                                             set-auto="false"
                                                             class="seeris-video embed-responsive-item bg modal-video"
                                                             data-published="@if($seriesmedia->ispublished){{1}}@else{{0}}@endif"
                                                             preload="metadata" playsinline loop autoplay>
                                                             <source src="{{ $seriesmedia->filepath }}#t=0.5"
                                                                type="video/mp4">
                                                                <source src="{{ $seriesmedia->filepath }}#t=0.5"
                                                                    type="video/ogg">
                                                                    <source src="{{ $seriesmedia->filepath }}#t=0.5"
                                                                        type="video/quicktime">
                                                                        Your browser does not support HTML5 video.
                                                            </video>
                                                        @endif
                                                        @if(!$seriesmedia->ispublished)
                                                            <img src="{{ asset('images/Video logo.png') }}"
                                                            alt="Video"
                                                            class="image_on_video">
                                                        @else
                                                            <img src="{{ asset('images/videobtn.png') }}"
                                                            alt="" class="carousel_playbtn playbtn">
                                                        @endif
                                                    @else
                                                            <div class="embed-responsive-item noimage-bg"></div>
                                                    @endif
                                                @else
                                                    @if($seriesmedia->hasurlthumb)
                                                    <div class="thumbimg"
                                                    style="background-image: url({{ asset($seriesmedia->urlthumbpath) }});"></div>
                                                    @endif
                                                    <iframe class="embed-responsive-item"
                                                    src="{{ $seriesmedia->workingurl }}"
                                                    allowfullscreen></iframe>
                                                @endif
                                                @if(!$seriesmedia->ispublished)
                                               <div class="rel-video rel-video1 rel-video rel-video1-vd {{ $seriesmedia->publishdateday }}"
                                                   @if(!$seriesmedia->ispublished) data-publishdateair="{{ $seriesmedia->publishdateair }}" @endif>
                                                   <span style="font-size: 26px; font-style: italic; margin-right: 5px;"></span>
                                                   
                        <div class="{{ $seriesmedia->publishdateday }} watchlike "><span><?php echo str_replace('on ','<div class="on">on</div>',str_replace('You can see this','<div class="avail">Available</div>',$seriesmedia->publishdateair)); ?></span></div>
                                                 
                                               </div>
                                               <div class="coming-soon-cls"><img
                                                src="{{ asset('images/Comming_soon_1.png') }}"
                                                class="commig_soon_lb"></div>
                                            @endif
                                            @endif
                                        </div>
                                        <div class="row sch-bottom">
                                            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 profile-image-container no-padding">
                                                <img src="{{ asset($seriesmedia->series->user->imagefilepath) }}"
                                                                alt="{{ ucfirst($seriesmedia->series->user->fullname) }}"
                                                                class="img-responsive inline-block img-thumbnail"
                                                                id="profile-image">
                                            </div>
                                            <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7 user-name-text text-left">
                                                <span class="inline-block">
                                                    {{ ucfirst($seriesmedia->series->user->fullname) }}
                                                </span>
                                            </div>
                                            <div class="col-xs-3 col-sm-3 col-md-3 story-count no-padding"> 
                                                <!-- @if($seriesmedia->ispublished) -->
                                                   <!--  <i class="fa fa-television" aria-hidden="true"></i>
                                                    <span class="upcoming-count">{{ $seriesmedia->episodes_will_air }}</span> -->
                                                <!-- @endif -->
                                                <!-- <span @if(!$seriesmedia->ispublished) style="margin-left:25px;"
                                                                  @endif class="star_pedding">
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    @if($seriesmedia->request_counter == 0)
                                                        <span class="request-count">{{'0'}}</span>
                                                    @else
                                                        @if($seriesmedia->request_counter == 1)
                                                        <span class="request-count">1</span>
                                                        @else
                                                        <span class="request-count">{{$seriesmedia->request_counter}} </span>
                                                        @endif
                                                    @endif
                                                    </span>-->
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding" style="margin-top:-4px;">
                                                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 "></div>
                                                    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 no-padding story-title-container text-left">
                                                        <!-- <div class="story-title">{{ $seriesmedia->title }}</div>  -->
                                                         <div class="story-title box left-skew">Now Available</div> 
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
<!-- <script src="{{ asset('js/series.js?cache=2.7') }}"></script> -->
@endsection


@push('footer_script')

<script>
    checkImg = function (img) {
                $("<img/>") // Make in memory copy of image to avoid css issues
                .attr("src", $(img).attr("src"))
                .load(function () {
                        pic_real_width = this.width;   // Note: $(this).width() will not
                        pic_real_height = this.height; // work for in memory images.
                        var imgClass = (this.width / this.height > 1) ? 'wide' : 'tall';
                        $(img).addClass(imgClass);
                    });
            };
            jQuery(document).ready(function () {

                bindvideothumb();

                jQuery("#storymodel").find("video").each(function () {
                    var ele = jQuery(this);
                    getVideoHeight(jQuery(this).find("source").attr("src"),
                        function (w, h) {
                            if (w > h) {
                                ele.addClass("landscape_cls");
                                ele.parent().addClass('potrait_parent');
                            } else {
                                ele.addClass("potrait_cls");
                                ele.parent().addClass('landscape');

                            }
                            console.log(w + "X" + h);
                        }
                        );
                });
            });
        </script>
        <script>
            var owl = $('.owl-carousel');

            function startProgressBar() {
                // apply keyframe animation
                $(".slide-progress").css({
                    width: "100%",
                    transition: "width 5000ms",
                    animationPlayState: "running"
                });
            }

            function resetProgressBar() {
                $(".slide-progress").css({
                    width: 0,
                    transition: "width 0s"
                });
            }

            owl.on('drag.owl.carousel', function (e) {
                $('.btn-play').addClass('hidden');
                $('.btn-pause').removeClass('hidden');
                $(".slide-progress").css("animation-play-state", "running");
            });

            owl.on('translate.owl.carousel', function (e) {
                $('.owl-item video').each(function () {
                    $(this).get(0).pause();
                    $('.owl-item').find('.playbtn').show();
                });
                resetProgressBar();
            });
            owl.on('translated.owl.carousel', function (e) {
                var isVideo = $('.owl-item.active video').get(0);
                var isPublished =  $('.owl-item.active video').attr('data-published');
                
                if (isVideo !== undefined && isPublished == '1') {
                    $('.owl-item.active video').get(0).play();
                    $('.owl-item.active').find('.playbtn').hide();
                }
                startProgressBar();
            })

            $('.btn-play').on('click', function () {
                owl.trigger('play.owl.autoplay', [5000]);
                $('.btn-play').addClass('hidden');
                $('.btn-pause').removeClass('hidden');
                startProgressBar();
            })
            $('.btn-pause').on('click', function () {
                owl.trigger('stop.owl.autoplay');
                $('.btn-play').removeClass('hidden');
                $('.btn-pause').addClass('hidden');
                $(".slide-progress").css("animation-play-state", "paused");
                $(".slide-progress").css("width",$(".slide-progress").width());
            })

            $(document).on('click', '.btn-slide-show', function () {
                // alert('clicked');
                var type = $(this).attr('data-published');
                console.log(type);
                if (type == 1) {
                    display_episodes = 'all';
                    $('.all-episode').addClass('hidden');
                    $('.published-episode').removeClass('hidden');
                } else {
                    display_episodes = 'published';
                    $('.all-episode').removeClass('hidden');
                    $('.published-episode').addClass('hidden');
                }
                // $('.owl-carousel').trigger('refresh.owl.carousel');
            });


            // Close button css
            jQuery(document).ready(function () {
                var owl = $('.owl-carousel');
                owl.owlCarousel({
                    animateOut: 'zoomOutLeft',
                    // animateIn: 'zoomInLeft',
                    loop: true,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    autoplayHoverPause: false,
                    slideSpeed: 5000,
                    paginationSpeed: 5000,
                    lazyLoad: true,
                    items: 1,
                    dots: false,
                    video: true,
                    videoHeight: '60%',
                    checkVisible: true,
                    responsiveClass: true,
                    responsive: {
                        0: {
                            items: 1,
                            nav: true
                        },
                        320: {
                            items: 1,
                            nav: false,
                            loop: true
                        }
                    },
                    onInitialized: startProgressBar,
                });
                // });

            });
        </script>
        <style>
        .slide-progress {
            width: 0;
            max-width: 100%;
            height: 4px;
            background: #ff0751;
            border-radius: 5px;
            margin-top: 3px;
            padding:0 5px;
        }

        .air-count img {
            position: absolute;
            text-align: center;
            top: 4px;
            left: 153px;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .air-count .count {
            position: absolute;
            width: 14px;
            height: 14px;
            background: #ee2d4f;
            color: white;
            text-align: center;
            border-radius: 50%;
            top: -3px;
            left: 169px;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99;
        }

        .air-count .count-title {
            position: absolute;
            height: 20px;
            background: none !important;
            top: 2px;
            left: 154px;
            width: auto;
        }

        .upcoming-count {
            position: absolute;
            font-size: 12px;
            /* width: 20px; */
            /* height: 20px; */
            width: 14px;
            height: 14px;
            background: #1885FE;
            color: white;
            text-align: center;
            border-radius: 50%;
            top: 2px;
            right: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .request-count {
            position: absolute;
            font-size: 12px;
            /* width: 20px; */
            /* height: 20px; */
            width: 14px;
            height: 14px;
            background: #1885FE;
            color: white;
            text-align: center;
            border-radius: 50%;
            top: 2px;
            right: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

       
        
    </style>
    @endpush