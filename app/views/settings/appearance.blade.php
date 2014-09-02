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
            <h1 class="page-header">Appearance</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    
    <div class="row">
    {{ Form::open(array('action' => 'SettingController@updateAppearance', 'enctype' => 'multipart/form-data')) }}
		<div class="col-md-4">
			<div class="form-group {{ ($errors->has('faxbox[name]')) ? 'has-error' : '' }}">
				<label for="faxbox[name]" class="control-label">Site Name</label>
				{{ Form::text('faxbox[name]', $settings['faxbox.name'], ['class' => 'form-control', 'placeholder' => 'Site Name']) }}
				{{ ($errors->has('faxbox[name]') ? $errors->first('faxbox[name]') : '') }}
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-4">
			<div class="form-group {{ ($errors->has('faxbox[logo]')) ? 'has-error' : '' }}">
				<label>Logo</label><br>
				@if($settings['faxbox.logo'])
				<img style="max-width:200px;" src="{{ asset('images/'.$settings['faxbox.logo']) }}"><br>
				@endif
				{{ Form::file('logo', ['accept' => 'image/jpeg,image/png']) }}
				{{ ($errors->has('faxbox[logo]') ? $errors->first('faxbox[logo]') : '') }}
				<small>Logo should be 100 x 50px.</small>
			</div>
		</div>
	</div>	
	
    <div class="row">
		<div class="col-md-2">
			<div class="form-group {{ ($errors->has('faxbox[colors][sidebar]')) ? 'has-error' : '' }}">
				<label for="faxbox[colors][sidebar]" class="control-label">Sidebar Color</label>
				{{ Form::text('faxbox[colors][sidebar]', $settings['faxbox.colors.sidebar'], ['class' => 'form-control', 'placeholder' => 'Sidebar Color', 'id' => 'color1']) }}
				{{ ($errors->has('faxbox[colors][sidebar]') ? $errors->first('faxbox[colors][sidebar]') : '') }}
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group {{ ($errors->has('faxbox[colors][link]')) ? 'has-error' : '' }}">
				<label for="faxbox[colors][link]" class="control-label">Link Color</label>
				{{ Form::text('faxbox[colors][link]', $settings['faxbox.colors.link'], ['class' => 'form-control', 'placeholder' => 'Link Color', 'id' => 'color2']) }}
				{{ ($errors->has('faxbox[colors][link]') ? $errors->first('faxbox[colors][link]') : '') }}
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-2">
			<div class="form-group {{ ($errors->has('faxbox[colors][text]')) ? 'has-error' : '' }}">
				<label for="faxbox[colors][text]" class="control-label">Text Color</label>
				{{ Form::text('faxbox[colors][text]', $settings['faxbox.colors.text'], ['class' => 'form-control', 'placeholder' => 'Text Color', 'id' => 'color3']) }}
				{{ ($errors->has('faxbox[colors][text]') ? $errors->first('faxbox[colors][text]') : '') }}
			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group {{ ($errors->has('faxbox[colors][background]')) ? 'has-error' : '' }}">
				<label for="faxbox[colors][background]" class="control-label">Background Color</label>
				{{ Form::text('faxbox[colors][background]', $settings['faxbox.colors.background'], ['class' => 'form-control', 'placeholder' => 'Background Color', 'id' => 'color4']) }}
				{{ ($errors->has('faxbox[colors][background]') ? $errors->first('faxbox[colors][background]') : '') }}
			</div>
		</div>
	</div>
		

    {{ Form::submit(trans('general.update'), array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
</div>
@stop

@section('scripts')
<script type="text/javascript" src="{{ asset('colpick/js/colpick.js') }}"></script>

<script>
$(document).ready(function(){
	var options = {
		layout:'hex',
		submit:0,
		colorScheme:'dark',
		onChange:function(hsb,hex,rgb,el,bySetColor) {
			$(el).css('border-color','#'+hex);
			// Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
			if(!bySetColor) $(el).val(hex);
		}
	}
    $('#color1').colpick(options).keyup(function(){
    	$(this).colpickSetColor(this.value);
    });
    
    $('#color2').colpick(options).keyup(function(){
		$(this).colpickSetColor(this.value);
	});
        
	$('#color3').colpick(options).keyup(function(){
		$(this).colpickSetColor(this.value);
	});
            
	$('#color4').colpick(options).keyup(function(){
		$(this).colpickSetColor(this.value);
	});
});
</script>
@stop