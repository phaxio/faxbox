@section('head')
<link rel="stylesheet" href="{{ asset('colpick/css/colpick.css') }}"/>
<style>
#picker {
	margin:0;
	padding:0;
	border:0;
	width:70px;
	height:20px;
	border-right:20px solid green;
	line-height:20px;
}
</style>
@stop

@section('content')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Fax Api</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

	<div class="row">
		{{ Form::open(array('action' => 'SettingController@updateFaxApi')) }}
		<div class="col-md-5">
			<div class="form-group {{ ($errors->has('services[phaxio][public]')) ? 'has-error' : '' }}">
				{{ Form::text('services[phaxio][public]', $settings['services.phaxio.public'], ['class' => 'form-control', 'placeholder' => 'Api Public']) }}
				{{ ($errors->has('services[phaxio][public]') ? $errors->first('services[phaxio][public]') : '') }}
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<div class="form-group {{ ($errors->has('services[phaxio][secret]')) ? 'has-error' : '' }}">
				{{ Form::text('services[phaxio][secret]', $settings['services.phaxio.secret'], ['class' => 'form-control', 'placeholder' => 'Api Secret']) }}
				{{ ($errors->has('services[phaxio][secret]') ? $errors->first('services[phaxio][secret]') : '') }}
			</div>
		</div>
	</div>
		

    {{ Form::submit(trans('general.update'), array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
</div>
@stop
