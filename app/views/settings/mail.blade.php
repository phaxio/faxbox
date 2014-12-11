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
		        {{ Form::select('mail[driver]', ["smtp" => "SMTP", "mail" => "mail", "sendmail" => "sendmail", "mailgun" => "mailgun"], $settings['mail.driver']) }}
        	</div>
        </div>
	</div>
    
	<div class="row">
		<div class="col-md-4">
			<div class="form-group {{ ($errors->has('mail.from.address')) ? 'has-error' : '' }}">
				<label for="mail[from][address]" class="control-label">From Address</label>
				{{ Form::text('mail[from][address]', $settings['mail.from.address'], ['class' => 'form-control', 'placeholder' => 'From Email']) }}
				{{ ($errors->has('mail.from.address') ? $errors->first('mail.from.address') : '') }}
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<div class="form-group {{ ($errors->has('mail.from.name')) ? 'has-error' : '' }}">
				<label for="mail[from][name]" class="control-label">From Name</label>
				{{ Form::text('mail[from][name]', $settings['mail.from.name'], ['class' => 'form-control', 'placeholder' => 'From Name']) }}
				{{ ($errors->has('mail.from.name') ? $errors->first('mail.from.name') : '') }}
			</div>
		</div>
	</div>
    		
    <div class="smtp-settings" style="display:none">
		<div class="row">
			<div class="col-md-2">
				<div class="form-group {{ ($errors->has('mail.host')) ? 'has-error' : '' }}">
					<label for="mail[host]" class="control-label">Host</label>
					{{ Form::text('mail[host]', $settings['mail.host'], ['class' => 'form-control', 'placeholder' => 'Mail Host']) }}
					{{ ($errors->has('mail.host') ? $errors->first('mail.host') : '') }}
				</div>
			</div>
			
			<div class="col-md-1">
				<div class="form-group {{ ($errors->has('mail.port')) ? 'has-error' : '' }}">
					<label for="mail[port]" class="control-label">Port</label>
					{{ Form::text('mail[port]', $settings['mail.port'], ['class' => 'form-control text-center', 'placeholder' => 'SMTP Port']) }}
					{{ ($errors->has('mail.port') ? $errors->first('mail.port') : '') }}
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-4">
				<div class="form-group {{ ($errors->has('mail.username')) ? 'has-error' : '' }}">
					<label for="mail[username]" class="control-label">SMTP Username</label>
					{{ Form::text('mail[username]', $settings['mail.username'], ['class' => 'form-control', 'placeholder' => 'SMTP Username']) }}
					{{ ($errors->has('mail.username') ? $errors->first('mail.username') : '') }}
				</div>
			</div>
		</div>
	
		<div class="row">
			<div class="col-md-4">
				<div class="form-group {{ ($errors->has('mail.password')) ? 'has-error' : '' }}">
					<label for="mail[password]" class="control-label">SMTP Password</label>
					{{ Form::text('mail[password]', $settings['mail.password'], ['class' => 'form-control', 'placeholder' => 'SMTP Password']) }}
					{{ ($errors->has('mail.password') ? $errors->first('mail.password') : '') }}
				</div>
			</div>
		</div>
	</div>
	
	<div class="mailgun-settings" style="display:none">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group {{ ($errors->has('services.mailgun.domain')) ? 'has-error' : '' }}">
					<label for="services[mailgun][domain]" class="control-label">Mailgun Domain</label>
					{{ Form::text('services[mailgun][domain]', $settings['services.mailgun.domain'], ['class' => 'form-control', 'placeholder' => 'Mailgun Domain']) }}
					{{ ($errors->has('services.mailgun.domain') ? $errors->first('services.mailgun.domain') : '') }}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group {{ ($errors->has('services.mailgun.secret')) ? 'has-error' : '' }}">
					<label for="services[mailgun][secret]" class="control-label">API Key</label>
					{{ Form::text('services[mailgun][secret]', $settings['services.mailgun.secret'], ['class' => 'form-control', 'placeholder' => 'Mailgun API Key']) }}
					{{ ($errors->has('services.mailgun.secret') ? $errors->first('services.mailgun.secret') : '') }}
				</div>
			</div>
		</div>
	</div>


    {{ Form::submit(trans('general.update'), array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
</div>
@stop

@section('scripts')
<script>
$(function(){
	$('select[name="mail[driver]"]').on('change', function(){
		showDriverDetails();
	});
	
	function showDriverDetails(val)
	{
		$(".smtp-settings").slideUp();
		$(".mailgun-settings").slideUp();
		
		var val = $('select[name="mail[driver]"]').val();
		
		if(val == 'smtp') $(".smtp-settings").slideDown();
    		
		if(val == 'mailgun') $(".mailgun-settings").slideDown();
	}
	
	showDriverDetails();
	
});
</script>
@stop