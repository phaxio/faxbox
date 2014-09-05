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

	@if(isset($_ENV['USE_LOCAL_STORAGE']) && !$_ENV['USE_LOCAL_STORAGE'])
	<div class="row">
		<div class="alert alert-info">Phaxio API keys are currently loaded via environment variables because local storage of configuration is disabled.</div>
	</div>
	@else
	<div class="row">
		{{ Form::open(array('action' => 'SettingController@updateFaxApi')) }}
		<div class="col-md-5">
			<div class="form-group {{ ($errors->has('services[phaxio][public]')) ? 'has-error' : '' }}">
				<label for="services[phaxio][public]" class="control-label">Key</label>
				{{ Form::text('services[phaxio][public]', $settings['services.phaxio.public'], ['class' => 'form-control', 'placeholder' => 'Key']) }}
				{{ ($errors->has('services[phaxio][public]') ? $errors->first('services[phaxio][public]') : '') }}
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<div class="form-group {{ ($errors->has('services[phaxio][secret]')) ? 'has-error' : '' }}">
				<label for="services[phaxio][secret]" class="control-label">Secret</label>
				{{ Form::text('services[phaxio][secret]', $settings['services.phaxio.secret'], ['class' => 'form-control', 'placeholder' => 'Secret']) }}
				{{ ($errors->has('services[phaxio][secret]') ? $errors->first('services[phaxio][secret]') : '') }}
			</div>
		</div>
	</div>
	@endif
		

    {{ Form::submit(trans('general.update'), array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
</div>
@stop
