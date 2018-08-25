@extends('layouts.front')

@section('pagetitle', 'Upload')

@section('content')
<div class="content-container">
{!! Form::open(['url' => '/series/addnewupload', 'id' => 'formseriesmediaaddnew']) !!}

<div class="col-xs-12 col0 stitle">
    <div class="row row2">
        <div class="clearfix"></div>
        <div class="form-group">
            <textarea id="seriesmediatitle" name="seriesmediatitle" class="form-control input-md invisible-form-control seriesmediatitle seriesmedia-textarea" placeholder="Enter Caption"></textarea>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<div class="clearfix"></div>

<div class="upload">
        <div class="upload1__wrap">
            
            <div class="upload__title">Upload video clip</div>
            <div class="upload__icon"></div>
            <div class="col-xs-12 col0">
                                    <div class="row row0">
                                    <div class="col-xs-12 col0 pt3 d-upload"> 
                                    <div class="upload-content-in-content ">
                    <div class="uploader text-center form-group mb0 upload__btn">
                        <div onclick="selectvideo(this);">
                            <div class=""><span class=""></span></div>
                            <div><span>Upload</span></div>
                        </div>
                        <input type="file" name="seriesmediavideofile" class="seriesmideiavideouploader videofile not-ignore">
                    </div>
            </div>
        </div>
        </div>
        </div>
        <br/>
            <!-- <a href="/upload_days" class="upload__btn">Upload</a>-->
            
            <div class="clearfix"></div>
                        @if($globaldata['user']->publish_day != '')
                            <p class="up_time">Your post will air every {{ $globaldata['user']->publish_day }} @ {{ $globaldata['user']->publish_time }} </p>
                            <div class="clearfix"></div>
                        @else
                            <p class="up_time">when do you want your contents to air?</p>
                            <div class="clearfix"></div>
                        @endif
                        
                        @if($globaldata['user']->publish_day != '')
                            <div class="col-xs-12 col0 pt3">
                                <input type="submit" class="upload3__btn" name="submit" value="DONE">
                                <input type="button" class="upload3__btn up_time" style="display:none" value="DONE">
                            </div>
                            
                        @else
                            <div class="col-xs-12 col0 pt3">
                                <input type="submit" class="upload3__btn"    name="submit" value="DONE">
                                <input type="button" class="upload3__btn up_time" style="display:none"  value="DONE">
                            </div>
                        @endif
                        
                        
        </div>
    </div>

 <div id="my_camera"></div>
        <input type="hidden" name="camera" id="camera" value="">
<button type="button" onClick="take_snapshot()">Take Snapshot</button>

    <div class="upload3" style="display:none">
        @if($globaldata['user']->publish_day != '')
           <input type="hidden" class="choose_another_date" value="" />
        @else
            <input type="hidden" class="choose_another_date" value="yes" />
        @endif
        <p>When do you want your contents to air ? </p>
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
            <div class="row row2">
                <div class="col-xs-6 pt4 width100-500 col2">
                    <div class="row row2">
                       <div class="col-xs-5 width50-500 col2 form-group mb0">
                            <select class="form-control form-control-small seriesmediaimmidiatepublishno-controls" name="seriesmediahour" id="hour">
                                <option value="">HH</option>
                                @for($i=0;$i <= 23;$i++)
                                    @if($i < 10)
                                    <option value="0{{ $i }}"  @if($globaldata['user']->publish_time != "") @if(  explode(':',$globaldata['user']->publish_time)[0]  == $i) selected @endif @endif >0{{ $i }}</option>
                                    @else
                                    <option value="{{ $i }}" @if($globaldata['user']->publish_time != "") @if(  explode(':',$globaldata['user']->publish_time)[0]  == $i) selected @endif @endif >{{ $i }}</option>
                                    @endif
                                @endfor
                            </select>
                        </div>
                        <div class="col-xs-1 hide-500 col2 text-center">:</div>
                        <div class="col-xs-5 width50-500 col2 form-group mb0">
                            <select class="form-control form-control-small seriesmediaimmidiatepublishno-controls" name="seriesmediaminute" id="minute">
                                <option value="">MM</option>
                                @for($i=0;$i <= 59;$i++)
                                    @if($i < 10)
                                    <option value="0{{ $i }}" @if($globaldata["user"]->publish_time != "") @if(  explode(':',$globaldata['user']->publish_time)[1]  == $i) selected @endif @endif >0{{ $i }}</option>
                                    @else
                                    <option value="{{ $i }}" @if($globaldata['user']->publish_time != "") @if(  explode(':',$globaldata['user']->publish_time)[1]  == $i) selected @endif @endif >{{ $i }}</option>
                                    @endif
                                @endfor
                            </select>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col0">
            <div class="row row2">
                <div class="col-xs-6 pt4 width100-500 col2">
                    <div class="row row2">
        <input type="text" name="seriesmediapublish_day" id="currDay" value="{{ $globaldata['user']->publish_day }}" readonly >
        </div>
        </div>
        </div>
        </div>  
          
        <input type="hidden" name="seriesmediaimmidiatepublish" value="0" />
     <div class="col-xs-12 col0">
                                    <div class="row row0">
                                    <div class="col-xs-12 col0 pt3">
                                       <input type="button" class="upload3__btn submitBtn" name="submit" value="DONE" onclick="closeDatePickerView(this);" style="border: none;border-radius: 0;position: fixed;bottom: 0;right: 0;    display: block;">
    
    </div>
    </div>
    </div>
    </div>
     {!! Form::close() !!}
 </div>
@endsection

@section('page-scripts')
<script src="{{ asset('js/series.js?cache=2.7') }}"></script>
@endsection


@push('footer_script')
<script language="JavaScript">
        Webcam.set({
            width: '350',
            height: '350',
            image_format: 'jpeg',
            jpeg_quality: 90
        });
        Webcam.attach( '#my_camera' );     
</script>
<script>
    function take_snapshot() {
            // take snapshot and get image data
            Webcam.snap( function(data_uri) {
                var raw_image_data = data_uri.replace(/^data\:image\/\w+\;base64\,/, '');
                $('#camera').val(raw_image_data);
            } );
        }
</script>
@endpush