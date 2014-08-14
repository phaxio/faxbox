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
			<div class="form-group {{ ($errors->has('fax_api_public')) ? 'has-error' : '' }}">
				{{ Form::text('fax_api_public', $settings['fax_api_public'], ['class' => 'form-control', 'placeholder' => 'Api Public', 'id' => 'fax_api_public']) }}
				{{ ($errors->has('fax_api_public') ? $errors->first('fax_api_public') : '') }}
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<div class="form-group {{ ($errors->has('fax_api_secret')) ? 'has-error' : '' }}">
				{{ Form::text('fax_api_secret', $settings['fax_api_secret'], ['class' => 'form-control', 'placeholder' => 'Api Secret', 'id' => 'fax_api_secret']) }}
				{{ ($errors->has('fax_api_secret') ? $errors->first('fax_api_secret') : '') }}
			</div>
		</div>
	</div>
		

    {{ Form::submit(trans('setting.updateApi'), array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
</div>
@stop
