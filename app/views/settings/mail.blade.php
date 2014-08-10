@section('content')
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Mail Settings</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        {{ Form::open(array('action' => 'SettingController@updateMail')) }}
		<div class="col-md-12">
			<div class="form-group {{ ($errors->has('driver')) ? 'has-error' : '' }}">
				<label>Mail driver</label>
		        {{ Form::select('mail[driver]', ["smtp" => "SMTP", "mail" => "mail", "sendmail" => "sendmail", "mailgun" => "mailgun"]) }}
        	</div>
        </div>
	</div>
    
    <div class="row">
        <div class="col-md-2">
			<div class="form-group {{ ($errors->has('area')) ? 'has-error' : '' }}">
				{{ Form::text('mail[host]', null, ['class' => 'form-control', 'placeholder' => 'Mail Host']) }}
				{{ ($errors->has('host') ? $errors->first('host') : '') }}
			</div>
		</div>
		
		<div class="col-md-1">
			<div class="form-group {{ ($errors->has('area')) ? 'has-error' : '' }}">
				{{ Form::text('mail[port]', '587', ['class' => 'form-control text-center', 'placeholder' => 'SMTP Port']) }}
				{{ ($errors->has('port') ? $errors->first('port') : '') }}
			</div>
		</div>
    </div>
    
	<div class="row">
		<div class="col-md-3">
			<div class="form-group {{ ($errors->has('username')) ? 'has-error' : '' }}">
				{{ Form::text('mail[username]', null, ['class' => 'form-control', 'placeholder' => 'SMTP Username']) }}
				{{ ($errors->has('username') ? $errors->first('username') : '') }}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-3">
			<div class="form-group {{ ($errors->has('password')) ? 'has-error' : '' }}">
				{{ Form::text('mail[password]', null, ['class' => 'form-control', 'placeholder' => 'SMTP Password']) }}
				{{ ($errors->has('password') ? $errors->first('password') : '') }}
			</div>
		</div>
	</div>


    {{ Form::submit(trans('setting.updateMail'), array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
</div>
@stop