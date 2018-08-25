@extends('layouts.front')

@section('pagetitle', 'Series')

@section('content')
{!! Form::open(['url' => '/series/addnewupload', 'id' => 'formseriesmediaaddnew']) !!}

<div class="upload3">
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
                                    <option value="0{{ $i }}">0{{ $i }}</option>
                                    @else
                                    <option value="{{ $i }}">{{ $i }}</option>
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
                                    <option value="0{{ $i }}">0{{ $i }}</option>
                                    @else
                                    <option value="{{ $i }}">{{ $i }}</option>
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
        <input type="text" name="seriesmediapublish_day" id="currDay" readonly >
        </div>
		</div>
		</div>
		</div>	
           <div class="col-xs-12 col0">
									<div class="row row0">
									<div class="col-xs-12 col0 pt3 d-upload"> 
								    <div class="upload-content-in-content ">
                    <div class="uploader text-center form-group mb0">
                        <div onclick="selectvideo(this);">
                            <div><span class="fa fa-cloud-upload"></span></div>
                            <div><span>Upload</span></div>
                        </div>
                        <input type="file" name="seriesmediavideofile" class="seriesmideiavideouploader videofile not-ignore">
                    </div>
            </div>
        </div>
		</div>
		</div>
		<input type="hidden" name="seriesmediaimmidiatepublish" value="0" />
		<!--<div class="col-xs-12 col0">
									<div class="row row0">
									<div class="col-xs-12 col0 pt3">
									<small>
										<label class="icheck-label mb0 pointer">
											<input type="radio" checked="checked" name="seriesmediaimmidiatepublish" value="1" class="seriesmedia_immidiatepublish_yes radios"> 
											Air Immediately
										</label>
									</small>
									<small>Or</small>
								</div>
										<div class="col-xs-12 col0">
											<small>
												<label class="icheck-label mb0 pointer">
													<input type="radio" name="seriesmediaimmidiatepublish" value="0" class="seriesmedia_immidiatepublish_no radios"> 
													<span class="inline-block">Select Date & Time</span> 
												</label>
											</small>		
										</div>
										</div>
										</div> -->
     <div class="col-xs-12 col0">
									<div class="row row0">
									<div class="col-xs-12 col0 pt3">
        <input type="submit" class="upload3__btn" name="submit" value="DONE">
    </div>
	</div>
	</div>
    </div>
    {!! Form::close() !!}
@endsection

@section('page-scripts')

<script src="{{ asset('js/series.js?cache=2.7') }}"></script>
@endsection