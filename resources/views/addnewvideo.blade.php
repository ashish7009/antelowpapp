@if(isset($globaldata['user']))
<div class="add-new-video-container wow fadeInDown">
	<div class="upload-circle text-center fa fa-paper-plane-o color-white bg-yellow"></div>
	{!! Form::open(['url' => '/series/addnew', 'id' => 'formseriesmediaaddnew']) !!}
		<div class="upload-content">
			<div class="upload-content-in">
				<div class="upload-content-in-content">
					<div class="uploader text-center form-group mb0">
						<div onclick="selectvideo(this);">
							<div><span class="fa fa-cloud-upload"></span></div>
							<div><span>Upload</span></div>
						</div>
						<input type="file" name="seriesmediavideofile" class="seriesmideiavideouploader videofile not-ignore">
					</div>
					<div class="content-right">
						<div class="form-group mb0">
							<input type="text" name="seriesmediatitle" class="form-control form-control-small invisible-form-control seriesmediatitle" placeholder="Enter Caption" />
						</div>
						<div class="div-textual">
							<div><small>When do you want this to air?</small></div>
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
									<div class="row row0">
										<div class="col-xs-12 col0">
											<small>
												<label class="icheck-label mb0 pointer">
													<input type="radio" name="seriesmediaimmidiatepublish" value="0" class="seriesmedia_immidiatepublish_no radios"> 
													<span class="inline-block">Select Date & Time</span> 
												</label>
											</small>		
										</div>
										<div class="col-xs-12 col0">
											<div class="row row2">
												<div class="col-xs-6 pt4 width100-500 col2">
													<div class="row row2">
														<div class="col-xs-6 col2 mb0 form-group">
															<select class="form-control form-control-small seriesmediaimmidiatepublishno-controls" name="seriesmediamonth">
																<option value="">MM</option>
																@for($i=1;$i <= 12;$i++)
																	@if($i < 10)
																	<option value="0{{ $i }}">{{ date('M', mktime(0, 0, 0, $i, 10)) }}</option>
																	@else
																	<option value="{{ $i }}">{{ date('M', mktime(0, 0, 0, $i, 10)) }}</option>
																	@endif
																@endfor
															</select>
														</div>
														<div class="col-xs-6 col2 mb0 form-group">
															<select class="form-control form-control-small seriesmediaimmidiatepublishno-controls" name="seriesmediadate">
																<option value="">DD</option>
																@for($i=1;$i <= 31;$i++)
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
												<div class="col-xs-6 pt4 width100-500 col2">
													<div class="row row2">
														<div class="col-xs-5 width50-500 col2 form-group mb0">
															<select class="form-control form-control-small seriesmediaimmidiatepublishno-controls" name="seriesmediahour">
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
															<select class="form-control form-control-small seriesmediaimmidiatepublishno-controls" name="seriesmediaminute">
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
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="text-right">
				<input type="submit" value="Air" class="btn btn-warning btn-sm air-btn box-shadow-white" />
			</div>
		</div>
	{!! Form::close() !!}
</div>
@endif